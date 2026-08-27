@extends('app')
@section('content')
    <div class="table-responsive">
        <div align="right" class="mb-3">
            {{-- <a href="{{ route('create') }}" class="btn btn-primary">Tambah Peserta</a> --}}
            <a href="{{ route('order.create') }}" class="btn btn-primary">Create</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Photo</th>
                    <th>Name</th>
                    {{-- <th>Category Name</th> --}}
                    <th>Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $index => $v)
                        <tr>
                            <td>{{ $index += 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('storage/' . $v->photo) }}" alt="" width="100" height="100"
                                        style="object-fit: cover">
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $v->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $v->name }}</td>
                            {{-- <td>{{ $v->category->name }}</td> --}}
                            <td>Rp{{ number_format($v->price) }}</td>
                            <td>{{ $v->description }}</td>
                            <td>
                                <a href="{{ route('order.edit', $v->id) }}" class="btn btn-success btn-sm">Edit</a> |
                                <form action="{{ route('order.destroy', $v->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                @endforeach
        </table>
    </div>
@endsection
