<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Web\UserFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * 用户信息展示页。
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * 用户信息编辑页。
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * 用户信息更新操作。
     *
     * @param \App\Http\Requests\Web\UserFormRequest $request
     * @param \App\Models\User                       $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserFormRequest $request, User $user)
    {
        $user->update($request->all());

        return redirect()->route('users.show', $user->id)
                         ->with('success', '个人资料更新成功！');
    }

}
