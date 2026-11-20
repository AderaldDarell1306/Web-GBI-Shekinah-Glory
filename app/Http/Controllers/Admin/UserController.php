<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Hanya super_admin & developer yang bisa mengelola akun
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak: Hanya Administrator Utama yang dapat mengelola pengguna dan hak akses.');
        }

        $users = User::with('wilayah')->orderBy('role')->orderBy('name')->get();
        $wilayahList = Wilayah::aktif()->orderBy('nama_wilayah')->get();

        return view('admin.users.index', compact('users', 'wilayahList'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:developer,super_admin,gembala,bendahara,ketua_cool,admin',
            'wilayah_id' => 'nullable|exists:wilayah,id',
            'telepon' => 'nullable|string|max:30',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'wilayah_id' => $validated['role'] === 'ketua_cool' ? $validated['wilayah_id'] : null,
            'telepon' => $validated['telepon'],
            'status' => 'aktif',
        ]);

        return redirect()->back()->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => "required|email|unique:users,email,{$id}",
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:developer,super_admin,gembala,bendahara,ketua_cool,admin',
            'wilayah_id' => 'nullable|exists:wilayah,id',
            'telepon' => 'nullable|string|max:30',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'wilayah_id' => $validated['role'] === 'ketua_cool' ? $validated['wilayah_id'] : null,
            'telepon' => $validated['telepon'],
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->back()->with('success', "Akun {$user->name} berhasil diperbarui!");
    }

    public function destroy($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        if (Auth::id() == $id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus.');
    }
}

