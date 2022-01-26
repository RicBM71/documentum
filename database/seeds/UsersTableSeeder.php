<?php

use App\User;
use App\Avatar;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::truncate();
        Role::truncate();
        User::truncate();

        $rootRole = Role::create(['name'=>'Root']);
        $adminRole = Role::create(['name'=>'Admin']);
        $userRole = Role::create(['name'=>'Factura']);
        $userRole = Role::create(['name'=>'Documenta']);



        $verUser = Permission::create(['name'=>'Borra documentos']);
        Permission::create(['name'=>'Edita Clientes']);

        $user = new User;

        $user->name = "Ric";
		$user->email = "info@sanaval.com";
		$user->username = "ricardo.bm";
		$user->password = Hash::make('123');
		$user->save();
       $user->assignRole($rootRole);


        // for ($i=2; $i <= 10  ; $i++) {

        //     $user = new User;

        //     $user->name = "User ".$i;
        //     $user->email = "user".$i."@rr.es";
        //     $user->username = "User".$i;
        //     $user->empresa_id = 1;
        //     $user->password = Hash::make('123');
        //     $user->save();
        //     $user->assignRole($userRole);
        // }

    }
}
