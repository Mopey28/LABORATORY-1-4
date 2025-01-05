{{-- @extends('layouts.admin')

@section('title', 'Create Supplier')

@section('content')
<div class="create-supplier">
    <h2>Create Supplier</h2>
    <form action="{{ route('admin.store-supplier') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="supplier_name">Name</label>
            <input type="text" name="supplier_name" id="supplier_name" class="form-control" value="{{ old('supplier_name') }}" required>
            @error('supplier_name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="email_address">Email Address</label>
            <input type="email" name="email_address" id="email_address" class="form-control" value="{{ old('email_address') }}" required>
            @error('email_address')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number') }}" required>
            @error('phone_number')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" id="image" class="form-control">
            @error('image')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Create Supplier</button>
    </form>
</div>
@endsection --}}
