@extends ('adminlte::page')

@section ('title', config('app.name', 'Laravel'))

@section ('content_header')
    <h1>Jadwal Keberangkatan</h1>
@stop

@section ('content')
    <a href="/jadwal-keberangkatan/tambah" class="btn btn-warning">Tambah Jadwal</a>
    <a href="/jadwal-keberangkatan/pdf" class="btn btn-secondary">Cetak PDF</a>
    <a href="/jadwal-keberangkatan/excel" class="btn btn-primary">Laporan Excel</a>
    <a href="/jadwal-keberangkatan/csv" class="btn btn-info">Laporan CSV</a>
    <table border="1px" class="table table-bordered mt-3" style="width:1000px;">
        <thead>
            <tr>
                <th>Tanggal Berangkat</th>
                <th>Kuota</th>
                <th>Sisa Kuota</th>
                <th>Min. Peserta</th>
                <th>Biaya Operasional</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $datas)
            <tr>
                <td>{{ isset($datas->tanggal_berangkat) ? date('d M Y', strtotime($datas->tanggal_berangkat)) : ($datas->tanggal ?? '-') }}</td>
                <td>{{ $datas->kuota ?? 0 }} Orang</td>
                <td>{{ $datas->sisa_kuota ?? $datas->kuota ?? 0 }} Orang</td>
                <td>{{ $datas->min_peserta ?? $datas->minimal_peserta ?? '-' }}</td>
                <td>Rp {{ number_format($datas->biaya_operasional ?? $datas->harga ?? 0, 0, ',', '.') }}</td>
                <td>
                    <span class="badge {{ ($datas->status_keberangkatan ?? '') === 'Terjadwal' ? 'badge-info' : 'badge-secondary' }}">
                        {{ $datas->status_keberangkatan ?? $datas->status ?? 'Terjadwal' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@stop