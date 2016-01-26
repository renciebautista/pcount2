<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\User;

class AddAdminTableSeeder extends Seeder
{
    public function run()
    {
    	DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    	DB::table('users')->truncate();
    	DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        User::where('username','admin')->delete();
		User::insert(array(
			'name'     => 'admin',
			'email'    => 'admin@pcount.com',
			'username' => 'admin',
			'password' => Hash::make('password'),
		));

		$admin = User::where('username', 'admin')->first();
		$admin->roles()->attach(1);
    }
}
