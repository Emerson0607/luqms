<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>login</h1>
    <form method="POST" action="/login">
        @csrf

        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12 ">
                <x-form-field>
                    {{-- <div class="sm:col-span-4">
                        <x-form-label for="email"> Email </x-form-label>
                        <x-form-input type="email" name="email" id="email" :value="old('email')"
                            placeholder="email@example.com" />
                        <x-form-error name="email" />
                    </div> --}}
                    <div class="sm:col-span-4">
                        <x-form-label for="username2"> Username </x-form-label>
                        <x-form-input type="text" name="username2" id="username2" :value="old('username2')"
                            placeholder="Enter username" />
                        <x-form-error name="username2" />
                    </div>
                </x-form-field>
                <x-form-field>
                    <div class="sm:col-span-4">
                        <x-form-label for="password"> Password </x-form-label>
                        <x-form-input type="password" name="password" id="password" />
                        <x-form-error name="password" />
                    </div>
                </x-form-field>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" class="text-sm/6 font-semibold text-gray-900">Cancel</button>
            <x-form-button>Log In</x-form-button>
        </div>
    </form>

</body>

</html>
