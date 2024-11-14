<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1> Accout Registration </h1>

    <form method="POST" action="/register">
        @csrf

        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12 ">
                <h2 class="text-base/7 font-semibold text-gray-900">Register</h2>
                <p class="mt-1 text-sm/6 text-gray-600">This information will be displayed publicly so be careful what
                    you
                    share.</p>

                <!-- name  -->
                <x-form-field>
                    <div class="sm:col-span-4">
                        <x-form-label for="name"> Name </x-form-label>
                        <x-form-input type="text" name="name" id="name" />
                        <x-form-error name="name" />
                    </div>
                </x-form-field>
                <x-form-field>
                    <div class="sm:col-span-4">
                        <x-form-label for="w_id"> Window ID </x-form-label>
                        <x-form-input type="text" name="w_id" id="w_id" />
                        <x-form-error name="w_id" />
                    </div>
                </x-form-field>
                <x-form-field>
                    <div class="sm:col-span-4">
                        <x-form-label for="department"> Department </x-form-label>
                        <x-form-input type="text" name="department" id="department" />
                        <x-form-error name="department" />
                    </div>
                </x-form-field>
                <x-form-field>
                    <div class="sm:col-span-4">
                        <x-form-label for="email"> Email </x-form-label>
                        <x-form-input type="email" name="email" id="email" placeholder="email@example.com" />
                        <x-form-error name="email" />
                    </div>
                </x-form-field>
                <x-form-field>
                    <div class="sm:col-span-4">
                        <x-form-label for="password"> Password </x-form-label>
                        <x-form-input type="password" name="password" id="password" />
                        <x-form-error name="password" />
                    </div>
                </x-form-field>
                <x-form-field>
                    <div class="sm:col-span-4">
                        <x-form-label for="password_confirmation"> Confirm Password </x-form-label>
                        <x-form-input type="password" name="password_confirmation" id="password_confirmation" />
                        <x-form-error name="password_confirmation" />
                    </div>
                </x-form-field>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" class="text-sm/6 font-semibold text-gray-900">Cancel</button>
            <x-form-button>Register</x-form-button>
        </div>
    </form>


</body>

</html>
