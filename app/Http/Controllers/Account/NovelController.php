<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Novel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class NovelController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['permission:novels.index'], only: ['index']),
            new Middleware(['permission:novels.create'], only: ['create', 'store']),
            new Middleware(['permission:novels.edit'], only: ['edit', 'update']),
            new Middleware(['permission:novels.delete'], only: ['destroy']),
        ];
    }
    

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Novel::query();
            
            return DataTables::of($data)
                    ->addColumn('checkbox', function ($row) {
                        return '<input type="checkbox" value="' . $row->id . '" class="novel-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">';
                    })
                    ->addColumn('type', function ($row) {
                        $typeColors = [
                            'manga' => 'bg-blue-100 text-blue-800',
                            'manhwa' => 'bg-green-100 text-green-800',
                            'manhua' => 'bg-yellow-100 text-yellow-800',
                            'other' => 'bg-gray-100 text-gray-800',
                        ];
                        $colorClass = $typeColors[$row->type] ?? 'bg-gray-100 text-gray-800';
                        return '<span class="px-2 py-1 rounded-full text-xs font-semibold ' . $colorClass . '">' . ucfirst($row->type) . '</span>';
                    })
                    ->addColumn('status', function ($row) {
                        $statusColors = [
                            'approved' => 'bg-green-100 text-green-800',
                            'pending'  => 'bg-yellow-100 text-yellow-800',
                            'rejected' => 'bg-red-100 text-red-800',
                        ];
                        $colorClass = $statusColors[$row->status] ?? 'bg-gray-100 text-gray-800';
                        return '<span class="px-2 py-1 rounded-full text-xs font-semibold ' . $colorClass . '">' . ucfirst($row->status) . '</span>';
                    })
                    ->addColumn('author', function ($row) {
                        return $row->user ? $row->user->name : 'Unknown';
                    })
                    ->addColumn('action', function ($row) {
                        return view('account.novels.action')->with("novel", $row);
                    })
                    ->rawColumns(['checkbox', 'type', 'status', 'author', 'action']) 
                    ->make(true);
        }

        return view('account.novels.index');
    }

    public function show(Request $request, Novel $novel)
    {
        if ($request->ajax()) {
            $query = Entity::where('novel_id', $novel->id)
                ->select(['id', 'main_name', 'type', 'gender', 'status']);

            return DataTables::of($query)
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" value="' . $row->id . '" class="entity-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">';
                })
                ->editColumn('type', function ($row) {
                    // Formatting Type Badge dengan Tailwind v4
                    $colors = [
                        'character' => 'bg-slate-950 text-white border-slate-900',
                        'item'      => 'bg-sky-50 text-sky-700 border-sky-100',
                        'place'     => 'bg-cyan-50 text-cyan-700 border-cyan-100'
                    ];
                    $class = $colors[$row->type] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                    return '<span class="text-xs font-semibold px-2 py-0.5 rounded border ' . $class . '">' . ucfirst($row->type) . '</span>';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status === 'approved') {
                        return '<span class="bg-green-50 text-green-700 text-xs font-semibold px-2 py-0.5 rounded border border-green-100">Approved</span>';
                    }
                    return '<span class="bg-amber-50 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded border border-amber-100">Pending</span>';
                })
                ->addColumn('action', function ($row) {
                    return view('account.novels.actionShow')->with("entity", $row);
                })
                ->rawColumns(['checkbox','type', 'status', 'action'])
                ->make(true);
        }

        return view('account.novels.show', compact('novel'));
    }

    public function create()
    {
        return view('account.novels.create');
    }
    
    public function store(Request $request)
    {
        //validate request
        $request->validate([
            'title' => 'required|string|max:255|unique:novels,title',
            'type'  => 'required|in:manga,manhwa,manhua,other',
        ]);

        $user = auth()->user();
        $status = ($user->hasRole('admin') || $user->hasRole('administrator')) ? 'approved' : 'pending';
        
        //create novel
        Novel::create([
            'title'   => $request->title,
            'slug'    => Str::slug($request->title, '-'),
            'type'    => $request->type,
            'status'  => $status, 
            'user_id' => $user->id,
        ]);

        return redirect()->route('account.novels.index')
                ->with('success', $status === 'approved' 
                    ? 'Novel berhasil diterbitkan secara instan!' 
                    : 'Novel berhasil diajukan! Menunggu persetujuan Admin.'
                );
    }

    public function edit($id)
    {
        //get novel
        $novel = Novel::findOrFail($id);

        //return view
        return view('account.novels.edit', compact('novel'));
    }

    public function update(Request $request, Novel $novel)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:novels,title,' . $novel->id,
            'type'  => 'required|in:manga,manhwa,manhua,other',
        ]);

        $user = auth()->user();
        $oldSlug = $novel->slug;

        if ($user->hasRole('admin') || $user->hasRole('administrator')) {
            $status = $request->input('status', $novel->status);
        } else {
            $status = 'pending';
        }

        $novel->update([
            'title'  => $request->title,
            'slug'   => Str::slug($request->title, '-'),
            'type'   => $request->type,
            'status' => $status,
        ]);

        Cache::forget("novel_keywords_{$oldSlug}_id");
        Cache::forget("novel_keywords_{$oldSlug}_en");
        Cache::forget("novel_keywords_{$novel->slug}_id");
        Cache::forget("novel_keywords_{$novel->slug}_en");

        return redirect()->route('account.novels.index')
                            ->with('success', $status === 'pending' 
                                ? 'Novel berhasil diperbarui dan dikembalikan ke antrean pending untuk ditinjau Admin.' 
                                : 'Novel berhasil diperbarui secara instan!'
                            );
    }

    public function destroy(Novel $novel)
    {
        $slug = $novel->slug;
        $novel->delete();

        // 🔥 HAPUS CACHE SAAT NOVEL DIHAPUS
        Cache::forget("novel_keywords_{$slug}_id");
        Cache::forget("novel_keywords_{$slug}_en");

        return redirect()->route('account.novels.index')->with('success', 'Novel deleted successfully');
    }

    // Fungsi baru untuk Bulk Delete
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if ($ids) {
            // Ambil daftar slug novel sebelum dihapus secara bulk
            $slugs = Novel::whereIn('id', $ids)->pluck('slug');

            Novel::whereIn('id', $ids)->delete();

            // 🔥 HAPUS CACHE UNTUK SEMUA NOVEL YANG DIHAPUS MASSAL
            foreach ($slugs as $slug) {
                Cache::forget("novel_keywords_{$slug}_id");
                Cache::forget("novel_keywords_{$slug}_en");
            }

            return response()->json(['status' => 'success', 'message' => 'Novel deleted successfully!']);
        }
        return response()->json(['status' => 'error', 'message' => 'Please select data first!']);
    }
}
