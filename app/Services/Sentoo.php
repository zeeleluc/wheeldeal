<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\Reservation;
use App\Enums\PaymentStatus;

class Sentoo
{
    protected string $apiUrl;
    protected string $secret;
    protected string $merchantId;

    public function __construct()
    {
        $this->apiUrl = config('services.sentoo.api_url');
        $this->secret = config('services.sentoo.secret');
        $this->merchantId = config('services.sentoo.merchant_id');
    }

    public function createPayment(Reservation $reservation, string $currency, string $description): ?array
    {
        $returnUrl = rtrim(config('app.url'), '/').'/'.ltrim(config('services.sentoo.return_url'), '/');

        try {
            $response = Http::asForm()
                ->withHeaders(['X-SENTOO-SECRET' => $this->secret])
                ->post("{$this->apiUrl}/payment/new", [
                    'sentoo_merchant' => $this->merchantId,
                    'sentoo_amount' => $reservation->total_price_cents,
                    'sentoo_currency' => $currency,
                    'sentoo_description' => $description,
                    'sentoo_return_url' => $returnUrl,
                ]);

            $data = $response->json();

            if ($response->successful() && isset($data['success']['data']['url'])) {
                $identification = $data['success']['message'];

                Payment::create([
                    'reservation_id' => $reservation->id,
                    'identification' => $identification,
                    'status' => PaymentStatus::PENDING,
                ]);

                return [
                    'url' => $data['success']['data']['url'],
                    'identification' => $identification,
                ];
            }

            Log::error('Sentoo payment creation failed', $data);

            return null;

        } catch (\Exception $e) {
            Log::error('Sentoo payment creation exception', ['exception' => $e]);

            return null;
        }
    }

    public function fetchStatus(string $transactionId): ?PaymentStatus
    {
        try {
            $response = Http::withHeaders(['X-SENTOO-SECRET' => $this->secret])
                ->get("{$this->apiUrl}/payment/status/{$this->merchantId}/{$transactionId}");

            $data = $response->json();

            if (isset($data['success']['message'])) {
                return PaymentStatus::from($data['success']['message']);
            }

            Log::warning('Sentoo status fetch returned invalid response', ['data' => $data]);
        } catch (\Exception $e) {
            Log::error('Sentoo status fetch error', ['transaction_id' => $transactionId, 'exception' => $e]);
        }

        return null;
    }

    public function handleWebhook(array $payload): void
    {
        $transactionId = $payload['transaction_id'] ?? null;

        if (!$transactionId) {
            Log::warning('Webhook missing transaction_id', $payload);

            return;
        }

        $payment = Payment::where('identification', $transactionId)->first();

        if (!$payment) {
            Log::warning('Webhook for unknown payment', ['transaction_id' => $transactionId]);

            return;
        }

        if (!in_array($payment->status, [PaymentStatus::PENDING])) {
            return;
        }

        $status = $this->fetchStatus($transactionId);

        if (!$status) {
            Log::warning('Unable to resolve payment status', ['transaction_id' => $transactionId]);

            return;
        }

        $payment->markAs($status);
        $payment->reservation->resolveStatus();

        if (PaymentStatus::SUCCESS === $status) {
            $payment->reservation->paid();
        }

        Log::info('Sentoo webhook processed', [
            'payment_id' => $payment->id,
            'status' => $status->value,
            'reservation_id' => $payment->reservation_id,
        ]);
    }
}
