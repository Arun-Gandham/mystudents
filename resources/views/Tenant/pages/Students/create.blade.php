@extends('Tenant.layouts.layout1')
@section('title','Add Student')

@section('content')
<div class="container-fluid">
  <h2>Add Student</h2>
  <form method="POST" action="{{ tenant_route('tenant.students.store') }}" enctype="multipart/form-data">
    @csrf
    <x-alert-errors />
    @include('Tenant.pages.Students._form')
  </form>
</div>
@endsection
