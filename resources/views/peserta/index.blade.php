@extends('app')
@section('content')
   <div align="right" class="mb-3"> 
             <a href="{{ route('create') }}" class="btn btn-primary">Tambah Peserta</a>
           </div>
           <table class="table table-bordered">
             <thead>
               <tr>
                 <th>No</th>
                 <th>Nama</th>
                 <th>Umur</th>
                 <th>Aksi</th>
               </tr>
             </thead>
             <tbody>
               @foreach ($pesertas as $index => $value)
               <tr>
                 <td>{{ $index += 1 }}</td>
                 <td>{{ $value->name }}</td>
                 <td>{{ $value->age }}</td>
                 <td><a href="{{ route('edit.peserta', $value->id) }}" class="btn btn-success btn-sm">Edit</a> 
                  <form action="{{ route('delete.peserta', $value->id) }}" method="post" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Yakin di hapus?')">Delete</button
                  </form>
                </td>
               </tr>
               @endforeach
             </tbody>
           </table>

@endsection