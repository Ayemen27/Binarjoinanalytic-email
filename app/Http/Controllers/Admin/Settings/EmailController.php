<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Models\Seo;
use App\Models\EmailTemplate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateEmailRequest;

class EmailController extends Controller
{

    public function index()
    {
        return view('admin.settings.emails.index')->with('emails', EmailTemplate::all());
    }



    public function edit(EmailTemplate $email)
    {
        return view('admin.settings.emails.edit')->with("email", $email);
    }

    public function update(UpdateEmailRequest $request, EmailTemplate $email)
    {
        $validatedPostData = $request->validated();
        $email->update($validatedPostData);
        showToastr(__('lobage.toastr.update'));
        return back();
    }
}
