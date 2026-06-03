<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware(['permission:roles.index'], only: ['index']),
            new Middleware(['permission:roles.create'], only: ['create', 'store']),
            new Middleware(['permission:roles.edit'], only: ['edit', 'update']),
            new Middleware(['permission:roles.delete'], only: ['destroy']),
        ];
    }
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::query();

            return DataTables::of($data)
                    ->addColumn('checkbox', function ($row) {
                        return '<input type="checkbox" value="' . $row->id . '" class="role-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">';
                    })
                    ->addColumn('action', function ($row) {
                        return view('account.roles.action')->with("role", $row);
                    })
                    // Tambahkan 'checkbox' ke dalam rawColumns agar HTML-nya terender
                    ->rawColumns(['checkbox', 'action'])
                    ->make(true);
        }

        return view('account.roles.index');
    }
    
    public function create()
    {
        // Ambil semua permission dan urutkan berdasarkan name
        $data = Permission::orderBy('name')->get();

        // Group berdasarkan prefix sebelum titik pertama
        $permissions = $data->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        //return view
        return view('account.roles.create', compact('permissions'));
    }
    
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name'          => 'required|unique:roles,name',
            'permissions'   => 'array|nullable',
        ]);

        // insert role
        $role = Role::create([
            'name'          => $request->name,
            'guard_name'    => 'web',
        ]);

        //sync permissions
        $role->givePermissionTo($request->permissions);

        // Redirect dengan pesan sukses
        return redirect()->route('account.roles.index')->with('success', 'Role created successfully.');
    }
    
    public function edit($id)
    {
        //get role
        $role = Role::findOrFail($id);

        // Ambil semua permission, lalu kelompokkan berdasarkan prefix sebelum titik
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        // Ambil permission yang dimiliki role (berdasarkan name)
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        //return view
        return view('account.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        //get role
        $role = Role::findOrFail($id);

        $request->validate([
            'name'          => 'required|unique:roles,name,' . $id,
            'permissions'   => 'array|nullable',
        ]);

        //update
        $role->update(['name' => $request->name]);

        //sync
        $role->syncPermissions($request->permissions);

        //redirect
        return redirect()->route('account.roles.index')->with('success', 'Role updated successfully.');
    }
    
    public function destroy($id)
    {
        //get role
        $role = Role::findOrFail($id);

        //delete
        $role->delete();

        //redirect
        return redirect()->route('account.roles.index')->with('success', 'Role deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if ($ids) {
            Role::whereIn('id', $ids)->delete();
            return response()->json(['status' => 'success', 'message' => 'Role deleted successfully!']);
        }
        return response()->json(['status' => 'error', 'message' => 'Please select data first!']);
    }
}
