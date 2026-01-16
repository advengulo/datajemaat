@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="mb-3">
                <a href="{{ route('admin.drafts.pending') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Pending Drafts
                </a>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="mb-0">Review Draft #{{ $draft->id }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Operation Type:</strong>
                                <span class="badge badge-{{ $draft->operation_type === 'create' ? 'success' : ($draft->operation_type === 'update' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($draft->operation_type) }}
                                </span>
                            </p>
                            <p><strong>Status:</strong>
                                <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $draft->status)) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Submitted By:</strong> {{ $draft->submitter->name ?? 'Unknown' }}</p>
                            <p><strong>Submitted At:</strong> {{ $draft->submitted_at ? $draft->submitted_at->format('d M Y H:i:s') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Submitted Data</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 30%;">Field</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($draftData as $key => $value)
                                <tr>
                                    <td><strong>{{ ucwords(str_replace('_', ' ', $key)) }}</strong></td>
                                    <td>{{ is_array($value) ? json_encode($value) : ($value ?? '-') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <details class="mt-3">
                        <summary class="btn btn-sm btn-outline-secondary">View Raw JSON</summary>
                        <pre class="mt-2 p-3 bg-light border rounded">{{ json_encode($draftData, JSON_PRETTY_PRINT) }}</pre>
                    </details>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Review Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <form method="POST" action="{{ route('admin.drafts.approve', $draft) }}">
                                @csrf
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <strong>Approve & Publish</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="approve-note">Approval Note (Optional)</label>
                                            <textarea name="note" id="approve-note" class="form-control" rows="3" placeholder="Add any comments about this approval..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Are you sure you want to approve and publish this draft?')">
                                            <i class="fas fa-check"></i> Approve & Publish
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-4">
                            <form method="POST" action="{{ route('admin.drafts.reject', $draft) }}">
                                @csrf
                                <div class="card border-danger">
                                    <div class="card-header bg-danger text-white">
                                        <strong>Reject</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="reject-note">Rejection Reason (Required)</label>
                                            <textarea name="note" id="reject-note" class="form-control" rows="3" placeholder="Explain why this draft is being rejected..." required></textarea>
                                            @error('note')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to reject this draft?')">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-4">
                            <form method="POST" action="{{ route('admin.drafts.request-revision', $draft) }}">
                                @csrf
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-dark">
                                        <strong>Request Revision</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="revision-note">Revision Instructions (Required)</label>
                                            <textarea name="note" id="revision-note" class="form-control" rows="3" placeholder="Describe what needs to be changed..." required></textarea>
                                            @error('note')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-warning btn-block" onclick="return confirm('Request revision for this draft?')">
                                            <i class="fas fa-edit"></i> Request Revision
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
