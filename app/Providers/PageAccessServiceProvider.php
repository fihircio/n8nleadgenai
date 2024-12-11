<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Post;

/**
 * PageAccessServiceProvider
 *
 * This service provider is responsible for managing page access control
 * within the application. It provides methods to determine the current page,
 * check access permissions, and retrieve error messages for denied access.
 */
class PageAccessServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('pageAccess', function ($app) {
            return new class {
                public function getCurrentPage()
                {
                    $uriPage = empty(config('zeus-sky.uri.page')) ? '' : config('zeus-sky.uri.page') . '/';
                    $prefix = config('zeus-sky.prefix') . '/';

                    return str_replace($prefix . $uriPage, '', request()->path());
                }

                public function checkAccessDenied($user, $page)
                {
                    // Find the page in the database by slug
                    $post = Post::where('slug', $page)->first();

                    // If the page doesn't exist in our database, allow access
                    // This covers cases where the route is managed by other plugins or is not a restricted page
                    if (!$post) {
                        return false;
                    }

                    $pagePermission = $post->permissions->first();

                    // If the page has no associated permission or the permission is "public", allow access
                    if (!$pagePermission || $pagePermission->name === 'public') {
                        return false;
                    }

                    // For authenticated users, check if they have the required permission
                    if ($user) {
                        return $user->cannot($pagePermission->name);
                    }

                    // For unauthenticated users, deny access to non-public pages
                    return true;
                }

                public function getErrorMessage()
                {
                    return __('Forbidden: This page is restricted');
                }
            };
        });
    }

    public function boot()
    {
        //
    }
}
