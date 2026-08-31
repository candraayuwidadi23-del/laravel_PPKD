<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - Point Of Sales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="login-wrapper mt-5">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-6">
                    <div class="card shadow-lg rounded-4">
                        <div class="card-body p-4 p-md-5">
                            <h3 class="text-center mb-5">Login - Point Of Sales</h3>

                            <form action="{{ route('actionLogin') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="" class="form-label" fw-semibold>Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" placeholder="Enter your email" required
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label" fw-semibold>Password</label>
                                    <input type="password" class="form-control  @error('password') is-invalid @enderror"
                                        name="password" placeholder="Enter your password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @guest
                                    <button type="submit" class="btn btn-primary w-100 fw-semibold">Login</button>
                                @else
                                    @php
                                        $dashboardUrl = match (Auth::user()->role_id) {
                                            1 => url('admin/dashboard'),
                                            2 => url('cashier/dashboard'),
                                            default => url('dashboard'),
                                        };
                                    @endphp
                                    <a href="{{ $dashboardUrl }}" class="btn btn-success w-100 fw-semibold">Dashboard</a>
                                @endguest

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
