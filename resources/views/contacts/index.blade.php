@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Contacts</h1>

    <!-- Search and Sorting Controls -->
    <div class="row mb-4">
        <div class="col-md-6">
            <!-- Search Form -->
            <form method="GET" action="{{ route('contacts.index') }}" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by name or email" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        <div class="col-md-6 text-md-end">
            <!-- Sorting Links -->
            <div class="btn-group" role="group">
                <a href="{{ route('contacts.index', ['sort_by' => 'name', 'direction' => $sortDirection === 'asc' ? 'desc' : 'asc']) }}" class="btn btn-outline-primary">
                    Sort by Name {{ $sortColumn === 'name' ? ($sortDirection === 'asc' ? '🔼' : '🔽') : '' }}
                </a>
                <a href="{{ route('contacts.index', ['sort_by' => 'created_at', 'direction' => $sortDirection === 'asc' ? 'desc' : 'asc']) }}" class="btn btn-outline-primary">
                    Sort by Date {{ $sortColumn === 'created_at' ? ($sortDirection === 'asc' ? '🔼' : '🔽') : '' }}
                </a>
            </div>
        </div>
    </div>

    <!-- Contacts Table -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                    <tr>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->phone ?? 'N/A' }}</td>
                        <td>{{ $contact->address ?? 'N/A' }}</td>
                        <td>{{ $contact->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <a href="{{ route('contacts.show', $contact->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form method="POST" action="{{ route('contacts.destroy', $contact->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this contact?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No contacts found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $contacts->appends(['search' => request('search'), 'sort_by' => $sortColumn, 'direction' => $sortDirection])->links() }}
    </div>
</div>
@endsection
