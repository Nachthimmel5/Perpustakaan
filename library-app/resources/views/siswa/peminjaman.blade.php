@extends('layouts.app')

@section('title', 'Peminjaman Buku')

@section('content')
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Perpustakaan Siswa</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('siswa.dashboard') }}">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('siswa.peminjaman') }}">Peminjaman Buku</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('siswa.pengembalian') }}">Pengembalian Buku</a></li>
            </ul>
            <span class="navbar-text text-white me-3">
                {{ $anggota->nama }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3 class="mb-3">Peminjaman Buku</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Buku yang sedang dipinjam -->
    @if($sedangDipinjam->count() > 0)
    <div class="alert alert-warning">
        <strong>Buku yang sedang Anda pinjam:</strong>
        <ul class="mb-0">
            @foreach($sedangDipinjam as $pinjam)
                <li>{{ $pinjam->buku->judul }} (dipinjam: {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y') }})</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Daftar Buku Tersedia -->
    <h5 class="mb-3">Daftar Buku Tersedia</h5>
    <div class="row">
        @forelse($buku as $b)
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $b->judul }}</h5>
                    <p class="card-text mb-1"><strong>Pengarang:</strong> {{ $b->pengarang }}</p>
                    <p class="card-text mb-1"><strong>Penerbit:</strong> {{ $b->penerbit }}</p>
                    <p class="card-text mb-3"><strong>Tahun:</strong> {{ $b->tahun }}</p>
                    
                    @php
                        $sudahDipinjam = $sedangDipinjam->where('id_buku', $b->id_buku)->first();
                    @endphp
                    
                    @if($sudahDipinjam)
                        <button class="btn btn-secondary btn-sm" disabled>Sedang Anda Pinjam</button>
                    @else
                        <form method="POST" action="{{ route('siswa.peminjaman.store') }}">
                            @csrf
                            <input type="hidden" name="id_buku" value="{{ $b->id_buku }}">
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Pinjam buku ini?')">Pinjam Buku</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">Belum ada buku tersedia</div>
        </div>
        @endforelse
    </div>
</div>
@endsection