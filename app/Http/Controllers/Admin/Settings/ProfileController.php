<?php

namespace App\Http\Controllers\Admin\Settings;

use Hash, Auth;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;

class ProfileController extends Controller
{


    public function index()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.settings.profile', compact('admin'));
    }


    public function update(UpdateProfileRequest $request, ImageService $imageService)
    {
        $admin = Auth::guard('admin')->user();

        $admin->update([
            $admin->firstname = $request->firstname,
            $admin->lastname = $request->lastname,
            $admin->email = $request->email,
        ]);

        if ($request->hasFile('avatar')) {
            $file = $imageService->updateAvatar($request->file('avatar'), $admin->avatar);  // Use the FileService
            $admin->update(['avatar' => $file['filename']]);
        }

        showToastr(__('lobage.toastr.update'));
        return back();
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $admin->password)) {

            showToastr(__('Current password does not match!'), 'error');
            return back()->with('error', 'Current password does not match!');
        }

        $admin->password = Hash::make($request->password);
        $admin->save();

        showToastr(__('Password successfully changed!'));
        return back();
    }
}
