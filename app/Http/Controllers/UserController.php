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
                'email' => 'Please login to access the dashboard.',
            ])->onlyInput('email');
        }
        $users = User::get();
        return view('users')->with('userss', $users);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'photo' => 'image|nullable|max:10000|mimes:jpg,jpeg,png', // batas ukuran image 10MB
        ]);

        // Ambil data pengguna berdasarkan ID
        $pengguna = User::findOrFail($id);
        $filenameOriginal = $pengguna->photo; // Foto lama akan tetap digunakan jika tidak ada foto baru

        if ($request->hasFile('photo')) {
            // Hapus image lama jika ada
            if ($pengguna->photo != null) {

                File::delete(public_path() . '/images/' . $pengguna->photo);
            }

            // Menyiapkan nama file baru
            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $path = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $filename = $path . '_' . time();
            $filenameOriginal = $filename . '_Original.' . $extension;

            $filename = $request->file('photo')->storeAs('images', $filenameOriginal, 'public');

            // update nama di database ness mulai soko kene oke?
            // sedurung e rung di save, pantes ga mlebu ndek database
            $pengguna->photo = $filename;
            $pengguna->save();
            
            return redirect('users')->with('success', "alhamdullilah foto berhasil disimpan 😎");
        } else {
            if ($pengguna->photo != null) {
                $filenameOriginal = $pengguna->photo;
            } else {
                $filenameOriginal = null;
            }
        }

        try {
            // Update data pengguna dengan foto baru (jika ada) atau foto lama
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