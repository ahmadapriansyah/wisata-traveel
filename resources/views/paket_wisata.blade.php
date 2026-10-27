@extends ('adminlte::page')

@section ('title', config('app.name', 'Laravel'))

@section ('content_header')
    <h1>Data Paket Wisata</h1>
@stop

@section ('content')
    <a href="/paket-wisata/tambah" class="btn btn-warning">Tambah Paket</a>
    <table border="1px" class="table table-bordered mt-3" style="width:1000px;">
        <thead>
            <tr>
                <th>Nama Paket</th>
                <th>Destinasi</th>
                <th>Jenis Layanan</th>
                <th>Harga</th>
                <th>Durasi</th>
                <th>Meeting Point</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $datas)
            <tr>
                <td>{{ $datas->nama_paket }}</td>
                <td>{{ $datas->destinasi }}</td>
                <td>{{ $datas->jenis_layanan }}</td>
                <td>Rp {{ number_format($datas->harga, 0, ',', '.') }}</td>
                <td>{{ $datas->durasi_hari }} Hari</td>
                <td>{{ $datas->meeting_point }} ({{ $datas->jam_kumpul }})</td>
                <td>
                    <a href="/paket-wisata/ubah/{{ $datas->id }}">Edit</a>
                    @method('delete')
                    <a href="/paket-wisata/hapus/{{ $datas->id }}" onclick="return confirm('Anda Yakin Ingin Menghapus data ini ?')">Hapus</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@stop