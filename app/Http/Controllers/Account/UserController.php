<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Utama\Major;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['permission:users.index'], only: ['index']),
            new Middleware(['permission:users.create'], only: ['create', 'store']),
            new Middleware(['permission:users.edit'], only: ['edit', 'update']),
            new Middleware(['permission:users.delete'], only: ['destroy']),
        ];
    }
    

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // PERBAIKAN 1: Tambahkan with('major') dan with('roles') jika menggunakan Spatie
            $data = User::with(['roles'])->select('users.*'); 
            
            return DataTables::of($data)
                    ->addColumn('checkbox', function ($row) {
                        return '<input type="checkbox" value="' . $row->id . '" class="user-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">';
                    })
                    ->addColumn('roles', function ($row) {
                        return view('account.users.role')->with("user", $row);
                    })
                    ->addColumn('action', function ($row) {
                        return view('account.users.action')->with("user", $row);
                    })
                    ->filterColumn('major', function($query, $keyword) {
                        $query->whereHas('major', function($q) use ($keyword) {
                            $q->where('major', 'like', "%{$keyword}%");
                        });
                    })
                    ->orderColumn('major', function ($query, $order) {
                        $query->leftJoin('majors as m1', 'users.major_id', '=', 'm1.id')
                            ->orderBy('m1.major', $order);
                    })
                    ->rawColumns(['checkbox', 'action', 'roles']) 
                    ->make(true);
        }

        return view('account.users.index');
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('account.users.create', compact('roles'));
    }
    
    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6|confirmed',
            'roles'     => 'array|nullable',
        ]);

        // create user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // assign roles
        $user->syncRoles($request->roles);

        // redirect with success message
        return redirect()->route('account.users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {

        $roles = Role::orderBy('name')->get();

        //get user
        $user = User::findOrFail($id);

        //return view
        return view('account.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // validate request
        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:6|confirmed',
            'roles'     => 'array|nullable',
        ]);

        // update
        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
        ];

        // check if password is filled
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // update
        $user->update($data);

        // sync roles
        $user->syncRoles($request->roles);

        // redirect with success message
        return redirect()->route('account.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        //delete user
        $user->delete();

        return redirect()->route('account.users.index')->with('success', 'User deleted successfully');
    }

    // Fungsi baru untuk Bulk Delete
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if ($ids) {
            User::whereIn('id', $ids)->delete();
            return response()->json(['status' => 'success', 'message' => 'User deleted successfully!']);
        }
        return response()->json(['status' => 'error', 'message' => 'Please select data first!']);
    }
}
