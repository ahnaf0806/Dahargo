<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required','string','max:100'],
            'email' => [
                'required','email','max:150',
                Rule::unique('users','email')->ignore($user->id)
            ],
            'password' => ['nullable','string','min:6','confirmed'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        $user->save();

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
