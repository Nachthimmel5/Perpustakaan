@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container mt-5">
    <div class="col-md-6 mx-auto">
        <h3 class="mb-3">Daftar Anggota</h3>
        
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label>NIS</label>
                <input type="number" name="nis" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Kelas</label>
                <input type="text" name="kelas" class="form-control" placeholder="12 RPL 1" required>
            </div>
            <div class="mb-3">
                <label>Jurusan</label>
                <select name="jurusan" class="form-control" required>
                    <option value="">Pilih</option>
                    <option value="rpl">RPL</option>
                    <option value="tkj">TKJ</option>
                    <option value="tr">TR</option>
                    <option value="tja">TJA</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Daftar</button>
            <p class="text-center mt-3"><a href="{{ route('login') }}">Login</a></p>
        </form>
    </div>
</div>
@endsection