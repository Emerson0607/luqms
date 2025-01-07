<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Login</h1>
        <form method="POST" action="/login">
            @csrf

            <div class="row mb-4">
                <!-- Username Field -->
                <div class="col-md-6">
                    <label for="username2" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username2" id="username2"
                        value="{{ old('username2') }}" placeholder="Enter username">
                    <x-form-error name="username2" />
                </div>
            </div>

            <div class="row mb-4">
                <!-- Password Field -->
                <div class="col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                    <x-form-error name="password" />
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary me-2">Cancel</button>
                    <x-form-button class="btn btn-primary">Log In</x-form-button>
                </div>
            </div>
        </form>
    </div>

    <!-- Include Bootstrap JS (optional, for components like modal, tooltip, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
