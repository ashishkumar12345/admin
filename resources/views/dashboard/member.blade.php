@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark m-0">Member Dashboard</h3>
    <span class="badge bg-primary px-3 py-2 fs-6">Member Panel</span>
</div>
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="card border-0 shadow-sm p-4 mb-4">
    <h4 class="card-title text-primary fw-bold mb-3">Generate Short URL</h4>
    @if ($errors->urlErrorBag->any())
        <div class="alert alert-danger border-danger alert-dismissible fade show" role="alert" style="background-color: #f8d7da; color: #721c24;">
            <strong class="text-danger">Validation Error:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->urlErrorBag->all() as $error)
                    <li class="text-danger fw-bold">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @elseif ($errors->any())
        <div class="alert alert-danger border-danger alert-dismissible fade show" role="alert" style="background-color: #f8d7da; color: #721c24;">
            <strong class="text-danger">Validation Error:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li class="text-danger fw-bold">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('shorturl.store') }}" method="POST" novalidate>
        @csrf
        <div class="d-flex gap-2 align-items-center">
            <input type="url" name="long_url" value="{{ old('long_url') }}" placeholder="Original Long URL" class="form-control form-control-lg @if($errors->has('long_url') || $errors->urlErrorBag->has('long_url')) is-invalid @endif" style="width: 60%;">
            <button type="submit" class="btn btn-primary btn-lg px-4 fw-semibold">Shorten</button>
        </div>
    </form>
</div>
<div class="card border-0 shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="card-title text-dark fw-bold m-0">My Short URLs</h4>
       <div class="dropdown">
            <button class="btn btn-outline-primary btn-sm dropdown-toggle fw-semibold" type="button" id="downloadCsvDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Download
            </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="downloadCsvDropdown">
                    <li><a class="dropdown-item" href="{{ route('export.shorturls', ['filter' => 'this_month']) }}">This Month</a></li>
                    <li><a class="dropdown-item" href="{{ route('export.shorturls', ['filter' => 'last_month']) }}">Last Month</a></li>
                    <li><a class="dropdown-item" href="{{ route('export.shorturls', ['filter' => 'last_week']) }}">Last Week</a></li>
                    <li><a class="dropdown-item" href="{{ route('export.shorturls', ['filter' => 'today']) }}">Today</a></li>
                </ul>
            </div>
        </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Short URL</th>
                    <th>Long  URL</th>
                    <th>Hits</th>
                    <th>Name</th>
                    <th>Created On</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shortUrls as $url)
                    <tr>
                        <td>
                            <a href="{{ url('/s/'.$url->short_url) }}" target="_blank" class="fw-bold">
                                {{ url('/s/'.$url->short_url) }}
                            </a>
                        </td>
                        <td class="text-break">{{ $url->long_url }}</td>
                        <td><span class="badge bg-success">{{ $url->clicks }}</span></td>
                        <td><span class="badge bg-secondary">{{ $url->user->name ?? 'N/A' }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($url->created_at)->format('d M y') }}</td>
                    </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-3">No data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end mt-3">
        {{ $shortUrls->links() }}
    </div>
</div>
@endsection