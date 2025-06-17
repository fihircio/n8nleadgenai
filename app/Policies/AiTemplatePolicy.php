<?php

namespace App\Policies;

use App\Models\AiTemplate;
use App\Models\User;

class AiTemplatePolicy
{
    public function view(User $user, AiTemplate $template): bool
    {
        return $user->id === $template->user_id;
    }

    public function update(User $user, AiTemplate $template): bool
    {
        return $user->id === $template->user_id;
    }

    public function delete(User $user, AiTemplate $template): bool
    {
        return $user->id === $template->user_id;
    }
} 