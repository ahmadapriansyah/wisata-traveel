@extends('adminlte::page')

@section('title', 'Dashboard Wisatawan')

@section('content_header')
    <h1 class="m-0 text-dark">Selamat Datang di Wisata Travel!</h1>
@endsection

@section('content')
<div class="container-fluid">

    <div class="card bg-dark text-white border-0 shadow-lg rounded-lg overflow-hidden mb-4">
    <img src="{{ asset('storage/images/banner-wisata.jpg') }}" 
         class="card-img opacity-50" 
         alt="Banner Wisata" 
         style="max-height: 320px; object-fit: cover;" 
         onerror="this.onerror=null; this.src='{{ asset('images/banner-wisata.jpg') }}';">
    <div class="card-img-overlay d-flex flex-column justify-content-center align-items-center text-center p-4">
        <h1 class="display-4 font-weight-bold text-shadow">Jelajahi Keindahan Destinasi Impianmu</h1>
        <p class="lead font-weight-normal">Temukan paket wisata terbaik dan pesan tiket perjalananmu dengan mudah.</p>
        <a href="#destinasi" class="btn btn-warning btn-lg font-weight-bold shadow-sm rounded-pill px-4">Cari Destinasi</a>
    </div>
</div>

    <div id="destinasi" class="pt-2 mb-3">
        <h3 class="font-weight-bold text-gray-800 mb-3"><i class="fas fa-map-marked-alt text-primary mr-2"></i>Destinasi Populer</h3>
    </div>

    <div class="row">
        @forelse($wisata ?? [] as $item)
            @php
                $itemArray = (array) $item;
                $nama  = $itemArray['nama_paket'] ?? $itemArray['nama_wisata'] ?? 'Destinasi Wisata';
                $harga = (float) ($itemArray['harga'] ?? 0);
                $foto  = $itemArray['foto'] ?? null;
                $desk  = $itemArray['deskripsi'] ?? 'Nikmati liburan seru dan pengalaman tak terlupakan.';
            @endphp

            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0 rounded-lg overflow-hidden card-hover">
                    <div class="position-relative">
                        <img src="{{ $foto ? asset('storage/' . $foto) : 'https://via.placeholder.com/400x200?text=Foto+Wisata' }}" class="card-img-top" alt="Wisata" style="height: 200px; object-fit: cover;">
                        <span class="badge badge-success position-absolute" style="top: 10px; right: 10px; font-size: 0.85rem; padding: 6px 10px;">
                            Rp {{ number_format($harga, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title font-weight-bold text-dark">{{ $nama }}</h5>
                        
                        <div class="my-2">
                            <span class="badge badge-light border"><i class="fas fa-car text-primary mr-1"></i> Mobil + Driver</span>
                            <span class="badge badge-light border"><i class="fas fa-ticket-alt text-success mr-1"></i> Tiket Masuk</span>
                        </div>

                        <p class="card-text text-muted small mt-1">
                            {{ $desk }}
                        </p>
                        
                        <div class="mt-auto pt-3">
                            <a href="{{ route('pesan.form', $itemArray['id'] ?? $item->id) }}" class="btn btn-outline-primary btn-block rounded-pill">
                                <i class="fas fa-ticket-alt mr-1"></i> Pesan Tiket
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center shadow-sm">
                    <i class="fas fa-info-circle mr-1"></i> Belum ada data tempat wisata yang tersedia.
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection

@section('css')
<style>
    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
    }
    .text-shadow {
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
    }
    .opacity-50 {
        opacity: 0.4;
    }
</style>
@endsection
