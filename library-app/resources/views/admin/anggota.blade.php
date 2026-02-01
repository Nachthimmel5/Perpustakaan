@extends('layouts.app')

@section('title', 'Kelola Anggota')

@section('content')
<!-- Navbar -->
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
    <h3 class="mb-3">Kelola Data Anggota</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Tambah Anggota -->
    <div class="card mb-4">
        <div class="card-body">
            <h5>Tambah Anggota Baru</h5>
            <form method="POST" action="{{ route('admin.anggota.store') }}">
                @csrf
                <div class="row mb-2">
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="nis" placeholder="NIS" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="kelas" placeholder="Kelas (X, XI, XII)" required>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="jurusan" required>
                            <option value="">Pilih Jurusan</option>
                            <option value="rpl">RPL</option>
                            <option value="tkj">TKJ</option>
                            <option value="tr">TR</option>
                            <option value="tja">TJA</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                    </div>
                    <div class="col-md-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Anggota -->
    <table class="table table-bordered">
        <thead class="table-primary">
            <tr>
                <th>ID Anggota</th>
                <th>NIS</th>
                <th>Nama Anggota</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Username</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anggota as $a)
            <tr>
                <td>{{ $a->id_anggota }}</td>
                <td>{{ $a->nis }}</td>
                <td>{{ $a->nama }}</td>
                <td>{{ $a->kelas }}</td>
                <td>{{ strtoupper($a->jurusan) }}</td>
                <td>{{ $a->user->username }}</td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#edit{{ $a->id_anggota }}">Edit</button>
                    <form action="{{ route('admin.anggota.destroy', $a->id_anggota) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus anggota ini?')">Hapus</button>
                    </form>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="edit{{ $a->id_anggota }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Edit Anggota</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.anggota.update', $a->id_anggota) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>NIS</label>
                                    <input type="number" class="form-control" name="nis" value="{{ $a->nis }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama" value="{{ $a->nama }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Kelas</label>
                                    <input type="text" class="form-control" name="kelas" value="{{ $a->kelas }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Jurusan</label>
                                    <select class="form-control" name="jurusan" required>
                                        <option value="rpl" {{ $a->jurusan == 'rpl' ? 'selected' : '' }}>RPL</option>
                                        <option value="tkj" {{ $a->jurusan == 'tkj' ? 'selected' : '' }}>TKJ</option>
                                        <option value="tr" {{ $a->jurusan == 'tr' ? 'selected' : '' }}>TR</option>
                                        <option value="tja" {{ $a->jurusan == 'tja' ? 'selected' : '' }}>TJA</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Username</label>
                                    <input type="text" class="form-control" name="username" value="{{ $a->user->username }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Password Baru (kosongkan jika tidak diganti)</label>
                                    <input type="password" class="form-control" name="password" placeholder="Isi jika ingin ganti password">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <tr>
                <td colspan="7" class="text-center">Belum ada anggota terdaftar</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection