<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Create a new action instance.
     */
    public function __construct(protected DeletesTeams $deletesTeams)
    {
    }

    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $this->deleteTeams($user);
            $this->deleteSubscriptionData($user);
            $user->deleteProfilePhoto();
            $user->tokens->each->delete();
            $user->delete();
        });
    }

    /**
     * Delete the teams and team associations attached to the user.
     */
    protected function deleteTeams(User $user): void
    {
        $user->teams()->detach();

        $user->ownedTeams->each(function (Team $team) {
            $this->deletesTeams->delete($team);
        });
    }

    /**
     * Delete the subscription data related to the user.
     */
    protected function deleteSubscriptionData(User $user): void
    {
        if ($user->billing_provider === 'stripe') {
            DB::table('subscription_items')->whereIn('subscription_id', function ($query) use ($user) {
                $query->select('id')
                    ->from('subscriptions')
                    ->where('user_id', $user->id);
            })->delete();
            DB::table('subscriptions')->where('user_id', $user->id)->delete();
        } elseif ($user->billing_provider === 'paddle') {
            DB::table('customers')->where('billable_id', $user->id)->delete();
            DB::table('subscription_items')->whereIn('subscription_id', function ($query) use ($user) {
                $query->select('id')
                    ->from('subscriptions')
                    ->where('billable_id', $user->id);
            })->delete();
            DB::table('subscriptions')->where('billable_id', $user->id)->delete();
            DB::table('transactions')->where('billable_id', $user->id)->delete();
        } elseif ($user->billing_provider === 'lemonsqueezy') {
            DB::table('lemon_squeezy_customers')->where('billable_id', $user->id)->delete();
            DB::table('lemon_squeezy_subscriptions')->where('billable_id', $user->id)->delete();
            DB::table('lemon_squeezy_orders')->where('billable_id', $user->id)->delete();
        } elseif ($user->billing_provider === 'nowpayments') {
            DB::table('nowpayments_customers')->where('billable_id', $user->id)->delete();
            DB::table('nowpayments_subscriptions')->where('billable_id', $user->id)->delete();
            DB::table('nowpayments_payments')->where('billable_id', $user->id)->delete();
        }
    }
}
