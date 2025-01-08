<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="css/login.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="login-body">
        <img class="login-bg" src="../img/login-queue.svg" alt="">

        <form class="login-form" method="POST" action="/login">
            @csrf
            <div class="login-title">
                <h1 class="login-lu">Laguna University</h1>
                <h1 class="login-qms"> Queue Management <br>System</h1>
            </div>

            {{-- <div class="login-input">
                <input type="text" name="username2" id="username2"
                    value="{{ old('username2') }}" placeholder="Username" required
                    class="@error('username2') is-invalid @enderror">

                <input type="password" name="password" id="password" placeholder="Password" required
                    class="@error('password') is-invalid @enderror">

                <button type="submit">Login</button>
                <a href="#">Forgot Password?</a>
            </div> --}}

            <div class="login-input">
                <div class="username-input">
                    <input type="text"
                        name="username2"
                        id="username2"
                        value="{{ old('username2') }}"
                        placeholder="Username"
                        required
                        class="form-control @error('username2') is-invalid @enderror">
                    @error('username2')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="password-input">
                    <input type="password"
                        name="password"
                        id="password"
                        placeholder="Password"
                        required
                        class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit">Login</button>
                <a href="#">Forgot Password?</a>
            </div>


        </form>

        <img class="blob1" src="../img/blob1.svg" alt="">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </div>


</body>

</html>
