@extends('adminlte::page')

@section('title', 'Tambah Paket Wisata')

@section('content_header')
    <h1>Tambah Paket Wisata</h1>
@stop

@section('content')
<div class="card card-primary">
    <form action="/paket-wisata/simpan" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Nama Paket</label>
                <input type="text" name="nama_paket" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Destinasi</label>
                <input type="text" name="destinasi" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jenis Layanan</label>
                <select name="jenis_layanan" class="form-control" required>
                    <option value="Open Trip">Open Trip</option>
                    <option value="Private Trip">Private Trip</option>
                </select>
            </div>
            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" min="0" name="harga" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Durasi (Hari)</label>
                <input type="number" min="1" name="durasi_hari" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Meeting Point</label>
                <input type="text" name="meeting_point" class="form-control">
            </div>
            <div class="form-group">
                <label>Jam Kumpul</label>
                <input type="time" name="jam_kumpul" class="form-control">
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="/paket-wisata" class="btn btn-default">Batal</a>
        </div>
    </form>
</div>
@stop