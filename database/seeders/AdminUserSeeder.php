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
        Navigation::updateOrCreate(
            ['handle' => 'main'],
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
                ]
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
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo('access basic features');

        $userRole = Role::create(['name' => 'premium-first-tier']);
        $userRole->givePermissionTo(['access basic features', 'access premium features', 'access first tier features']);

        $userRole = Role::create(['name' => 'premium-second-tier']);
        $userRole->givePermissionTo(['access basic features', 'access premium features', 'access first tier features', 'access second tier features']);

        $userRole = Role::create(['name' => 'premium-third-tier']);
        $userRole->givePermissionTo(['access basic features', 'access premium features', 'access first tier features', 'access second tier features', 'access third tier features']);

        // Create Admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin@admin.com')
        ]);
        $admin->assignRole('admin');
        $admin->ownedTeams()->save(Team::forceCreate([
            'user_id' => $admin->id,
            'name' => 'Admin\'s Team',
            'personal_team' => true,
        ]));

        // Create User user
        $user = User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => Hash::make('user@user.com')
        ]);
        $user->assignRole('user');
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => 'User\'s Team',
            'personal_team' => true,
        ]));
    }
}
