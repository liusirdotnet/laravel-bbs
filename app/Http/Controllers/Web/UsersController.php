<?php

namespace App\Http\Controllers\Web;

use App\Handlers\ImageHandler;
use App\Http\Requests\Web\UserFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'show']);
    }

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
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Request $request, User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    /**
     * 用户信息更新操作。
     *
     * @param \App\Http\Requests\Web\UserFormRequest $request
     * @param \App\Models\User                       $user
     * @param \App\Handlers\ImageHandler             $handler
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(
        UserFormRequest $request,
        User $user,
        ImageHandler $handler
    ) {
        $this->authorize('update', $user);
        $data = $request->all();

        if ($request->avatar) {
            $result = $handler->upload($request->avatar, 'avatars', $user->id, 365);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }
        $user->update($data);

        return redirect()->route('users.show', $user->id)
                         ->with('success', '个人资料更新成功！');
    }

}
