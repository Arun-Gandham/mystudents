@extends('tenant.baselayout')
@section('title','Edit Application')

@section('content')
<div class="container-fluid py-4">
  <h4>Edit Application</h4>
  @include('components.alert-errors')
  <form method="POST" action="{{ tenant_route('tenant.applications.update',['application' => $application->id]) }}">
    @csrf @method('PUT')
    @include('tenant.pages.student_applications.partials.form',['application'=>$application])
    <div class="mt-3 text-end">
      <button class="btn btn-primary">Update</button>
    </div>
  </form>
</div>
@endsection
