<div x-data="subscriptionManager">
    <!-- Modal structure -->
    <x-custom-modal id="manage-subscription" max-width="lg" />

    <!-- Buttons to open the modal -->
    <div id="buttonsManageSub" class="flex flex-col sm:flex-row gap-3">
        <button id="restorePlan"
            @click="openModal('restore')"
            class="{{ when($user->subscriptionsNP()->latest()->first()->status === 'active', 'hidden') }} py-3 px-4 inline-flex justify-center items-center text-sm font-semibold rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 disabled:opacity-50 disabled:pointer-events-none">
            {{ __('Restore Plan') }}
        </button>
        <button id="changePlan"
            @click="openModal('change')"
            class="{{ when($user->subscriptionsNP()->latest()->first()->status === 'cancelled', 'hidden') }} py-3 px-4 inline-flex justify-center items-center text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
            {{ __('Change Plan') }}
        </button>
        <button id="cancelPlan"
            @click="openModal('cancel')"
            class="{{ when($user->subscriptionsNP()->latest()->first()->status === 'cancelled', 'hidden') }} py-3 px-4 inline-flex justify-center items-center text-sm font-semibold rounded-lg border border-gray-300 text-gray-700 bg-gray-50 hover:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none">
            {{ __('Cancel Plan') }}
        </button>
    </div>
</div>
