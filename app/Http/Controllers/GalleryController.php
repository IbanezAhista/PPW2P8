<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\File;

/**
 * @OA\Info(
 *     description="Contoh API doc menggunakan OpenAPI/Swagger",
 *     version="0.0.1",
 *     title="Contoh API documentation",
 *     termsOfService="http://swagger.io/terms/",
 *     @OA\Contact(
 *         email="ibanezcentivoliaahista@mail.ugm.ac.id"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */
class GalleryController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/gallery",
     *     tags={"gallery"},
     *     summary="Upload gallery image",
     *     description="Upload an image along with title and description",
     *     operationId="uploadGallery",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  required = {"title", "description", "file"},
     *                  @OA\Property(
     *                      property = "title",
     *                      type="string",
     *                      description="judul",
     *                      example="Bulan dan Bintang"
     *                  ),
     *                  @OA\Property(
     *                      property = "description",
     *                      type="string",
     *                      description="deskripsi",
     *                      example="Pertemuan Pertama Bulan dan Bintang"
     *                  ),
     *                  @OA\Property(
     *                      property = "file",
     *                      type="string",
     *                      format="binary",
     *                      description="gambar"
     *                  )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             example={
     *                 "success": true,
     *                 "message": "Berhasil menambahkan gambar",
     *                 "data": {
     *                     "output": "halo nama file deskripsi gambar",
     *                     "title": "judul",
     *                     "description": "alamak",
     *                     "file": "gambar"
     *                 }
     *             }
     *         )
     *     )
     * )
     */

    public function gallery(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'file' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan file ke direktori
        $filePath = $request->file('file')->store('uploads', 'public');

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan gambar',
            'data' => [
                'title' => $request->title,
                'description' => $request->description,
                'file' => $filePath,
            ],
        ]);
    }

    public function index()
    {
        $data = array(
            'id' => "posts",
            'menu' => 'Gallery',
            'galleries' => Post::where(
                'picture',
                '!=',
                ''
            )->whereNotNull('picture')->orderBy('created_at', 'desc')->paginate(30)
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
        $request->validate([
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

    public function indexapi() {
        $data_buku_bergambar = Post::where('picture', '!=', '')->whereNotNull('picture')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'message' => "Berhasil mendapatkan semua buku",
            'data' => $data_buku_bergambar,
        ], 200);
    }
}
