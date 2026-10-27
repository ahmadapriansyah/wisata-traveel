@extends('adminlte::page')

@section('title', 'Dashboard - Wisata Travel')

@section('content_header')
    <h1>Dashboard Wisata Travel</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>10</h3>
                    <p>Modul Sistem</p>
                </div>
                <div class="icon">
                    <i class="fas fa-th-list"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Aktif</h3>
                    <p>Status Sistem</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            Welcome to Wisata Travel & Tour Management System!
        </div>
    </div>
@stop