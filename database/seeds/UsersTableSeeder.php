<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'Ajib Roziq',
            'username' => 'arozqq',
            'password' => bcrypt('password'),
            'email' => 'ajibroziq21@gmail.com',
        ]);
    }
}
