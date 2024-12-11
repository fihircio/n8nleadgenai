<?php

namespace App\Listeners;

use App\Models\Team;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class UserRegistered extends Component
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event)
    {
        $user = $event->user;
        $user->name = $user->email;
        $user->billing_provider = Session::get('billing_provider') ?? config('saashovel.BILLING_PROVIDER');
        $user->save();
        $user->assignRole('user');
        $this->createTeam($user);
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam($user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}
