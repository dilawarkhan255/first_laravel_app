<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User;

class UserController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if(request()->hasfile('image')){
            $imageName = time().'.'.request()->image->extension();
            request()->image->move(public_path('images'), $imageName);

            $user = Auth::user();
            $user->profile_image = $imageName;
            /** @var \App\Models\User $user **/
            $user->save();

            return back()->with('success', 'You have successfully uploaded the image.');
        }
        return back()->with('error', 'Image upload failed.');
    }
}
