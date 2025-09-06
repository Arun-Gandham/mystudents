@extends('tenant.baselayout')
@section('title','New Application')

@section('content')
<div class="container-fluid py-4">
  <h4>Create New Application</h4>
  @include('components.alert-errors')
  <form method="POST" action="{{ tenant_route('tenant.applications.store') }}">
    @csrf
    @include('tenant.pages.student_applications.partials.form')
    <div class="mt-3 text-end">
      <button class="btn btn-primary">Save</button>
    </div>
  </form>
</div>
@endsection
