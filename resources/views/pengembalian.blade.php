@extends ('adminlte::page')

@section ('title', config('app.name', 'Laravel'))

@section ('content_header')
    <h1>Data Pengembalian (Refund / Pindah Jadwal)</h1>
@stop

@section ('content')
    <a href="/pengembalian/tambah" class="btn btn-warning">Proses Pengembalian</a>
    <a href="/pengembalian/pdf" class="btn btn-secondary">Cetak PDF</a>
    <a href="/pengembalian/excel" class="btn btn-primary">Laporan Excel</a>
    <a href="/pengembalian/csv" class="btn btn-info">Laporan CSV</a>
    <table border="1px" class="table table-bordered mt-3" style="width:1000px;">
        <thead>
            <tr>
                <th>Jenis Penanganan</th>
                <th>Nominal Refund</th>
                <th>Alasan</th>
                <th>Tanggal Pengembalian</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $datas)
            <tr>
                <td>{{ $datas->jenis }}</td>
                <td>Rp {{ number_format($datas->nominal, 0, ',', '.') }}</td>
                <td>{{ $datas->alasan }}</td>
                <td>{{ $datas->tanggal_pengembalian }}</td>
                <td>
                    <a href="/pengembalian/ubah/{{ $datas->id }}">Edit</a>
                    @method('delete')
                    <a href="/pengembalian/hapus/{{ $datas->id }}" onclick="return confirm('Anda Yakin Ingin Menghapus data ini ?')">Hapus</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@stop