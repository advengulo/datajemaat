@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Pending Draft Approvals</h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($drafts->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No pending drafts to review.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Operation Type</th>
                                        <th>Submitted By</th>
                                        <th>Submitted At</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($drafts as $draft)
                                    <tr>
                                        <td>{{ $draft->id }}</td>
                                        <td>
                                            <span class="badge badge-{{ $draft->operation_type === 'create' ? 'success' : ($draft->operation_type === 'update' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($draft->operation_type) }}
                                            </span>
                                        </td>
                                        <td>{{ $draft->submitter->name ?? 'Unknown' }}</td>
                                        <td>{{ $draft->submitted_at ? $draft->submitted_at->format('d M Y H:i') : '-' }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $draft->status)) }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.drafts.review', $draft) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> Review
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
