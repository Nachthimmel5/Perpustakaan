@extends('layouts.app')

@section('title', 'Transaksi')

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
    <h3 class="mb-3">Kelola Transaksi Peminjaman</h3>

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

    <!-- Form Tambah Transaksi -->
    <div class="card mb-4">
        <div class="card-body">
            <h5>Tambah Transaksi Peminjaman</h5>
            <form method="POST" action="{{ route('admin.transaksi.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <select class="form-control" name="id_anggota" required>
                            <option value="">Pilih Anggota</option>
                            @foreach($anggota as $a)
                                <option value="{{ $a->id_anggota }}">{{ $a->nis }} - {{ $a->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="id_buku" required>
                            <option value="">Pilih Buku</option>
                            @foreach($buku as $b)
                                <option value="{{ $b->id_buku }}">{{ $b->judul }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="datetime-local" class="form-control" name="tanggal_pinjam" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <table class="table table-bordered">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>NIS</th>
                <th>Nama Anggota</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $t)
            <tr>
                <td>{{ $t->id_transaksi }}</td>
                <td>{{ $t->anggota->nis }}</td>
                <td>{{ $t->anggota->nama }}</td>
                <td>{{ $t->anggota->kelas }}</td>
                <td>{{ strtoupper($t->anggota->jurusan) }}</td>
                <td>{{ $t->buku->judul }}</td>
                <td>{{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d/m/Y H:i') }}</td>
                <td>
                    @if($t->tanggal_kembali)
                        {{ \Carbon\Carbon::parse($t->tanggal_kembali)->format('d/m/Y H:i') }}
                    @else
                        <span class="badge bg-warning text-dark">Belum Kembali</span>
                    @endif
                </td>
                <td>
                    @if($t->tanggal_kembali)
                        <span class="badge bg-success">Sudah Kembali</span>
                    @else
                        <span class="badge bg-danger">Dipinjam</span>
                    @endif
                </td>
                <td>
                    @if(!$t->tanggal_kembali)
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#kembali{{ $t->id_transaksi }}">Kembalikan</button>
                    @endif
                    <form action="{{ route('admin.transaksi.destroy', $t->id_transaksi) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus transaksi ini?')">Hapus</button>
                    </form>
                </td>
            </tr>

            <!-- Modal Pengembalian -->
            @if(!$t->tanggal_kembali)
            <div class="modal fade" id="kembali{{ $t->id_transaksi }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Pengembalian Buku</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.transaksi.update', $t->id_transaksi) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <p><strong>Anggota:</strong> {{ $t->anggota->nama }}</p>
                                <p><strong>Buku:</strong> {{ $t->buku->judul }}</p>
                                <p><strong>Tanggal Pinjam:</strong> {{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d/m/Y H:i') }}</p>
                                <div class="mb-3">
                                    <label>Tanggal Kembali</label>
                                    <input type="datetime-local" class="form-control" name="tanggal_kembali" 
                                           value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Konfirmasi Pengembalian</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            @empty
            <tr>
                <td colspan="10" class="text-center">Belum ada transaksi</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection