@extends('app')
@section('content')
<form action="{{ route('store-peserta') }}" method="post">
      @csrf
      <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama">
      </div>
      <div class="mb-3">
        <label for="umur" class="form-label">Umur</label>
        <input type="number" class="form-control" name="umur" placeholder="Masukkan Umur">
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" placeholder="Masukkan Email">
      </div>
      <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <textarea class="form-control" name="address" id="" cols="30" rows="5"></textarea>
      </div>
      <div class="mb-3">
      <button class="btn btn-primary" type="submit">Simpan</button>
</form>
@endsection

