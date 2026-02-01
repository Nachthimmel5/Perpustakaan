@extends('layouts.app')

@section('title', 'Kelola Buku')

@section('content')
<!-- Navbar (copy dari dashboard) -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Perpustakaan Admin</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.buku') }}">Kelola Buku</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.transaksi') }}">Transaksi</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.anggota') }}">Kelola Anggota</a></li>
            </ul>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3 class="mb-3">Kelola Data Buku</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form Tambah Buku -->
    <div class="card mb-4">
        <div class="card-body">
            <h5>Tambah Buku Baru</h5>
            <form method="POST" action="{{ route('admin.buku.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="judul" placeholder="Judul Buku" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="pengarang" placeholder="Pengarang" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="penerbit" placeholder="Penerbit" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="tahun" placeholder="Tahun" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Buku -->
    <table class="table table-bordered">
        <thead class="table-primary">
            <tr>
                <th>Kode Buku</th>
                <th>Judul Buku</th>
                <th>Pengarang</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($buku as $b)
            <tr>
                <td>{{ $b->id_buku }}</td>
                <td>{{ $b->judul }}</td>
                <td>{{ $b->pengarang }}</td>
                <td>{{ $b->penerbit }}</td>
                <td>{{ $b->tahun }}</td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#edit{{ $b->id_buku }}">Edit</button>
                    <form action="{{ route('admin.buku.destroy', $b->id_buku) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="edit{{ $b->id_buku }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Edit Buku</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.buku.update', $b->id_buku) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Judul Buku</label>
                                    <input type="text" class="form-control" name="judul" value="{{ $b->judul }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Pengarang</label>
                                    <input type="text" class="form-control" name="pengarang" value="{{ $b->pengarang }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Penerbit</label>
                                    <input type="text" class="form-control" name="penerbit" value="{{ $b->penerbit }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Tahun</label>
                                    <input type="number" class="form-control" name="tahun" value="{{ $b->tahun }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>
@endsection