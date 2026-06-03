<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use Illuminate\Http\Request;
use App\Models\Novel;

class DashboardController extends Controller
{
    public function index()
    {
        // 📊 STATISTIK MASTER NOVEL
        $totalNovels = Novel::count();
        $approvedNovels = Novel::where('status', 'approved')->count();
        $pendingNovels = Novel::where('status', 'pending')->count();

        // 📊 STATISTIK ENTITIES (Karakter, Item, Tempat)
        $totalEntities = Entity::count();
        $pendingEntities = Entity::where('status', 'pending')->count();
        
        // Break down tipe entitas yang sudah disetujui
        $characterCount = Entity::where('type', 'character')->where('status', 'approved')->count();
        $itemCount = Entity::where('type', 'item')->where('status', 'approved')->count();
        $placeCount = Entity::where('type', 'place')->where('status', 'approved')->count();

        return view('account.dashboard.index', compact(
            'totalNovels',
            'approvedNovels',
            'pendingNovels',
            'totalEntities',
            'pendingEntities',
            'characterCount',
            'itemCount',
            'placeCount'
        ));
    }
}
