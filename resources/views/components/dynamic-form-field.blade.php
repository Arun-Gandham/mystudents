@props(['field'=>[],'val'=>null,'resolveOptions'=>null,'model'=>null])

@php

  $name  = $field['name'] ?? null;
  $type  = $field['type'] ?? 'text';
  $label = $field['label'] ?? ucfirst(str_replace('_',' ', (string)$name));
  $placeholder = $field['placeholder'] ?? '';
  $help  = $field['help'] ?? null;
  $col   = $field['col'] ?? 'col-md-6';
  $attrs = $field['attrs'] ?? [];
  $req   = !empty($field['required']);
  $options = $resolveOptions ? $resolveOptions($field) : [];
  $value = $val ? $val($name, $field['default'] ?? null) : null;

  // mark trackable fields for unsaved-changes guard
  if (!empty($field['track'])) $attrs['data-track'] = 'true';
  if ($req) $attrs['required'] = true;
  $attrHtml = '';
  foreach ($attrs as $k=>$v) $attrHtml .= ' '.e($k).'="'.e($v).'"';
@endphp

<div class="{{ $col }}">
  @if($type!=='switch' && $type!=='checkbox')
    <label class="form-label" @if($name) for="{{ $name }}" @endif>
      {{ $label }} @if($req)<span class="text-danger">*</span>@endif
    </label>
  @endif

  @switch($type)
    @case('textarea')
      <textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $field['rows'] ?? 4 }}"
        class="form-control @error($name) is-invalid @enderror"
        placeholder="{{ $placeholder }}" {!! $attrHtml !!}>{{ $value }}</textarea>
    @break

    @case('select')
      <select name="{{ $name }}" id="{{ $name }}" class="form-select @error($name) is-invalid @enderror" {!! $attrHtml !!}>
        <option value="">{{ $field['placeholder'] ?? '-- Select --' }}</option>
        @foreach($options as $optVal => $optLabel)
          <option value="{{ $optVal }}" @selected($value == $optVal)>{{ $optLabel }}</option>
        @endforeach
      </select>
    @break

    @case('multiselect')
      @php $vals = (array)($value ?? []); @endphp
      <select name="{{ $name }}[]" id="{{ $name }}" multiple size="{{ $field['size'] ?? 4 }}"
        class="form-select @error($name) is-invalid @enderror" {!! $attrHtml !!}>
        @foreach($options as $optVal => $optLabel)
          <option value="{{ $optVal }}" @selected(in_array($optVal, $vals, true))>{{ $optLabel }}</option>
        @endforeach
      </select>
    @break

    @case('checkbox_group')
      @php $vals = (array)($value ?? []); @endphp
      <div class="d-flex flex-wrap gap-3">
        @foreach($options as $optVal => $optLabel)
          <div class="form-check">
            <input class="form-check-input @error($name) is-invalid @enderror" type="checkbox"
              id="{{ $name.'_'.$optVal }}" name="{{ $name }}[]" value="{{ $optVal }}"
              @checked(in_array($optVal, $vals, true)) {!! $attrHtml !!}>
            <label class="form-check-label" for="{{ $name.'_'.$optVal }}">{{ $optLabel }}</label>
          </div>
        @endforeach
      </div>
    @break

    @case('color')
      @php $colorValue = $value ?: '#000000'; @endphp
      <input type="color" name="{{ $name }}" id="{{ $name }}"
            value="{{ $colorValue }}"
            class="form-control form-control-color @error($name) is-invalid @enderror" {!! $attrHtml !!}>
    @break

    @case('radio')
      <div>
        @foreach($options as $optVal => $optLabel)
          <div class="form-check form-check-inline">
            <input class="form-check-input @error($name) is-invalid @enderror" type="radio"
              id="{{ $name.'_'.$optVal }}" name="{{ $name }}" value="{{ $optVal }}"
              @checked($value == $optVal) {!! $attrHtml !!}>
            <label class="form-check-label" for="{{ $name.'_'.$optVal }}">{{ $optLabel }}</label>
          </div>
        @endforeach
      </div>
    @break

    @case('file')
  <input type="file" name="{{ $name }}" id="{{ $name }}"
         class="form-control @error($name) is-invalid @enderror" {!! $attrHtml !!}>

  @if(!empty($field['current_url']) && filled($field['current_url']))
    <div class="form-text mt-2">
      Current: <a href="{{ $field['current_url'] }}" target="_blank" rel="noopener">View</a>
    </div>
  @endif

  @if(!empty($field['preview']))
    @php
      $previewId = ltrim($field['preview'], '#');
    @endphp
    {{-- hidden until a valid image is selected --}}
    <div id="{{ $previewId }}_wrap" class="mt-2 d-none">
      <img id="{{ $previewId }}" alt="Logo preview" class="img-thumbnail" style="max-height:120px;" loading="lazy">
    </div>

    <script>
      (function(){
        const inp  = document.getElementById(@json($name));
        const wrap = document.getElementById(@json($previewId . '_wrap'));
        const img  = document.getElementById(@json($previewId));
        if (!inp || !wrap || !img) return;

        function hide(){
          img.removeAttribute('src');
          wrap.classList.add('d-none');
        }
        function show(file){
          if (!file || !file.type || !file.type.startsWith('image/')) { hide(); return; }
          const url = URL.createObjectURL(file);
          img.src = url;
          wrap.classList.remove('d-none');
          img.onload = () => URL.revokeObjectURL(url);
        }

        // initial state: hidden
        hide();

        inp.addEventListener('change', ()=>{
          const f = inp.files && inp.files[0];
          if (f) show(f); else hide();
        });
      })();
    </script>
  @endif
@break


    @case('switch')
      <div class="form-check form-switch">
        <input class="form-check-input @error($name) is-invalid @enderror" type="checkbox" id="{{ $name }}" name="{{ $name }}" value="1" @checked((bool)$value) {!! $attrHtml !!}>
        <label class="form-check-label" for="{{ $name }}">{{ $label }}</label>
      </div>
    @break

    @case('checkbox')
      <div class="form-check">
        <input class="form-check-input @error($name) is-invalid @enderror" type="checkbox" id="{{ $name }}" name="{{ $name }}" value="1" @checked((bool)$value) {!! $attrHtml !!}>
        <label class="form-check-label" for="{{ $name }}">{{ $label }}</label>
      </div>
    @break

    @default
      <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
        value="{{ $value }}" placeholder="{{ $placeholder }}"
        class="form-control @error($name) is-invalid @enderror" {!! $attrHtml !!}>
  @endswitch

  @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
  @if($help)<div class="form-text">{{ $help }}</div>@endif
</div>
