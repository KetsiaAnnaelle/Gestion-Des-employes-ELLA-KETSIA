<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\Formation::factory(10)->create();
         \App\Models\Cours::factory(10)->create();
         \App\Models\InscriptionEtudiant::factory(20)->create();
         \App\Models\Abscence::factory(10)->create();
        \App\Models\Stage::factory(10)->create();
        \App\Models\Conduite::factory(10)->create();
         \App\Models\Enseignant::factory(10)->create();
         \App\Models\NewConduite::factory(10)->create();
          \App\Models\Facture::factory(10)->create();
           \App\Models\Rembourssement::factory(10)->create();
           \App\Models\Paiement::factory(10)->create();
           \App\Models\PaiementFacture::factory(10)->create();
           \App\Models\Personnel::factory(10)->create();
           \App\Models\PerformancePerso::factory(10)->create();
         \App\Models\CongePerso::factory(10)->create();
         \App\Models\CarrierePerso::factory(10)->create();
          \App\Models\FicheTravailPerso::factory(10)->create();
           \App\Models\PaiementPerso::factory(10)->create();
           \App\Models\CongeEns::factory(10)->create();
         \App\Models\CarriereEns::factory(10)->create();
          \App\Models\FicheCoursEns::factory(10)->create();
           \App\Models\PaiementEns::factory(10)->create();
           \App\Models\PerformanceEns::factory(10)->create();
           \App\Models\Note::factory(10)->create();
           \App\Models\NewPaiementPerso::factory(10)->create();
           \App\Models\NewPaiementEns::factory(10)->create();
           \App\Models\NewPerfEns::factory(10)->create();
           \App\Models\NewPerfPerso::factory(10)->create();
           \App\Models\Note::factory(10)->create();
           \App\Models\Note::factory(10)->create();
           \App\Models\CategorieDepense::factory(10)->create();
        \App\Models\DepenseSortie::factory(10)->create();
        \App\Models\DepenseEntree::factory(10)->create();

    }
}
