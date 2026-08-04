<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CampusSeeder::class,
            UserRoleSeeder::class,
            KampusSeeder::class,
            FaqSeeder::class,
            SyaratKetentuanSeeder::class,
            KebijakanPrivasiSeeder::class,
            PanduanDelegasiSeeder::class,
            PenampilSeeder::class,
            SponsorSeeder::class,
            KolaboratorSeeder::class,
        ]);
    }
}