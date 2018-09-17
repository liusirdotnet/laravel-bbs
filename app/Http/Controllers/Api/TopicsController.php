<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Models\User;
use App\Transformers\TopicTransformer;
use Illuminate\Http\Request;

class TopicsController extends ApiController
{
    /**
     * 话题列表。
     *
     * @param \App\Http\Requests\Api\TopicRequest $request
     * @param \App\Models\Topic $topic
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request, Topic $topic)
    {
        $query = $topic->query();

        if ($categoryId = $request->category_id) {
            $query->where('category_id', $categoryId);
        }

        switch ($request->order) {
            case 'recent':
                $query->createDesc();
                break;
            default:
                $query->updateDesc();
                break;
        }
        $topics = $query->paginate(20);

        return $this->response->paginator($topics, new TopicTransformer());
    }

    /**
     * 用户话题列表。
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     *
     * @return \Dingo\Api\Http\Reponse
     */
    public function userIndex(Request $request, User $user)
    {
        $topics = $user->topics()->createDesc()->paginate(20);

        return $this->response->paginator($topics, new TopicTransformer());
    }

    public function show(Topic $topic)
    {
        return $this->response->item($topic, new TopicTransformer());
    }

    /**
     * 创建话题。
     *
     * @param \App\Http\Requests\Api\TopicRequest $request
     * @param \App\Models\Topic $topic
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $this->user()->id;
        $topic->save();

        return $this->response->item($topic, new TopicTransformer())
            ->setStatusCode(201);
    }

    /**
     * 更新话题。
     *
     * @param \App\Http\Requests\Api\TopicRequest $request
     * @param \App\Models\Topic $topic
     *
     * @return \Dingo\Api\Http\Response
     */
    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());

        return $this->response->item($topic, new TopicTransformer());
    }

    /**
     * 删除话题。
     *
     * @param \App\Models\Topic $topic
     *
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return $this->response->noContent();
    }
}
