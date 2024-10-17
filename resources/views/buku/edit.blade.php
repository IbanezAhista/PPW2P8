<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku</title>
</head>
<body>
    <form method="POST" action="{{route('buku.update', $buku -> id)}}">
        @csrf
        <label>Judul</label>
        <input type="text" name="title" value="{{ $buku -> judul }}">
        <br>
        <label>Penulis</label>
        <input type="text" name="author" value="{{ $buku -> penulis }}">
        <br>
        <label>Harga</label>
        <input type="text" name="price" value="{{ $buku -> harga }}">
        <br>
        <label>Tanggal Terbit</label>
        <input type="text" name="date" value="{{ $buku -> tgl_terbit }}">
        <br>
        <button type="submit">Update</button>
    </form>
</body>
</html>