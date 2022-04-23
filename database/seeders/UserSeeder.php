<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([ 
            'user_type' => 'admin',
            'name' => 'admin',     
            'email' => 'admin@admin.com',      
            'password' => Hash::make('password'),
            'status' => 1
        ]);

        DB::table('users')->insert([ 
            'user_type' => 'manager',
            'name' => 'manager',     
            'email' => 'manager@manager.com',      
            'password' => Hash::make('password'),
            'status' => 1                        
        ]);

        DB::table('users')->insert([ 
            'user_type' => 'blogger',
            'name' => 'blogger',     
            'email' => 'blogger@blogger.com',      
            'password' => Hash::make('password'),
            'status' => 1  
        ]);
    }
}
