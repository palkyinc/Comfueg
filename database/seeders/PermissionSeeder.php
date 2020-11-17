<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*/Lista de Permisos;
        Permission::create(['name' => 'antenas_index']);
        Permission::create(['name' => 'antenas_edit']);
        Permission::create(['name' => 'antenas_create']);
        
        //Lista de Roles
        $admin = Role::create(['name' => 'Admin']);

        //Asignar permiso a un rol
        $admin->givePermissionTo([
            'antenas_index',
            'antenas_edit',
            'antenas_create'
        ]);*/

        //Asignar Rol a Usuario
        $user = User::find(1);
        $user->assignRole('Admin');
    }
}
