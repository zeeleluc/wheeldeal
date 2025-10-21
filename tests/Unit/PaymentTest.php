<?php

namespace Tests\Unit;

use App\Enums\PaymentStatus;
use App\Enums\ReservationType;
use App\Models\User;
use App\Policies\ReservationPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestCaseHelpers\BaseTestHelpers;

class PaymentTest extends TestCase
{
    use RefreshDatabase;
    use BaseTestHelpers;

    protected ReservationPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ReservationPolicy();
    }

    /**
     * TC001: Successful payment.
     */
    public function testSuccessfulPayment(): void
    {
        $user = User::factory()->create();
        $reservation = $this->createReservation(['user' => $user, 'status' => ReservationType::PENDING_PAYMENT]);

        $payment = $this->createPayment($reservation, PaymentStatus::SUCCESS);
        $reservation->paid();

        $this->assertEquals(PaymentStatus::SUCCESS, $payment->status);
        $this->assertFalse($this->policy->pay($user, $reservation));
    }

    /**
     * TC002: Pending payment.
     */
    public function testPendingPayment(): void
    {
        $user = User::factory()->create();
        $reservation = $this->createReservation(['user' => $user, 'status' => ReservationType::PENDING_PAYMENT]);

        $payment = $this->createPayment($reservation, PaymentStatus::PENDING);

        $this->assertEquals(PaymentStatus::PENDING, $payment->status);
        $this->assertFalse($this->policy->pay($user, $reservation)); // Cannot pay again
    }

    /**
     * TC003: Unsuccessful payment (cancelled/rejected).
     */
    public function testUnsuccessfulPayment(): void
    {
        $user = User::factory()->create();
        $reservation = $this->createReservation(['user' => $user, 'status' => ReservationType::PENDING_PAYMENT]);

        $payment = $this->createPayment($reservation, PaymentStatus::REJECTED);

        $this->assertEquals(PaymentStatus::REJECTED, $payment->status);
        $this->assertTrue($this->policy->pay($user, $reservation));

        $payment = $this->createPayment($reservation, PaymentStatus::CANCELLED);

        $this->assertEquals(PaymentStatus::CANCELLED, $payment->status);
        $this->assertTrue($this->policy->pay($user, $reservation));

        $payment = $this->createPayment($reservation, PaymentStatus::SUCCESS);
        $reservation->paid();

        $this->assertEquals(PaymentStatus::SUCCESS, $payment->status);
        $this->assertFalse($this->policy->pay($user, $reservation));
    }

    /**
     * TC004: Asynchronous success payment.
     */
    public function testAsyncSuccessPayment(): void
    {
        $user = User::factory()->create();
        $reservation = $this->createReservation(['user' => $user, 'status' => ReservationType::PENDING_PAYMENT]);

        // Step 1: initial pending
        $pendingPayment = $this->createPayment($reservation, PaymentStatus::PENDING);
        $this->assertFalse($this->policy->pay($user, $reservation));

        // Step 2: later success callback
        $pendingPayment = $this->createPayment($reservation, PaymentStatus::SUCCESS);
        $reservation->paid();

        $this->assertEquals(PaymentStatus::SUCCESS, $pendingPayment->status);
        $this->assertFalse($this->policy->pay($user, $reservation));
    }
}
