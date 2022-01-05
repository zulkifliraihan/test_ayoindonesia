<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Zulkifli Raihan',
            'email' => 'admin@demo.com',
            'password' => Hash::make('123123')
        ]);
    }
}
