@extends('tenant.layouts.layout1')
@section('title','New Admission')
@section('content')
<div class="container-fluid">
  <h2>New Admission</h2>
  <form method="POST" action="{{ isset($application) 
      ? tenant_route('tenant.applications.admit.store',['application' => $application->id]) 
      : tenant_route('tenant.admissions.store') }}">
      <x-alert-errors />
    @include('tenant.pages.admissions._form')
  </form>
</div>
@endsection
