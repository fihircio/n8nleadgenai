<div x-data="subscriptionManager">
    <!-- Buttons to open the modal -->
    <div id="buttonsManageSub" class="flex flex-col sm:flex-row gap-3">
        <button @click="openModal" id="showModal" type="button" class="{{ when(!empty($subEndsAt), 'hidden') }} py-3 px-4 inline-flex justify-center items-center text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">{{ __('Manage your subscription') }}</button>
        <button @click="openModalForRestore" id="restorePlan" type="button" class="{{ when(empty($subEndsAt), 'hidden') }} py-3 px-4 inline-flex justify-center items-center text-sm font-semibold rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 disabled:opacity-50 disabled:pointer-events-none">{{ __('Restore Plan') }}</button>
        <button id="cancelPlan" @click="openModalForCancel" class="{{ when(!empty($subEndsAt), 'hidden') }} py-3 px-4 justify-center items-center text-sm font-semibold rounded-lg border border-gray-300 text-gray-700 bg-gray-50 hover:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none">
            {{ __('Cancel Plan') }}
        </button>
    </div>

    <!-- Modal structure using x-custom-modal -->
    <x-custom-modal id="manage-subscription" maxWidth="lg">
        <x-slot name="title">
            <span x-text="modalTitle"></span>
        </x-slot>

        <x-slot name="content">
            <p>
                <template x-if="modalContent === 'manageSub'">
                    <p>
                        @if (config('saashovel.REDIRECT_TO_CUSTOMER_PORTAL_IN_A_NEW_TAB'))
                            {{ __('A new window will open for you to log in to LemonSqueezy and manage your subscription.') }}
                        @else
                            {{ __('You will be redirected to LemonSqueezy to manage your subscription.') }}
                        @endif
                        {{ __('After you\'re done, click "Return" to return to your dashboard ') }}
                        @if (config('saashovel.REDIRECT_TO_CUSTOMER_PORTAL_IN_A_NEW_TAB'))
                            {{ __('or simply close this window ') }}
                        @endif
                        {{ __(' . Your updates will automatically apply to your account.') }}
                    </p>
                </template>

                <template x-if="modalContent === 'restorePlan'">
                    <span>
                        {{ __("Restore your plan? If so, your current billing cycle will continue and your subscription will automatically renew on ") }}<b>{{ \Carbon\Carbon::parse($user->subscription()->ends_at)->translatedFormat('j F Y') }}</b>.
                    </span>
                </template>

                <template x-if="modalContent === 'cancelPlan'">
                    <div>
                        <p class="mt-1 font-bold">{{ __("Are you sure you want to cancel your plan?") }}</p>
                        <p class="mt-1">{{ __("If so, your plan will remain active until ") }}<span class="font-bold">{{ \Carbon\Carbon::parse($user->subscription()->ends_at)->translatedFormat('j F Y') }}</span>{{ __(", then it will be canceled and you won't be charged again.") }}</p>
                        <p class="mt-1">{{ __("If you change your mind you can always restore your plan anytime before ") }}<span class="font-bold">{{ \Carbon\Carbon::parse($user->subscription()->ends_at)->translatedFormat('j F Y') }}.</p>
                    <div>
                </template>
            </p>
        </x-slot>

        <x-slot name="footer">
            <a x-text="proceedBtn" id="proceed" {!! config('saashovel.REDIRECT_TO_CUSTOMER_PORTAL_IN_A_NEW_TAB') ? '@click="proceedButtonAction(false)"' : '@click="proceedButtonAction(true)"' !!} class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                {{ __("Proceed to LemonSqueezy") }}
            </a>
            <button type="button" @click="closeModal" class="cancel inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                {{ __('Cancel') }}
            </button>
        </x-slot>
    </x-custom-modal>
</div>
