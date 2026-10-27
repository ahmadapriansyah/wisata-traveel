@extends ('adminlte::page')

@section ('title', config('app.name', 'Laravel'))

@section ('content_header')
    <h1>Pengajuan Custom Trip</h1>
@stop

@section ('content')
    <a href="/custom-trip/tambah" class="btn btn-warning">Tambah Pengajuan</a>
    <a href="/custom-trip/pdf" class="btn btn-secondary">Cetak PDF</a>
    <a href="/custom-trip/excel" class="btn btn-primary">Laporan Excel</a>
    <a href="/custom-trip/csv" class="btn btn-info">Laporan CSV</a>
    <table border="1px" class="table table-bordered mt-3" style="width:1000px;">
        <thead>
            <tr>
                <th>Tujuan</th>
                <th>Tanggal Diinginkan</th>
                <th>Durasi</th>
                <th>Jumlah Peserta</th>
                <th>Biaya Penawaran</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $datas)
            <tr>
                <td>{{ $datas->tujuan }}</td>
                <td>{{ $datas->tanggal_diinginkan }}</td>
                <td>{{ $datas->durasi_hari }} Hari</td>
                <td>{{ $datas->jumlah_peserta }} Orang</td>
                <td>Rp {{ number_format($datas->biaya_penawaran, 0, ',', '.') }}</td>
                <td>{{ $datas->status }}</td>
                <td>
                    <a href="/custom-trip/ubah/{{ $datas->id }}">Edit</a>
                    @method('delete')
                    <a href="/custom-trip/hapus/{{ $datas->id }}" onclick="return confirm('Anda Yakin Ingin Menghapus data ini ?')">Hapus</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@stop