<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\File;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array(
            'id' => "posts",
            'menu' => 'Gallery',
            'galleries' => Post::where('picture', '!=', 
            '')->whereNotNull('picture')->orderBy('created_at', 'desc')->paginate(30)
        );
        return view('gallery.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gallery.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate( [
            'title' => 'required|max:255',
            'description' => 'required',
            'picture' => 'image|nullable|max:1999'
        ]);
        if ($request->hasFile('picture')) {
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('picture')->getClientOriginalExtension();
            $basename = uniqid() . time();
            $smallFilename = "small_{$basename}.{$extension}";
            $mediumFilename = "medium_{$basename}.{$extension}";
            $largeFilename = "large_{$basename}.{$extension}";
            $filenameSimpan = "{$basename}.{$extension}";
            $path = $request->file('picture')->storeAs('posts_image', $filenameSimpan);
        } else {
            $filenameSimpan = 'noimage.png';
        }
        // dd($request->input());
        $post = new Post;
        $post->picture = $filenameSimpan;
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->save();
        return redirect('gallery')->with('success', 'Berhasil menambahkan data baru!');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::find($id);
        return view('gallery.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'picture' => 'image|nullable|max:10000|mimes:jpg,jpeg,png', // batas ukuran image 10MB
        ]);

        // Ambil data pengguna berdasarkan ID
        $post = Post::findOrFail($id);
        $filenameOriginal = $post->picture; // Foto lama akan tetap digunakan jika tidak ada foto baru

        if ($request->hasFile('picture')) {
            // Hapus image lama jika ada
            if ($post->picture != null) {

                File::delete(storage_path('posts_image/' . $post->picture));
            }

            // Menyiapkan nama file baru
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('picture')->getClientOriginalExtension();
            $basename = uniqid() . time();
            $smallFilename = "small_{$basename}.{$extension}";
            $mediumFilename = "medium_{$basename}.{$extension}";
            $largeFilename = "large_{$basename}.{$extension}";
            $filenameSimpan = "{$basename}.{$extension}";
            $path = $request->file('picture')->storeAs('posts_image', $filenameSimpan);

            // update nama di database ness mulai soko kene oke?
            // sedurung e rung di save, pantes ga mlebu ndek database
            $post->picture = $filenameSimpan;
            $post->save();

            return redirect('gallery')->with('success', 'Berhasil menambahkan foto baru!');
        } else {
            if ($post->picture != null) {
                $filenameOriginal = $post->picture;
            } else {
                $filenameOriginal = null;
            }
        }

        try {
            // Update data post dengan foto baru (jika ada) atau foto lama
            $post->update([
                'picture' => $filenameOriginal,
            ]);

            // Flash message sukses jika berhasil
            return redirect('gallery')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            // Flash message error jika gagal
            return redirect()->back()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gallery = Post::findOrFail($id);
        $file = public_path() . '/storage/posts_image/' . $gallery->picture;

        

        try {
            if (File::exists($file)) {
                File::delete($file);
                $gallery->delete();
            }
        } catch (\Throwable $th) {
            return redirect('gallery')->with('error', 'Gagal hapus data');
        }
        return redirect('gallery');
    }
}
