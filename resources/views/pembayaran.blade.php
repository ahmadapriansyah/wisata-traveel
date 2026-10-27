@extends('adminlte::page')

@section('title', 'Pembayaran')

@section('content_header')
    <h1>Pembayaran & Verifikasi</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(Auth::user()->role === 'Wisatawan')
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">Form Konfirmasi Pembayaran</h3>
        </div>
        <form action="/proses-bayar" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>ID Pemesanan</label>
                    <input type="number" name="id_pemesanan" class="form-control" placeholder="Masukkan ID Pemesanan" required>
                </div>
                <div class="form-group">
                    <label>Jumlah Bayar (Rp)</label>
                    <input type="number" name="jumlah_bayar" class="form-control" placeholder="Masukkan Nominal Pembayaran" required>
                </div>
                <div class="form-group">
                    <label>Bukti Pembayaran (Nama File / URL)</label>
                    <input type="text" name="bukti_bayar" class="form-control" placeholder="bukti_transfer.jpg" required>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">Kirim Pembayaran</button>
            </div>
        </form>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pembayaran</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Pembayaran</th>
                        <th>ID Pemesanan</th>
                        <th>Jumlah Bayar</th>
                        <th>Status</th>
                        @if(Auth::user()->role === 'Admin')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td>{{ $row->id ?? '-' }}</td>
                        <td>{{ $row->id_pemesanan ?? $row->pemesanan_id ?? '-' }}</td>
                        <td>Rp {{ number_format($row->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
                        <td><span class="badge badge-info">{{ $row->status ?? 'Proses' }}</span></td>
                        @if(Auth::user()->role === 'Admin')
                            <td>
                                <a href="/pembayaran/verifikasi/{{ $row->id }}" class="btn btn-sm btn-success">Verifikasi</a>
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop