<?php

namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
 
class PermissionsDemoSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
 
        $createPermissionOnUser = Permission::create(['name' => 'Create a new user']);
        $updatePermissionOnUser = Permission::create(['name' => 'Update user']);
        $readPermissionOnUser = Permission::create(['name' => 'list users']);
        $deletePermissionOnUser = Permission::create(['name' => 'Delete user']);

        $createPermissionOnTask = Permission::create(['name' => 'Create a new task']);
        $updatePermissionOnTask = Permission::create(['name' => 'Update task']);
        $readPermissionOnTask = Permission::create(['name' => 'list tasks']);
        $deletePermissionOnTask = Permission::create(['name' => 'Delete task']);
 
        
        $super = Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'admin']);
        $emp = Role::create(['name' => 'employee']);
        $manager = Role::create(['name' => 'manager']);

        $admin->givePermissionTo([
            $createPermissionOnUser,
            $updatePermissionOnUser,
            $readPermissionOnUser,
            $deletePermissionOnUser,
            $createPermissionOnTask ,
            $updatePermissionOnTask ,
            $readPermissionOnTask ,
            $deletePermissionOnTask 
        ]);

        $emp->givePermissionTo([
            $updatePermissionOnTask ,
            $readPermissionOnTask ,
        ]);

        $manager->givePermissionTo([
            $createPermissionOnUser,
            $readPermissionOnUser,
            $createPermissionOnTask ,
            $updatePermissionOnTask ,
            $readPermissionOnTask ,
            $deletePermissionOnTask 
        ]);

        $superAdminUser = User::factory()->create([
            'name' => 'Maram',
            'email' => 'maram@example.com',
            'password' =>  bcrypt("123"),
            'mobile' => '1053453643'
            
        ]);

        $superAdminUser->assignRole($super);
        $superAdminUser->givePermissionTo([
            $createPermissionOnUser,
            $updatePermissionOnUser,
            $readPermissionOnUser,
            $deletePermissionOnUser,
            $createPermissionOnTask ,
            $updatePermissionOnTask ,
            $readPermissionOnTask ,
            $deletePermissionOnTask 
        ]);


        
 
        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' =>  bcrypt("123"),
            'mobile' => '1023453643'
            
        ]);

        $adminUser->assignRole($admin);
        $adminUser->givePermissionTo([
            $createPermissionOnUser,
            $updatePermissionOnUser,
            $readPermissionOnUser,
            $deletePermissionOnUser,
            $createPermissionOnTask ,
            $updatePermissionOnTask ,
            $readPermissionOnTask ,
            $deletePermissionOnTask 
        ]);


        $managerUser = User::factory()->create([
            'name' => 'manager',
            'email' => 'manager@example.com',
            'password' =>  bcrypt("123"),
            'mobile' => '1023453642'
        ]);

        $managerUser->assignRole($manager);
        $managerUser->givePermissionTo([
            $createPermissionOnUser,
            $readPermissionOnUser,
            $createPermissionOnTask ,
            $updatePermissionOnTask ,
            $readPermissionOnTask ,
            $deletePermissionOnTask 
        ]);


        $empUser = User::factory()->create([
            'name' => 'employee',
            'email' => 'employee@example.com',
            'password' =>  bcrypt("123"),
            'mobile' => '1023453641'
        ]);

        $empUser->assignRole($emp);
        $empUser->givePermissionTo([
            $updatePermissionOnTask ,
            $readPermissionOnTask ,
        ]);
    }
}