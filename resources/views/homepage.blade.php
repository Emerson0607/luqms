<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>


<body>

    @guest
        <div> <x-nav-link href="/login" :active="request()->is('/login')">Log In</x-nav-link>
            <x-nav-link href="/register" :active="request()->is('/register')">Register</x-nav-link>
        </div>
    @endguest

    @auth
        <form method="POST" action="/logout">
            @csrf

            <x-form-button>Log Out</x-form-button>
        </form>
    @endauth

    login success authentication
</body>

</html>
