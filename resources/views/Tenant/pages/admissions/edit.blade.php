@extends('tenant.layouts.layout1')
@section('title','Edit Admission')
@section('content')
<div class="container-fluid">
  <h2>Edit Admission</h2>
  <form method="POST" action="{{ tenant_route('tenant.admissions.update',$admission->id) }}">
    @csrf @method('PUT')
    <x-alert-errors />
    @include('tenant.pages.admissions._form')
  </form>
</div>
@endsection
