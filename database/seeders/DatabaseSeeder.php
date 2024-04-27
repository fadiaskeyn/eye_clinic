<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Satuan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Menjalankan seeder untuk model User
        User::factory()->create([
            'kode' => '123',
            'nama' => 'admin',
            'foto' => 'asdas.jpg',
            'email' => 'admin@mail.com',
            'password' => 'admin123',
            'level' => 'admin',
        ]);

        // Menjalankan seeder untuk model User
        User::factory()->create([
            'kode' => '12345',
            'nama' => 'PadiasBjir',
            'foto' => 'asdas.jpg',
            'email' => 'padias@mail.com',
            'password' => 'padias123',
            'level' => 'dokter',
        ]);

        // kategori seeder
        Kategori::factory()->create([
            'nama' => 'obat',
        ]);
        Kategori::factory()->create([
            'nama' => 'service',
        ]);

        //satuan seeder
        Satuan::factory()->create([
            'nama' => 'pcs',
        ]);
        Satuan::factory()->create([
            'nama' => 'treatment',
        ]);
        // panggil seeder class
        $this->call([

        ]);
    }
}
