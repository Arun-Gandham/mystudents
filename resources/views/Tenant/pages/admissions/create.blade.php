@extends('tenant.baselayout')
@section('title','New Admission')

@section('content')
<div class="container-fluid py-3">
  <h4>{{ $admission && $admission->application_no 
        ? 'Admission — '.$admission->application_no 
        : 'New Admission' }}</h4>
  @include('components.alert-errors')

  <form method="POST" action="{{ $application 
      ? tenant_route('tenant.admissions.fromApp.store',['application'=>$application->id]) 
      : tenant_route('tenant.admissions.store') }}">
      @csrf
    @include('tenant.pages.admissions.form')
  </form>
</div>
@endsection
