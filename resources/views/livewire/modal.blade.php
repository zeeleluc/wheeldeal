@if($isOpen)
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-lg">
            {{ $slot }}
            <div class="mt-4 text-right">
                <button wire:click="$emit('closeModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Cancel
                </button>
            </div>
        </div>
    </div>
@endif
