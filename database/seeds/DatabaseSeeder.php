<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        Model::unguard();
        DB::table('users')->delete();

        $users = array(
            ['name' => 'admin', 'email' => 'admin@test.com', 'password' => Hash::make('123456')],
            ['name' => 'user', 'email' => 'user@test.com', 'password' => Hash::make('123456')],
        );

        foreach ($users as $user) {
            User::create($user);
        }

        Model::reguard();
    }
}
