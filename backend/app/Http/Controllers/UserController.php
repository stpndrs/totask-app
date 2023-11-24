<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function update(Request $request)
    {
        // return $request->all();
        $user = $request->user();

        // $validate = Validator::make($request->all(), [
        //     'name' => 'required',
        //     'email' => 'required|email'
        // ]);

        // if ($validate->fails()) {
        //     return $this->failed(['message' => 'Validation Error', 'errors' => $validate->errors()]);
        // }

        // $password = $user->password;
        // if ($request->password) {
        //     $password = bcrypt($request->password);
        // }

        return $request->all();
        $pp = null;
        if ($request->file('profile_image')) {
            $p = $request->file('profile_image');
            $pp = $p->getClientOriginalName();
            $p->storeAs('public/pp', $pp);
            if ($user->profile_image) {
                Storage::delete('storage/pp/' . $user->profile_image);
            }
        }

        $user->update([
            // 'name' => $request->name,
            // 'email' => $request->email,
            // 'password' => $password,
            'profile_image' => $pp,
        ]);

        return $this->success(['message' => 'Update profile success', 'data' => $user]);
    }

    // public function update(Request $request)
    // {
    //     $user = $request->user();

    //     $validate = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'email' => 'required|email'
    //     ]);

    //     if ($validate->fails()) {
    //         return $this->failed(['message' => 'Validation Error', 'errors' => $validate->errors()]);
    //     }

    //     $dataToUpdate = [
    //         'name' => $request->name,
    //         'email' => $request->email,
    //     ];

    //     // Cek apakah ada kata sandi baru yang dikirimkan
    //     if ($request->has('password')) {
    //         $dataToUpdate['password'] = bcrypt($request->password);
    //     }

    //     // Cek apakah ada file gambar profil yang dikirimkan
    //     if ($request->hasFile('profile_image')) {
    //         $profileImage = $request->file('profile_image');
    //         $pp = $profileImage->getClientOriginalName();
    //         $profileImage->storeAs('public/pp', $pp);

    //         // Hapus gambar profil lama jika ada
    //         if ($user->profile_image) {
    //             Storage::delete('public/pp/' . $user->profile_image);
    //         }

    //         $dataToUpdate['profile_image'] = $pp;
    //     }

    //     $user->update($dataToUpdate);

    //     return $this->success(['message' => 'Update profile success', 'data' => $user]);
    // }
}
