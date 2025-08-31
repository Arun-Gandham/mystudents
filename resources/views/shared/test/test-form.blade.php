@extends('superadmin.baselayout') {{-- or your own layout that loads Bootstrap via Vite --}}

@section('content')
  <h1 class="mb-3">Dynamic Form Demo (All Fields)</h1>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @if(session('posted'))
      <pre class="bg-light p-3 border rounded">{{ json_encode(session('posted'), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</pre>
    @endif
  @endif

  <!-- <x-dynamic-form
    id="testForm"
    :action="route('test.store')"
    method="POST"
    :model="null"
    :schema="$formSchema"
    :data="$formData"
    confirmSubmit="true"
    unsavedGuard="true"
  /> -->
  <x-dynamic-form
  id="testForm"
  :action="route('test.store')"
  method="POST"
  :schema="$formSchema"
  :data="$formData"
  confirmSubmit="true"
  unsavedGuard="true"
/>
@endsection
