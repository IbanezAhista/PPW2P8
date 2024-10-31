<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Please login tonaccess the dashboard.',
            ])->onlyInput('email');
        }
        $users = User::get();
        return view('users')->with('userss', $users);
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'photo' => 'image|nullable|max:10000|mimes:jpg,jpeg,png', // batas ukuran image 10MB
        ]);

        // Ambil data pengguna berdasarkan ID
        $pengguna = User::findOrFail($id);

        if ($request->hasFile('photo')) {
            // Hapus image lama
            if ($pengguna->photo != null) {
                File::delete(public_path() . '/photos/' . $pengguna->photo);
            }

            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $path = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $filename = $path . '_' . time();

            // Save original image
            $filenameOriginal = $filename . '_Original.' . $extension;
            $request->file('photo')->storeAs('photos', $filenameOriginal);
        } else {
            if ($pengguna->photo != null) {
                $filenameOriginal = $pengguna->photo;
            } else {
                $filenameOriginal = null;
            }
        }

        try {
            // Update data pengguna dengan data yang baru
            $pengguna->update([
                'photo' => $filenameOriginal,
            ]);

            // Flash message sukses jika berhasil
            return redirect('users')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            // Flash message error jika gagal
            return redirect()->back()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $user = User::find($id);
        $file = public_path() . '/storage/' . $user->photo;
        try {
            if (File::exists($file)) {
                File::delete($file);
            }
        } catch (\Throwable $th) {
            return redirect('users')->with('error', 'Gagal hapus data');
        }
        return redirect('users')->with('success', 'Berhasil hapus data');
    }

    public function edit(string $id)
    {
        if (Auth::user()->id == $id) {
            // Ambil data pengguna berdasarkan id
            $user = User::findOrFail($id);

            // Tampilkan view edit dengan data pengguna
            return view('edit', compact('user'));
        } else {
            return redirect()->route('dashboard')->withError('Anda tidak bisa mengedit akun pengguna lain!');
        }
    }


}