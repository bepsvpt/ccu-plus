<?php

use App\Ccu\User\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment(['local', 'testing'])) {
            factory(User::class)->create([
                'username' => 'test',
                'password' => bcrypt('test'),
            ]);
        }

        factory(User::class, mt_rand(10, 30))->create();
    }
}
