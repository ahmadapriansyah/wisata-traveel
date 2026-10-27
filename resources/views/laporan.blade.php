@extends('adminlte::page')

@section('title', 'Kelola Laporan')

@section('content_header')
    <h1>Laporan Transaksi & Pemesanan</h1>
@stop

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Rekap Laporan System</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori Laporan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Laporan Pemesanan Wisata</td>
                        <td><span class="badge badge-success">Siap</span></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Laporan Pembayaran & Keuangan</td>
                        <td><span class="badge badge-success">Siap</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop