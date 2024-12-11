<?php

namespace App\Filament;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as FilamentLogoutResponse;
use Illuminate\Http\RedirectResponse;

class CustomLogoutResponse implements FilamentLogoutResponse
{
    public function toResponse($request): RedirectResponse
    {
        return redirect()->route('home');
    }
}
