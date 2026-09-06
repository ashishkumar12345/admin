@extends('layouts.app')
@section('content')
<div class="row justify-content-center align-items-center min-vh-100">
    <div class="col-md-5">
        <div class="card border-0 shadow-lg p-4">
            <div class="card-body">
                <h3 class="fw-bold text-center text-primary mb-1">Sembark URL Shortener</h3>
                <p class="text-center text-muted mb-4">Please enter your credentials to login</p>
                @if ($errors->any())
                    <div class="alert alert-danger border-danger alert-dismissible fade show mb-4" role="alert" style="background-color: #f8d7da; color: #721c24;">
                        <strong class="text-danger">Login Failed:</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach ($errors->all() as $error)
                                <li class="text-danger fw-semibold">{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-secondary">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="admin@company.com" class="form-control form-control-lg @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback fw-semibold">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold text-secondary">Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="form-control form-control-lg @error('password') is-invalid @enderror"  required>
                        @error('password')
                            <div class="invalid-feedback fw-semibold">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                        Login to Dashboard
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection