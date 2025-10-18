<div>
    <a href="{{ $disabled ? '#' : route('reservation.create') }}"
            @class([
                'mb-4 px-4 py-2 text-white font-medium rounded-lg transition-colors',
                'bg-green-600 hover:bg-green-700' => !$disabled,
                'bg-gray-400 cursor-not-allowed' => $disabled,
                $class
            ])
            {{ $disabled ? 'aria-disabled=true' : '' }}
    >
        {{ __($title) }}
    </a>
</div>