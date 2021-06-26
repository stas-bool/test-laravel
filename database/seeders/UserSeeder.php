<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    protected Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAdmin();
        $this->createUser();
    }

    private function createAdmin():void
    {
        $admin = new User();
        $admin->name = "admin@example.net";
        $admin->email = "stas@biche-ool.ru";
        $admin->email_verified_at = $this->faker->unixTime;
        $admin->password = Hash::make("123");
        $admin->api_token = Str::random(60);
        $admin->created_at = $this->faker->unixTime;
        $admin->updated_at = $this->faker->unixTime;
        $admin->assignRole('admin');
        $admin->save();
    }

    private function createUser(): void
    {
        $user = new User();
        $user->name = "Test User";
        $user->email = 'user@example.net';
        $user->email_verified_at = $this->faker->unixTime;
        $user->password = Hash::make("123");
        $user->api_token = Str::random(60);
        $user->created_at = $this->faker->unixTime;
        $user->updated_at = $this->faker->unixTime;
        $user->assignRole('user');
        $user->save();
    }
}
