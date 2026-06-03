<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['permission:permissions.index'], only: ['index']),
            new Middleware(['permission:permissions.create'], only: ['create', 'store']),
            new Middleware(['permission:permissions.edit'], only: ['edit', 'update']),
            new Middleware(['permission:permissions.delete'], only: ['destroy']),
        ];
    }
    

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::query();
            
            return DataTables::of($data)
                    ->addColumn('checkbox', function ($row) {
                        return '<input type="checkbox" value="' . $row->id . '" class="permission-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">';
                    })
                    ->addColumn('action', function ($row) {
                        return view('account.permissions.action')->with("permission", $row);
                    })
                    // Tambahkan 'checkbox' ke dalam rawColumns agar HTML-nya terender
                    ->rawColumns(['checkbox', 'action']) 
                    ->make(true);
        }

        return view('account.permissions.index');
    }

    public function create()
    {
        return view('account.permissions.create');
    }
    
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        // Buat permission baru
        Permission::create([
            'name'          => $request->name,
            'guard_name'    => 'web',
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('account.permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit($id)
    {
        //get permission
        $permission = Permission::findOrFail($id);

        //return view
        return view('account.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        //validate request
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        //update permission
        $permission->update([
            'name' => $request->name,
        ]);

        return redirect()->route('account.permissions.index')->with('success', 'Permission updated successfully');
    }

    public function destroy(Permission $permission)
    {
        //delete permission
        $permission->delete();

        return redirect()->route('account.permissions.index')->with('success', 'Permission deleted successfully');
    }

    // Fungsi baru untuk Bulk Delete
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if ($ids) {
            Permission::whereIn('id', $ids)->delete();
            return response()->json(['status' => 'success', 'message' => 'Permission deleted successfully!']);
        }
        return response()->json(['status' => 'error', 'message' => 'Please select data first!']);
    }
}
