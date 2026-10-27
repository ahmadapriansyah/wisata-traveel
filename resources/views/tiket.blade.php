@extends('adminlte::page')

@section('title', 'E-Ticket / Voucher Wisata')

@section('content_header')
    <h1>E-Ticket / Voucher Wisata</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Daftar Tiket Wisata</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID Tiket</th>
                        <th>ID Pemesanan</th>
                        <th>Kode Tiket</th>
                        <th>Tanggal Terbit</th>
                        <th>Status Tiket</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $datas)
                    <tr>
                        <td>{{ $datas->id ?? '-' }}</td>
                        <td>{{ $datas->id_pemesanan ?? $datas->pemesanan_id ?? '-' }}</td>
                        <td><strong>{{ $datas->kode_tiket ?? '-' }}</strong></td>
                        <td>{{ isset($datas->created_at) ? date('d M Y', strtotime($datas->created_at)) : '-' }}</td>
                        <td><span class="badge badge-success">Aktif / Valid</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada tiket yang terbit.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop