<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <form action="{{route('action-bagi')}}" method="post">
    @csrf
    <label for="angka1">Angka 1:</label>
    <input type="number" name="angka1"><br>
    <label for="angka2">Angka 2:</label>
    <input type="number" name="angka2"><br>
    <button type="submit">Hitung</button>
  </form>
  <a href="{{ url()->previous() }}">Back</a>
  <h3>Hasilnya: {{ isset($hasil) ? $hasil : '0' }}</h3>
</body>
</html>