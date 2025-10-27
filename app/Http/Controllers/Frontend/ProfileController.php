<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
use App\Http\Requests\Frontend\UpdateUserRequest;

class ProfileController extends Controller
{

    public function index()
    {
        setMetaTags(translate('Profile', 'seo'));
        $user = Auth::user();
        return view('frontend.user.profile')->with('user', $user);
    }

    public function update(UpdateUserRequest $request, ImageService $imageService)
    {
        $user = Auth::user();

        $user->update([
            $user->firstname = $request->firstname,
            $user->lastname = $request->lastname,
            $user->email = $request->email,
        ]);

        if ($request->hasFile('avatar')) {
            $file = $imageService->updateAvatar($request->file('avatar'), $user->avatar);  // Use the FileService
            $user->update(['avatar' => $file['filename']]);
        }

        showToastr(translate('Your profile has been updated successfully!', 'alerts'));
        return back();
    }


    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            showToastr(translate('Current password does not match!', 'alerts'), 'error');
            return back()->withErrors(['current_password' => translate('Current password does not match!', 'alerts')]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        showToastr(translate('Your Password has been updated successfully!', 'alerts'));
        return back();
    }
}
