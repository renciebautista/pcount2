<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\User;
use App\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
		DB::table('roles')->truncate();

		// DB::statement("INSERT INTO roles (id, name, display_name, description, created_at, updated_at) VALUES
		// 	(1, 'ADMIN', 'ADMINSTRATOR', '', date('Y-m-d H:i:s'), date('Y-m-d H:i:s')),
		// 	(2, 'MANAGER', 'MANAGER', '', date('Y-m-d H:i:s'), date('Y-m-d H:i:s')),
		// 	(3, 'FIELD', 'FIELD', '', date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));");
		// DB::statement('SET FOREIGN_KEY_CHECKS=1;');


		$admin = new Role();
		$admin->name         = 'admin';
		$admin->display_name = 'User Administrator'; // optional
		$admin->description  = 'User is allowed to manage and edit other users'; // optional
		$admin->save();

		$field = new Role();
		$field->name         = 'field';
		$field->display_name = 'Field Personel'; // optional
		$field->description  = 'User is the auditor on site.'; // optional
		$field->save();

    }
}
