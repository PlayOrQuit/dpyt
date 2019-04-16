<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'ndphuong0710@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123456'),
        ]);
    }
}
