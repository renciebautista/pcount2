<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\User;

class AddAdminTableSeeder extends Seeder
{
    public function run()
    {
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
