@extends('tenant.layouts.layout1')
@section('title','Edit Application')
@section('content')
<div class="container-fluid">
  <h2>Edit Application</h2>
  <form method="POST" action="{{ tenant_route('tenant.applications.update',['application' => $application->id]) }}">
    @csrf @method('PUT')
    <x-alert-errors />
    @include('tenant.pages.student_applications._form')
  </form>
</div>
@endsection
