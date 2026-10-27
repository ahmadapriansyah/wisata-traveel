@extends ('adminlte::page')

@section ('title', config('app.name', 'Laravel'))

@section ('content_header')
    <h1>Data Peserta Wisata</h1>
@stop

@section ('content')
    <a href="/peserta/tambah" class="btn btn-warning">Tambah Peserta</a>
    <a href="/peserta/pdf" class="btn btn-secondary">Cetak PDF</a>
    <a href="/peserta/excel" class="btn btn-primary">Laporan Excel</a>
    <a href="/peserta/csv" class="btn btn-info">Laporan CSV</a>
    <table border="1px" class="table table-bordered mt-3" style="width:1000px;">
        <thead>
            <tr>
                <th>Nama Lengkap</th>
                <th>Jenis Identitas</th>
                <th>No. Identitas</th>
                <th>File Identitas</th>
                <th>No. HP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
           @foreach ($data as $datas)
            <tr>
                <td>{{ $datas->nama_lengkap ?? '-' }}</td>
                <td>{{ strtoupper($datas->jenis_identitas ?? '') }}</td>
                <td>{{ $datas->no_identitas ?? '-' }}</td>
                <td>{{ $datas->file_identitas ?? $datas->foto_identitas ?? '-' }}</td>
                <td>{{ $datas->no_hp ?? $datas->kontak ?? '-' }}</td>
                <td>
                    <a href="/peserta/ubah/{{ $datas->id }}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="/peserta/hapus/{{ $datas->id }}" class="btn btn-sm btn-danger" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@stop