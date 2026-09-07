@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark m-0">SuperAdmin Overview</h3>
    <span class="badge bg-dark px-3 py-2 fs-6">System Control Center</span>
</div>
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-primary text-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-semibold mb-1">Total Companies</h6>
                    <h2 class="fw-bold mb-0">{{ $companies->total() }}</h2>
                </div>
                <div class="fs-1 opacity-50"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-dark text-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-semibold mb-1">System Short URLs</h6>
                    <h2 class="fw-bold mb-0">{{ $shortUrls->total() }}</h2>
                </div>
                <div class="fs-1 opacity-50"></div>
            </div>
        </div>
    </div>
</div>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-0">
        <h5 class="fw-bold text-primary m-0">Invite New Client</h5>
    </div>
    <div class="card-body pt-0">
        @if ($errors->any())
            <div class="alert alert-danger border-danger alert-dismissible fade show" role="alert" style="background-color: #f8d7da; color: #721c24;">
                <strong class="text-danger">Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-danger fw-bold">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('company.invite') }}" method="POST" novalidate>
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary">Name</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="Client Name ..." class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary">Admin Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter Email" class="form-control">
                </div>
                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">Send Invitation</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card p-3 mb-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="m-0">Companies List</h4>
        <div class="dropdown">
            <button class="btn btn-outline-primary btn-sm dropdown-toggle fw-semibold" type="button" id="downloadCompanyCsvDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Download
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="downloadCompanyCsvDropdown">
                <li><a class="dropdown-item" href="{{ route('export.companies', ['filter' => 'today']) }}">Today</a></li>
                <li><a class="dropdown-item" href="{{ route('export.companies', ['filter' => 'last_week']) }}">Last Week</a></li>
                <li><a class="dropdown-item" href="{{ route('export.companies', ['filter' => 'this_month']) }}">This Month</a></li>
                <li><a class="dropdown-item" href="{{ route('export.companies', ['filter' => 'last_month']) }}">Last Month</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item fw-bold text-primary" href="{{ route('export.companies', ['filter' => 'all']) }}">All Time</a></li>
            </ul>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Client Name</th>
                    <th>Users</th>
                    <th>Total Generated URLs</th>
                    <th>Total URLs Hits</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($companies as $company)
                <tr>
                    <td class="fw-bold">{{ $company->name }}</td>
                    <td><span class="badge bg-info text-dark">{{ $company->users_count }}</span></td>
                    <td><span class="badge bg-primary">{{ $company->short_urls_count }}</span></td>
                    <td><span class="badge bg-primary">{{ $company->short_urls_sum_clicks }}</span></td>
                    <td>
                        @if($company->users_count > 0)
                            <span style="color: green;">Active</span>
                        @else
                            <span style="color: orange;">Pending Acceptance</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">No companies found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end mt-3">
        {{ $companies->appends(request()->query())->links() }}
    </div>
</div>
<div class="card p-3 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="m-0">All Short URLs</h4>
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
                    <td><span class="badge bg-secondary">{{ $url->company->name ?? 'N/A' }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($url->created_at)->format('d M y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">No short URLs generated.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end mt-3">
        {{ $shortUrls->appends(request()->query())->links() }}
    </div>
</div>
@endsection