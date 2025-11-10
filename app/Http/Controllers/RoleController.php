<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = DB::table('v_master_role')->orderBy('idrole', 'asc')->get();
        return view('role.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_role' => 'required|string|max:100'
        ]);

        Role::create([
            'nama_role' => $request->nama_role
        ]);

        return redirect()->route('role.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_role' => 'required|string|max:100'
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'nama_role' => $request->nama_role
        ]);

        return redirect()->route('role.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('role.index')->with('success', 'Role berhasil dihapus.');
    }
}
