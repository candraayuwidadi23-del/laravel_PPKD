@extends('app')
@section('content')
<div class="table table-responsive">
  <div align="right" class="mb-2">
    <a href="{{ route('category.create') }}" class="btn btn-primary btn-sm">Create Category</a>
  </div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($categories as $index => $v)
        <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $v->name}}</td>
        <td>
          <a href="{{ route('category.edit', $v->id) }}" class="btn btn-success btn-sm">Edit</a>
          <form action="{{ route('category.destroy', $v->id) }}" method="post" class="d-inline">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Yakin di hapus?')">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
      
    </tbody>
  </table>

@endsection