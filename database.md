# DATABASE

## NOVELS
```bash
Schema::create('novels', function (Blueprint $table) {
    $table->ulid('id')->primary(); // Mengubah ID standar menjadi ULID
    $table->string('title');
    $table->string('slug')->unique();
    $table->string('status')->default('pending'); // pending, approved, rejected
    $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
    $table->timestamps();
});
```

## ENTITY
```bash
Schema::create('entities', function (Blueprint $table) {
    $table->ulid('id')->primary(); // ID utama menggunakan ULID
    $table->foreignUlid('novel_id')->constrained()->onDelete('cascade');
    $table->string('main_name'); // Contoh: Mok Gyeongun / Pedang Asura
    $table->string('type'); // Isi: 'character', 'item', atau 'place'
    $table->string('gender')->nullable();
    $table->string('image_url'); // URL gambar objek/karakter
    $table->string('display_aliases')->nullable();
    $table->string('status')->default('pending'); // nilai: pending, approved, rejected
    $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
    $table->timestamps();
});
```

## ENTITY ALIASES
```bash
Schema::create('entity_aliases', function (Blueprint $table) {
    $table->ulid('id')->primary(); // Tetap gunakan ULID agar seragam
    $table->foreignUlid('entity_id')->constrained()->onDelete('cascade');
    $table->string('alias_name'); // Tempat menyimpan nama alias/julukan
    $table->timestamps();
    $table->index('alias_name');
});
```

## ENTITY TRANSLATION
```bash
Schema::create('entity_translations', function (Blueprint $table) {
    $table->ulid('id')->primary(); 
    $table->string('entity_id'); 
    $table->string('locale', 5); // 'id', 'en', 'ko', dll.
    $table->text('description'); // Deskripsi sesuai bahasa
    $table->timestamps();
    $table->foreign('entity_id')->references('id')->on('entities')->onDelete('cascade');
    $table->unique(['entity_id', 'locale']);
});
```