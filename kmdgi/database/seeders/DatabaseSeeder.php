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
            EdisiKmdgiSeeder::class,
            KampusSeeder::class,
            FaqSeeder::class,
            SyaratKetentuanSeeder::class,
            KebijakanPrivasiSeeder::class,
            PanduanDelegasiSeeder::class,
            SponsorSeeder::class,
            KolaboratorSeeder::class,
            EventKmdgiSeeder::class,
            PenampilSeeder::class,
            DokumentasiSeeder::class,
            AboutKmdgiSeeder::class,
            SejarahKmdgiSeeder::class,
            HeaderPublicSeeder::class,
            DeskripsiKaryaSeeder::class,
            JuknisLombaSeeder::class,
            PesertaLombaSeeder::class,
            
            
        ]);
    }
}
