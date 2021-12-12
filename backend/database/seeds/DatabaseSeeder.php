<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        Artisan::call('passport:client --personal --name social-constant');

        \App\Models\User::query()->create([
            'id' => 100,
            'invite_code' => 'R1872354',
            'name' => 'Marco Oberbrunner',
            'device' => '15688888888',
            'headimgurl' => 'https://lorempixel.com/640/480/?36495',
        ]);

        \App\Models\Category::query()->insert([
            ["title"=>"Stories"],
            ["title"=>"News"],
            ["title"=>"World"],
            ["title"=>"Business"],
            ["title"=>"Health"],
        ]);

    }
}
