@extends('layouts.app')

@section('title', $title ?? 'Halaman Sementara')

@section('content')
    <div class="card">
        <h1>{{ $title ?? 'Halaman Sementara' }}</h1>
        <p class="muted">Halaman sementara milestone M1. Halaman ini diganti oleh fitur aslinya pada M4 (katalog) dan M7 (dashboard admin).</p>
    </div>
@endsection