@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark m-0">Client Admin Dashboard</h3>
        <p class="text-muted mb-0">Company: <strong>{{ auth()->user()->company->name ?? 'N/A' }}</strong></p>
    </div>
    <span class="badge bg-dark px-3 py-2 fs-6">Admin Access</span>
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
                    <h6 class="text-white-50 text-uppercase fw-semibold mb-1">Company Short URLs</h6>
                    <h2 class="fw-bold mb-0">{{ $shortUrls->total() }}</h2>
                </div>
                <div class="fs-1 opacity-50"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-info text-dark p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-dark-50 text-uppercase fw-semibold mb-1">Team Members</h6>
                    <h2 class="fw-bold mb-0">{{ $teamMembers->total() }}</h2>
                </div>
                <div class="fs-1 opacity-50"></div>
            </div>
        </div>
    </div>
</div>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-0">
        <h5 class="fw-bold text-primary m-0">Generate Short URL</h5>
    </div>
    <div class="card-body pt-0">
        @if ($errors->urlErrorBag->any())
            <div class="alert alert-danger border-danger alert-dismissible fade show" role="alert" style="background-color: #f8d7da; color: #721c24;">
                <strong class="text-danger">Please fix the error:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->urlErrorBag->all() as $error)
                        <li class="text-danger fw-bold">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('shorturl.store') }}" method="POST" novalidate>
            @csrf
            <div class="row g-2 align-items-center">
                <div class="col-md-9">
                    <input type="url" name="long_url" value="{{ old('long_url') }}" placeholder="Enter Long URL" class="form-control form-control-lg @if($errors->urlErrorBag->has('long_url')) is-invalid @endif">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold">Shorten Link</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-0">
        <h5 class="fw-bold text-dark m-0">Invite Team Member</h5>
    </div>
    <div class="card-body pt-0">
        @if ($errors->memberErrorBag->any())
            <div class="alert alert-danger border-danger alert-dismissible fade show" role="alert" style="background-color: #f8d7da; color: #721c24;">
                <strong class="text-danger">Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->memberErrorBag->all() as $error)
                        <li class="text-danger fw-bold">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('member.invite') }}" method="POST" novalidate>
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter Email" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary">Role</label>
                    <select name="role" class="form-select">
                        <option value="Member" selected>Member</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-dark px-4 fw-semibold">Send Invitation</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm h-100 p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark m-0">Generated Short URLs</h5>
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
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
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
                            <td colspan="4" class="text-center text-muted py-4">
                                No data
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">
              {{ $shortUrls->appends(request()->query())->links() }}
            </div>
              <div class="col-lg-12">
        <div class="card border-0 shadow-sm h-100 p-3">
            <h5 class="fw-bold text-dark mb-3">Team Members</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Total Generated URLs</th>
                            <th>Total URLs Hits</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teamMembers as $member)
                        <tr>
                            <td class="fw-semibold text-dark">
                                {{ $member->name }}
                            </td>
                             <td class="fw-semibold text-dark">
                                {{ $member->email }}
                            </td>
                            <td>
                                <span class="badge {{ $member->role === 'Admin' ? 'bg-dark' : 'bg-secondary' }}">
                                    {{ $member->role }}
                                </span>
                            </td>
                            <td><span class="badge bg-primary">{{ $member->short_urls_count }}</span></td>
                            <td><span class="badge bg-primary">{{ $member->short_urls_sum_clicks }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">No team members found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">
                {{ $teamMembers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
  
        </div>
    </div>
</div>
@endsection