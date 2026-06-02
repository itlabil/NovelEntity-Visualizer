<?php

namespace Database\Seeders;

use App\Models\EntityTranslation;
use App\Models\Novel;
use App\Models\Entity;
use App\Models\EntityAlias;
use Illuminate\Database\Seeder;

class NovelDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $novel = Novel::create([
            'title' => 'Myst, Might, Mayhem',
            'slug' => 'myst-might-mayhem'
        ]);

        // Buat Karakter Utama dengan GENDER 'male'
        $char = Entity::create([
            'novel_id' => $novel->id,
            'main_name' => 'Mok Gyeongun',
            'type' => 'character',
            'gender' => 'male', // <-- TAMBAH GENDER
            'image_url' => 'https://static.wikia.nocookie.net/myst-might-mayhem/images/e/e2/Transformation.png',
            'display_aliases' => 'Jeong, Cheon Ma, Heavenly Demon',
        ]);

        // ISI TRANSLASI MULTI-BAHASA
        // 1. Versi Inggris
        EntityTranslation::create([
            'entity_id' => $char->id,
            'locale' => 'en',
            'description' => 'The main protagonist of Myst, Might, Mayhem. Later known as the Heavenly Demon.'
        ]);

        // 2. Versi Indonesia
        EntityTranslation::create([
            'entity_id' => $char->id,
            'locale' => 'id',
            'description' => 'Protagonis utama dari Myst, Might, Mayhem. Di masa depan dikenal sebagai Iblis Langit.'
        ]);

        // Pemicu Keyword Scan (Semua bahasa digabung di sini agar terdeteksi semua)
        EntityAlias::create(['entity_id' => $char->id, 'alias_name' => 'Jeong']);
        EntityAlias::create(['entity_id' => $char->id, 'alias_name' => 'Mok Gyeongun']);
        EntityAlias::create(['entity_id' => $char->id, 'alias_name' => 'Iblis Langit']);
        EntityAlias::create(['entity_id' => $char->id, 'alias_name' => 'Mok Gyeong-un']);
    }
}
