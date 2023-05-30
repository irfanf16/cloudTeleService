<?php

namespace App\Actions\Admin\Auth;
use Illuminate\Support\Facades\Request;
use Lorisleiva\Actions\Concerns\AsAction;


class GetLoginAction
{
    use AsAction;

    public function asController(Request $request)
    {
//        User::create([
//           'name'=>'admin',
//           'email'=>'admin@gmail.com',
//           'password'=>Hash::make('123456')
//        ]);
        return view('admin.login.login');
    }
}
