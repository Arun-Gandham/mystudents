@extends('tenant.layouts.layout1')
@section('title','Upload Document')

@section('content')
<div class="container-fluid">
  <h2>Upload Document</h2>
  <form method="POST" action="{{ tenant_route('tenant.documents.store',$student->id) }}" enctype="multipart/form-data">
    @csrf
    <x-alert-errors />
    <div class="mb-2">
      <label>Type</label>
      <select name="doc_type" class="form-control" required>
        <option value="aadhaar">Aadhaar</option>
        <option value="birth_certificate">Birth Certificate</option>
        <option value="transfer_certificate">Transfer Certificate</option>
        <option value="caste_certificate">Caste Certificate</option>
        <option value="passport_photo">Passport Photo</option>
        <option value="other">Other</option>
      </select>
    </div>
    <div class="mb-2"><label>File</label><input type="file" name="file" class="form-control" required></div>
    <button class="btn btn-success">Upload</button>
    <a href="{{ tenant_route('tenant.documents.index',$student->id) }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
