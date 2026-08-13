<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Peserta;

class PesertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //insert into
        //Peserta::create([
        //    'name' => 'Candra Ayu',
        //    'email' => 'candra@gmail.com',
        //    'age' => '18',
        //    'address' => 'Tebet',
       // ]);
       Peserta::factory(50)->create();
    }
}
