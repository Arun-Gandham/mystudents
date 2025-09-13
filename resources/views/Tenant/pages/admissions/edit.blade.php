@extends('tenant.layouts.layout1')
@section('title','Edit Admission')

@section('content')
<div class="container-fluid py-3">
  <h4>Edit Admission</h4>
  @include('components.alert-errors')

  <form method="POST" action="{{ tenant_route('tenant.admissions.update',['admission'=>$admission->id]) }}">
    @csrf @method('PUT')
    @include('tenant.pages.admissions.form')
  </form>
</div>
@endsection
