<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $now = now();

        User::factory()->create([
            // 'name' => 'Test User',
            // 'email' => 'test@example.com',
            'nomor_wa' => '081234567890',
            'password' => bcrypt('password123'),
            'role'     => 'administrator',
        ]);
 

        $this->call([
            MstMesinSeeder::class,
            MstProdukSeeder::class,
            CostingSeeder::class,
        ]);
        

    }
}
