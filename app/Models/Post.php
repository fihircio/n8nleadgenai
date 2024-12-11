<?php

namespace App\Models;

use LaraZeus\Sky\Models\Post as SkyPost;
use Spatie\Permission\Traits\HasPermissions;

class Post extends SkyPost
{
    use HasPermissions;

    public function permissions()
    {
        return $this->morphToMany(
            \Spatie\Permission\Models\Permission::class,
            'model',
            'model_has_permissions',
            'model_id',
            'permission_id'
        );
    }
}
