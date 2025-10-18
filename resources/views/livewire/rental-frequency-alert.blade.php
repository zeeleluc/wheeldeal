<div>
    @if ($showAlert)
        <div class="bg-yellow-100 border-b border-yellow-300 text-yellow-900 text-sm text-center py-2 px-4">
            {{ __('You have recently booked a car. Please wait a few days before booking another.') }}
        </div>
    @endif
</div>
