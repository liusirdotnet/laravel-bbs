<?php

namespace App\Http\Controllers\Web;

use App\Handlers\ImageHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\TopicFormRequest;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller
{
    /**
     * TopicsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * 话题列表页。
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Topic        $topic
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, Topic $topic)
    {
        $topics = $topic->withOrder($request->order)->paginate(20);

        return view('web.topics.index', compact('topics'));
    }

    /**
     * 话题详情页。
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Topic        $topic
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, Topic $topic)
    {
        if (! empty($topic->slug) && $topic->slug !== $request->slug) {
            return redirect($topic->link(), 301);
        }

        return view('web.topics.show', compact('topic'));
    }

    /**
     * 话题创建页。
     *
     * @param \App\Models\Topic $topic
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Topic $topic)
    {
        $categories = Category::all();

        return view('web.topics.topic', compact('topic', 'categories'));
    }

    /**
     * 话题创建操作。
     *
     * @param \App\Http\Requests\Web\TopicFormRequest $request
     * @param \App\Models\Topic                       $topic
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TopicFormRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();

        return redirect()
            ->to($topic->link())
            ->with('message', '创建成功。');
    }

    /**
     * 话题编辑页。
     *
     * @param \App\Models\Topic $topic
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Topic $topic)
    {
        $this->authorize('update', $topic);
        $categories = Category::all();

        return view('web.topics.topic', compact('topic', 'categories'));
    }

    /**
     * 话题更新操作。
     *
     * @param \App\Http\Requests\Web\TopicFormRequest $request
     * @param \App\Models\Topic                       $topic
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(TopicFormRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());

        return redirect()
            ->to($topic->link())
            ->with('message', '更新成功。');
    }

    /**
     * 话题删除操作。
     *
     * @param \App\Models\Topic $topic
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return redirect()
            ->route('topics.index')
            ->with('success', '删除成功。');
    }

    /**
     * 话题相关图片上传（编辑器中上传图片）。
     *
     * @param \Illuminate\Http\Request   $request
     * @param \App\Handlers\ImageHandler $handler
     *
     * @return array
     */
    public function upload(Request $request, ImageHandler $handler)
    {
        $data = [
            'status' => false,
            'msg' => '上传失败！',
            'path' => '',
        ];

        if ($uploader = $request->uploader) {
            $result = $handler->upload($uploader, 'topics', Auth::id());

            if ($result) {
                $data['status'] = true;
                $data['msg'] = '上传成功。';
                $data['path'] = $result['path'];
            }
        }

        return $data;
    }
}
