<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\UserApp;
use App\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = DB::table('v_master_user')->orderBy('iduser', 'asc')->get();
        $roles = Role::all();

        return view('user.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:45|unique:user,username',
            'password' => 'required|min:6',
            'idrole' => 'required|integer|exists:role,idrole',
        ]);

        UserApp::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'idrole' => $request->idrole
        ]);

        return redirect()->route('user.index')->with('success', 'User baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = UserApp::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:45|unique:user,username,' . $user->iduser . ',iduser',
            'idrole' => 'required|integer|exists:role,idrole',
            'password' => 'nullable|min:6'
        ]);

        $data = [
            'username' => $request->username,
            'idrole' => $request->idrole
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        UserApp::where('iduser', $id)->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }
}
