@extends('superadmin.layouts.layout1')

@section('title', $pageTitle ?? "Super Admin")
@section('description', $pageDescription ?? "Super Admin")
@section('content')
  @yield('content')
  @stack('scripts')
  @stack('modals')
@endsection

