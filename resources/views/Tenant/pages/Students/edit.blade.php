@extends('tenant.layouts.layout1')
@section('title','Edit Student')

@section('content')
<div class="container-fluid">
  <h2>Edit Student</h2>
  <form method="POST" action="{{ tenant_route('tenant.students.update',['student' => $student->id]) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <x-alert-errors />
    @include('tenant.pages.students._form')
  </form>
</div>
@endsection
