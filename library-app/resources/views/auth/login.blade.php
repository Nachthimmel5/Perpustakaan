@extends('layouts.app')

@section('title', 'login')

@section('content')
<div class="container mt-5">
    <div class="col-md-4 mx-auto">
        <h3 class="mb-3">login</h3>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{  route('login')  }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-link"><a href="{{ route('register') }}">Register</a></button>
        </form>
    </div>
</div>
@endsection