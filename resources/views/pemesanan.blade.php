@extends ('adminlte::page')

@section ('title', config('app.name', 'Laravel'))

@section ('content_header')
    <h1>Data Pemesanan Wisata</h1>
@stop

@section ('content')
    <a href="/pemesanan/tambah" class="btn btn-warning">Tambah Pemesanan</a>
    <a href="/pemesanan/pdf" class="btn btn-secondary">Cetak PDF</a>
    <a href="/pemesanan/excel" class="btn btn-primary">Laporan Excel</a>
    <a href="/pemesanan/csv" class="btn btn-info">Laporan CSV</a>
    <table border="1px" class="table table-bordered mt-3" style="width:1000px;">
        <thead>
            <tr>
                <th>Kode Pemesanan</th>
                <th>Jumlah Peserta</th>
                <th>Total Biaya</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
           @foreach ($data as $datas)
            <tr>
                <td>{{ $datas->kode_pemesanan ?? $datas->id ?? '-' }}</td>
                <td>{{ $datas->jumlah_peserta ?? $datas->jumlah_pax ?? $datas->jumlah ?? '-' }} Orang</td>
                <td>Rp {{ number_format($datas->total_biaya ?? $datas->total_harga ?? $datas->harga ?? 0, 0, ',', '.') }}</td>
                <td>
                    <span class="badge {{ ($datas->status ?? '') === 'Lunas' ? 'badge-success' : 'badge-warning' }}">
                        {{ $datas->status ?? 'Menunggu Pembayaran' }}
                    </span>
                </td>
                <td>
                    <a href="/pembayaran" class="btn btn-sm btn-primary">Bayar / Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@stop