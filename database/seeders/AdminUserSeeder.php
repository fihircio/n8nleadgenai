<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use LaraZeus\Sky\Models\Navigation;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create navigation
        Navigation::firstOrCreate(
            [
                'handle' => 'main'
            ],
            [
                'name' => 'main',
                'items' => [
                    Str::uuid()->toString() => [
                        'label' => 'Features',
                        'type' => 'external-link',
                        'data' => [
                            'url' => '/#features',
                            'target' => null
                        ],
                        'children' => []
                    ],
                    Str::uuid()->toString() => [
                        'label' => 'Pricing',
                        'type' => 'external-link',
                        'data' => [
                            'url' => '/#pricing',
                            'target' => null
                        ],
                        'children' => []
                    ],
                    Str::uuid()->toString() => [
                        'label' => 'Contact',
                        'type' => 'external-link',
                        'data' => [
                            'url' => '/contact',
                            'target' => null
                        ],
                        'children' => []
                    ],
                ],
            ]
        );

        // permissions array
        $permissions = [
            'public',
            'access admin panel',
            'access basic features',
            'access premium features',
            'access first tier features',
            'access second tier features',
            'access third tier features'
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->syncPermissions(['access basic features']);

        $premiumFirstTier = Role::firstOrCreate(['name' => 'premium-first-tier']);
        $premiumFirstTier->syncPermissions(['access basic features', 'access premium features', 'access first tier features']);

        $premiumSecondTier = Role::firstOrCreate(['name' => 'premium-second-tier']);
        $premiumSecondTier->syncPermissions(['access basic features', 'access premium features', 'access first tier features', 'access second tier features']);

        $premiumThirdTier = Role::firstOrCreate(['name' => 'premium-third-tier']);
        $premiumThirdTier->syncPermissions(['access basic features', 'access premium features', 'access first tier features', 'access second tier features', 'access third tier features']);

        // Create Admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin@admin.com')
            ]
        );
        
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        if (!$admin->ownedTeams()->exists()) {
            $admin->ownedTeams()->save(Team::forceCreate([
                'user_id' => $admin->id,
                'name' => "Admin's Team",
                'personal_team' => true
            ]));
        }

        // Give admin coins if not already given
        if ($admin->balance === 0) {
            $admin->deposit(100, ['reason' => 'Initial seed coins']);
        }

        // Create User user if not exists
        $user = User::firstOrCreate(
            ['email' => 'user@user.com'],
            [
                'name' => 'User',
                'password' => Hash::make('user@user.com')
            ]
        );

        if (!$user->hasRole('user')) {
            $user->assignRole('user');
        }

        if (!$user->ownedTeams()->exists()) {
            $user->ownedTeams()->save(Team::forceCreate([
                'user_id' => $user->id,
                'name' => "User's Team",
                'personal_team' => true
            ]));
        }

        // Give user coins if not already given
        if ($user->balance === 0) {
            $user->deposit(100, ['reason' => 'Initial seed coins']);
        }
    }
}
