<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;use App\Models\Novel;
use App\Models\EntityAlias;
use App\Models\EntityTranslation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EntityController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['permission:entities.index'], only: ['index']),
            new Middleware(['permission:entities.create'], only: ['create', 'store']),
            new Middleware(['permission:entities.edit'], only: ['edit', 'update']),
            new Middleware(['permission:entities.delete'], only: ['destroy']),
        ];
    }

    // 1. Tampilkan Form Tambah (Mengunci $novel dari URI)
    public function create(Novel $novel)
    {
        return view('account.entities.create', compact('novel'));
    }

    // 2. Aksi Simpan Data Baru (Store)
    public function store(Request $request, Novel $novel)
    {
        // Validasi novel_id dicopot karena sudah pasti aman terikat dari URI
        $request->validate([
            'type'            => 'required|in:character,item,place',
            'gender'          => 'nullable|in:male,female',
            'display_aliases' => 'required|string',
            'desc_id'         => 'required|string',
            'desc_en'         => 'required|string',
        ]);

        $user = auth()->user();
        $status = ($user->hasRole('admin') || $user->hasRole('administrator')) ? 'approved' : 'pending';
        
        // Memisahkan kata kunci koma menjadi array murni
        $aliasesArray = array_filter(array_map('trim', explode(',', $request->display_aliases)));
        $mainName = $aliasesArray[0] ?? $request->display_aliases;

        DB::beginTransaction();
        try {
            // 1. Simpan induk Entity (ID otomatis dibuat oleh trait HasUlids di background)
            $entity = Entity::create([
                'novel_id'        => $novel->id,
                'main_name'       => $mainName,
                'type'            => $request->type,
                'gender'          => $request->type === 'character' ? $request->gender : null,
                'image_url'       => $request->image_url,
                'display_aliases' => $request->display_aliases,
                'status'          => $status,
                'user_id'         => $user->id
            ]);

            // 2. Simpan ke tabel anak menggunakan metode relasi (ID anak & entity_id otomatis terisi!)
            foreach ($aliasesArray as $aliasName) {
                $entity->aliases()->create([
                    'alias_name' => $aliasName
                ]);
            }

            // 3. Simpan terjemahan menggunakan metode relasi (id & entity_id otomatis terisi!)
            $entity->translations()->create([
                'locale'      => 'id',
                'description' => $request->desc_id
            ]);

            $entity->translations()->create([
                'locale'      => 'en',
                'description' => $request->desc_en
            ]);

            DB::commit();
            Cache::forget("novel_keywords_{$novel->slug}_id");
            Cache::forget("novel_keywords_{$novel->slug}_en");
            return redirect()->route('account.novels.show', $novel->id)->with('success', 'Entitas berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // Tampilkan Form Ubah (Edit)
    public function edit(Entity $entity)
    {
        $novel = $entity->novel; 
        $descId = $entity->translations()->where('locale', 'id')->value('description') ?? '';
        $descEn = $entity->translations()->where('locale', 'en')->value('description') ?? '';

        return view('account.entities.edit', compact('entity', 'novel', 'descId', 'descEn'));
    }

    // Aksi Perbarui Data (Update)
    public function update(Request $request, Entity $entity)
    {
        $request->validate([
            'type'            => 'required|in:character,item,place',
            'gender'          => 'nullable|in:male,female',
            'display_aliases' => 'required|string',
            'desc_id'         => 'required|string',
            'desc_en'         => 'required|string',
        ]);

        $user = auth()->user();
        $status = ($user->hasRole('admin') || $user->hasRole('administrator')) ? $entity->status : 'pending';

        $aliasesArray = array_filter(array_map('trim', explode(',', $request->display_aliases)));
        $mainName = $aliasesArray[0] ?? $request->display_aliases;

        DB::beginTransaction();
        try {
            // 1. Update data dasar di tabel entities
            $entity->update([
                'main_name'       => $mainName,
                'type'            => $request->type,
                'gender'          => $request->type === 'character' ? $request->gender : null,
                'display_aliases' => $request->display_aliases,
                'image_url'       => $request->image_url,
                'status'          => $status,
            ]);

            // 2. Re-Sync tabel entity_aliases (Hapus alias lama, tulis ulang yang baru)
            $entity->aliases()->delete();
            foreach ($aliasesArray as $aliasName) {
                $entity->aliases()->create([
                    'alias_name' => $aliasName
                ]);
            }

            // 3. Update atau Tulis Baru tabel entity_translations (ID & EN)
            $entity->translations()->updateOrCreate(
                ['locale' => 'id'],
                ['description' => $request->desc_id]
            );

            $entity->translations()->updateOrCreate(
                ['locale' => 'en'],
                ['description' => $request->desc_en]
            );

            DB::commit();
            
            if ($entity->novel) {
                Cache::forget("novel_keywords_{$entity->novel->slug}_id");
                Cache::forget("novel_keywords_{$entity->novel->slug}_en");
            }
            
            // Mental kembali ke halaman detail novel asalnya
            return redirect()
                ->route('account.novels.show', $entity->novel_id)
                ->with('success', 'Entitas berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui entitas: ' . $e->getMessage());
        }
    }

    public function destroy(Entity $entity)
    {
        $novelSlug = $entity->novel?->slug;
        $entity->delete();

        // 🔥 HAPUS CACHE SETELAH DATA DIHAPUS
        if ($novelSlug) {
            Cache::forget("novel_keywords_{$novelSlug}_id");
            Cache::forget("novel_keywords_{$novelSlug}_en");
        }

        return redirect()->back()->with('success', 'Entity deleted successfully');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if ($ids) {
            // Ambil daftar slug novel unik yang terpengaruh sebelum datanya dihapus massal
            $slugs = Novel::whereHas('entities', function($q) use ($ids) {
                $q->whereIn('id', $ids);
            })->pluck('slug')->unique();

            Entity::whereIn('id', $ids)->delete();

            // 🔥 HAPUS CACHE MASSAL UNTUK SETIAP NOVEL YANG TERDAMPAK
            foreach ($slugs as $slug) {
                Cache::forget("novel_keywords_{$slug}_id");
                Cache::forget("novel_keywords_{$slug}_en");
            }

            return response()->json(['status' => 'success', 'message' => 'Entity deleted successfully!']);
        }
        return response()->json(['status' => 'error', 'message' => 'Please select data first!']);
    }
}
