@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Contact Details</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $contact->name }}</h5>
            <p class="card-text">
                <strong>Email:</strong> {{ $contact->email }}<br>
                <strong>Phone:</strong> {{ $contact->phone ?? 'N/A' }}<br>
                <strong>Address:</strong> {{ $contact->address ?? 'N/A' }}
            </p>

            <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Back to Contacts</a>
            <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-primary">Edit Contact</a>
        </div>
    </div>
</div>
@endsection
