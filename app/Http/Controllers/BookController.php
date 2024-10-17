<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Buku;

class BookController extends Controller
{
    public function index (){
        $data_buku = Book::all();

        return view('buku.index', compact('data_buku'));
    }

    public function create(){
        return view('buku.create');
    }

    public function store(Request $request){
        $buku = new Book();
        $buku -> judul = $request -> judul;
        $buku -> penulis = $request -> penulis;
        $buku -> harga = $request -> harga;
        $buku -> tgl_terbit = $request -> tgl_terbit;
        $buku -> save();

        return redirect('/buku');
    }

    public function destroy($id){
        $buku = Book::find($id);
        $buku -> delete();

        return redirect('/buku');
    }

    public function edit($id){
        $buku = Book::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }

    public function update(Request $request, $id){
        $buku = Book::findOrFail($id);
        $buku -> judul = $request -> title;
        $buku -> penulis = $request -> author;
        $buku -> harga = $request -> price;
        $buku -> tgl_terbit = $request -> date;
        $buku -> save();

        return redirect('/buku');
    }

}
