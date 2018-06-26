<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Web\ReplyFormRequest;
use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RepliesController extends Controller
{
    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 话题回复创建操作。
     *
     * @param \App\Http\Requests\Web\ReplyFormRequest $request
     * @param \App\Models\Reply                       $reply
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReplyFormRequest $request, Reply $reply)
    {
        $attribute = 'content';
        $reply->content = $request->{$attribute};
        $reply->user_id = Auth::id();
        $reply->topic_id = $request->topic_id;
        $reply->save();

        return redirect()
            ->to($reply->topic->link())
            ->with('message', '创建成功。');
    }

    /**
     * 话题回复删除操作。
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Reply        $reply
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request, Reply $reply)
    {
        $this->authorize('destroy', $reply);
        $reply->delete();

        return redirect()
            ->route('replies.index')
            ->with('message', '删除成功！');
    }
}
