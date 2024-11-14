<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;


class RegisteredUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.register');
    }

    public function store()
    {
        $validatedattributes = request()->validate([
            'name' => ['required'],
            'w_id' => ['required'],
            'department' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'] //password_confirmation

        ]);

        $user = User::create($validatedattributes);

        Auth::login($user);
        return redirect('/homepage');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
