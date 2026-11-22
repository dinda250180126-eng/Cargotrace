<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Menampilkan daftar semua pengguna
    public function index()
    {
        // Kunci keamanan: Pastikan hanya admin yang bisa membuka halaman ini
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only Admin can manage users.');
        }

        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Mengubah role (jabatan) pengguna
    public function update(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $request->validate(['role' => 'required|in:admin,customer']);
        $user->update(['role' => $request->role]);

        return redirect('/users')->with('success', 'Role untuk ' . $user->name . ' berhasil diubah menjadi ' . strtoupper($request->role) . '!');
    }

    // Menghapus pengguna
    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        // Mencegah admin menghapus akunnya sendiri
        if (Auth::id() === $user->id) {
            return redirect('/users')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
        }

        $user->delete();
        return redirect('/users')->with('success', 'Akun pengguna berhasil dihapus.');
    }
}