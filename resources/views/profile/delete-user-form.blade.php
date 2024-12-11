<x-action-section>
    <x-slot name="title">
        {{ __('Delete Account') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Permanently delete your account.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </div>

        <div class="mt-5">
            <x-danger-button wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                {{ __('Delete Account') }}
            </x-danger-button>
        </div>

        <!-- Delete User Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmingUserDeletion">
            <x-slot name="title">
                {{ __('Delete Account') }}
            </x-slot>

            <x-slot name="content">
                @if (auth()->user()->isSocialite())
                    {{ __('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted.') }}
                @else
                    {{ __('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}

                    <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                        <x-input type="password" class="mt-1 block w-3/4"
                                    autocomplete="current-password"
                                    placeholder="{{ __('Password') }}"
                                    x-ref="password"
                                    wire:model="password"
                                    wire:keydown.enter="deleteUser" />

                        <x-input-error for="password" class="mt-2" />
                    </div>
                @endif
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>
                @if (auth()->user()->isSocialite())
                    <x-danger-button id="deleteUser" class="ms-3" wire:loading.attr="disabled">
                        {{ __('Delete Account') }}
                    </x-danger-button>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            function triggerEscKeyPress() {
                                const event = new KeyboardEvent('keydown', {
                                    key: 'Escape',
                                    code: 'Escape',
                                    which: 27,
                                    keyCode: 27,
                                    bubbles: true,
                                    cancelable: true
                                });

                                document.dispatchEvent(event);
                            }
                            var deleteButton = document.getElementById('deleteUser');
                            if (deleteButton) {
                                deleteButton.addEventListener('click', function(event) {
                                    event.preventDefault();
                                    triggerEscKeyPress();
                                    fetch('{{ route("deleteUserSocialite") }}', {
                                        method: 'POST',
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        },
                                    })
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Network response was not ok');
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        if (data.message === 'USER_DELETED') {
                                            window.location.replace('{{ route("home") }}');
                                        }
                                    })
                                });
                            }
                        });
                    </script>
                @else
                    <x-danger-button class="ms-3" wire:click="deleteUser" wire:loading.attr="disabled">
                        {{ __('Delete Account') }}
                    </x-danger-button>
                @endif
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>
