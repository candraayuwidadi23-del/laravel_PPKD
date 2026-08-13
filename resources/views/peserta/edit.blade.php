@extends('app')
@section('content')
<form action="{{ route('update.peserta', $peserta->id) }}" method="post">
      @csrf
      @method('PUT')
      {{-- @dd($peserta) --}}
      <div class="mb-3">
        <label for="" class="form-label">Nama</label>
        <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama" value="{{ $peserta->name }}">
      </div>
      <div class="mb-3">
        <label for="" class="form-label">Umur</label>
        <input type="number" class="form-control" name="umur" placeholder="Masukkan Umur" value="{{ $peserta->age }}">
      </div>
      <div class="mb-3">
        <label for="" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" placeholder="Masukkan Email" value="{{ $peserta->email }}">
      </div>
      <div class="mb-3">
        <label for="" class="form-label">Address</label>
        <textarea class="form-control" name="address" id="" cols="30" rows="5">{{ $peserta->address }}</textarea>
      </div>
      <div class="mb-3">
      <button class="btn btn-primary" type="submit">Simpan</button>
      </div>
</form>
@endsection

