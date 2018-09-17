<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Transformers\TopicTransformer;

class TopicsController extends ApiController
{
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
}
