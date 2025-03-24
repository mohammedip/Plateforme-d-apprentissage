<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(5)->create();

        $adminRole = Role::factory()->create(['name' => 'admin']);
        $mentorRole = Role::factory()->create(['name' => 'mentor']);
        $etudiantRole = Role::factory()->create(['name' => 'etudiant']);

        $adminPermission = Permission::factory()->create(['name' => 'admin permission']);
        $mentorPermission = Permission::factory()->create(['name' => 'mentor permission']);
        $etudiantPermission = Permission::factory()->create(['name' => 'etudiant permission']);

        $adminRole->givePermissionTo($adminPermission);
        $mentorRole->givePermissionTo($mentorPermission);
        $etudiantRole->givePermissionTo($etudiantPermission);

       
    }
}
