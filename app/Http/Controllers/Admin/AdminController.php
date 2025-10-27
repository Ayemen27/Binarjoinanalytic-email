<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Carbon\Carbon;
use Auth;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::where('id', '!=', Auth::guard('admin')->user()->id)->paginate(25);
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(StoreAdminRequest $request, ImageService $imageService)
    {

        $admin = Admin::create([
            "firstname"         => $request->firstname,
            "lastname"          => $request->lastname,
            "email"             => $request->email,
            "email_verified_at" => Carbon::now(),
            "password"          => \Hash::make($request->password),
        ]);

        if ($request->hasFile('avatar')) {
            $file = $imageService->storeAvatar($request->file('avatar'));  // Use the FileService
            $admin->update(['avatar' => $file['filename']]);
        } else {
            $admin->update(['avatar' => config('lobage.default_avatar')]);
        }

        showToastr(__('lobage.toastr.success'));
        return redirect(route('admin.admins.index'));
    }


    public function edit(Admin $admin)
    {
        if ($admin->id == Auth::guard('admin')->user()->id) {
            return redirect(route('admin.settings.profile'));
        }
        return view('admin.admins.edit')->with('admin', $admin);
    }

    public function update(UpdateAdminRequest $request, Admin $admin, ImageService $imageService)
    {

        $admin->update([
            $admin->firstname = $request->firstname,
            $admin->lastname  = $request->lastname,
            $admin->email     = $request->email,
        ]);

        if ($request->password != null) {
            $admin->update([
                "password" => \Hash::make($request->password)
            ]);
        }

        if ($request->hasFile('avatar')) {
            $file = $imageService->updateAvatar($request->file('avatar'), $admin->avatar);  // Use the FileService
            $admin->update(['avatar' => $file['filename']]);
        }

        showToastr(__('lobage.toastr.update'));
        return redirect(route('admin.admins.index'));
    }

    public function destroy(Admin $admin)
    {
        if ($admin->id == Auth::guard('admin')->user()->id) {
            showToastr(__('Administrator cannot be deleted'), 'error');
            return redirect(route('admin.admins.index'));
        }

        if ($admin->avatar != config('lobage.default_avatar')) {
            removeFileOrFolder($admin->avatar);
        }

        $admin->delete();
        showToastr(__('lobage.toastr.delete'));
        return back();
    }
}
