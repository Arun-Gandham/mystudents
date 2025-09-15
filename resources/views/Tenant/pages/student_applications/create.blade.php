@extends('tenant.layouts.layout1')
@section('title','New Application')
@section('content')
<div class="container-fluid">
  <h2>New Application</h2>
  <form method="POST" action="{{ tenant_route('tenant.applications.store') }}">
    <x-alert-errors />
    @include('tenant.pages.student_applications._form')
  </form>
</div>
@endsection
