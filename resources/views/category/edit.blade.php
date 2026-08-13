@extends('app')
@section('content')
<form action="{{ route('category.update', $edit->id) }}" method="post">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="" class="form-label">Name</label>
        <input type="text" class="form-control" name="name" value="{{ $edit->name }}">
    </div>

    <div class="mb-3">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="is_active" id="radioDefault1" {{ $edit->is_active == 1 ? 'checked' : '' }} value="1">
  <label class="form-check-label" for="radioDefault1">
    Active
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" name="is_active" id="radioDefault2" {{ $edit->is_active == 0 ? 'checked' : '' }} value="0">
  <label class="form-check-label" for="radioDefault2">
    Inactive
  </label>
</div>
    </div>  

    <div class="mb-3">
        <button class="btn btn-primary" type="submit">Simpan</button>
    </div>
@endsection