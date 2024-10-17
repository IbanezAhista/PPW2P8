<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, 
    maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index</title>
</head>
<body>
    <a href="{{ route('buku.create') }}" class="btn btn-primary float-end">Tambah Buku</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Harga</th>
                <th>Tanggal Terbit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_buku as $buku)
                <tr>
                    <td>{{ $buku->id }}</td>
                    <td>{{ $buku->judul }}</td>
                    <td>{{ $buku->penulis }}</td>
                    <td>{{ "Rp. ".number_format($buku->harga, 2, '.', '.') }}</td>
                    <td>{{ $buku->tgl_terbit }}</td>
                    <td>
                        <form action="{{ route('buku.destroy', $buku -> id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin mau dihapus?')" type="submit"
                            class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('buku.edit', $buku -> id )}}" class="btn btn-primary float-end">Edit Buku</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h2>Jumlah Data : {{ $data_buku->count() }}</h2>
    <h2>Total Harga : Rp. {{ $data_buku->sum('harga') }}</h2>
</body>
</html>
