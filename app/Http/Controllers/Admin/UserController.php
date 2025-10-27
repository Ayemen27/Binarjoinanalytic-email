<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Services\ImageService;
use Lobage\Planify\Models\Plan;
use App\Services\UserFilterService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Filter\UserFilterRequest;


class UserController extends Controller
{

    protected $filterService;

    public function __construct(UserFilterService $filterService)
    {
        $this->filterService = $filterService;
    }


    public function index(UserFilterRequest $request)
    {


        $get_inactive_users = User::whereNull('email_verified_at')->count();
        $get_active_users = User::whereNotNull('email_verified_at')->where('status', 1)->count();
        $get_banned_users = User::where('status', 0)->get()->count();
        $get_all_users = User::all()->count();

        $users = $this->filterService->filterUsers($request);

        return view('admin.users.index', compact('users', 'get_all_users', 'get_inactive_users', 'get_active_users', 'get_banned_users'));
    }


    public function create()
    {
        return view('admin.users.create');
    }


    public function store(StoreUserRequest $request, ImageService $imageService)
    {

        $user = User::create([
            "firstname" => $request->firstname,
            "lastname" => $request->lastname,
            "email" => $request->email,
            "email_verified_at" => $request->email_status == 1 ? Carbon::now() : null,
            "status" => $request->account_status,
            "password" => \Hash::make($request->password),
        ]);

        if ($request->hasFile('avatar')) {
            $file = $imageService->storeAvatar($request->file('avatar'));  // Use the FileService
            $user->update(['avatar' => $file['filename']]);
        } else {
            $user->update(['avatar' => config('lobage.default_avatar')]);
        }

        $plan = Plan::where('tag', 'free')->first();
        $user->newSubscription(
            'main',
            $plan,
        );


        showToastr(__('lobage.toastr.success'));
        return redirect(route('admin.users.index'));
    }


    public function edit(User $user)
    {
        return view('admin.users.edit')->with('user', $user);
    }


    public function update(UpdateUserRequest $request, User $user, ImageService $imageService)
    {

        if ($user->email_verified_at) {
            $email_status = $user->email_verified_at;
        } else {
            $email_status = Carbon::now();
        }

        $user->update([
            $user->firstname = $request->firstname,
            $user->lastname = $request->lastname,
            $user->email = $request->email,
            $user->email_verified_at = $request->email_status == 1 ?  $email_status : null,
            $user->status = $request->account_status,
        ]);

        if ($request->password != null) {
            $user->update([
                "password" => \Hash::make($request->password)
            ]);
        }


        if ($request->hasFile('avatar')) {
            $file = $imageService->updateAvatar($request->file('avatar'), $user->avatar);  // Use the FileService
            $user->update(['avatar' => $file['filename']]);
        }

        showToastr(__('lobage.toastr.update'));
        return redirect(route('admin.users.index'));
    }


    public function destroy(User $user)
    {

        if ($user->avatar != config('lobage.default_avatar')) {
            removeFileOrFolder($user->avatar);
        }

        $user->subscription('main')->delete();

        $user->delete();


        showToastr(__('lobage.toastr.delete'));
        return back();
    }
}
