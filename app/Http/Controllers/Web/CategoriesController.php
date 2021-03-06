<?php

namespace App\Http\Controllers\Web;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    /**
     * 分类详情页。
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Category     $category
     * @param \App\Models\Topic        $topic
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, Category $category, Topic $topic)
    {
        $topics = $topic->withOrder($request->order)
            ->where('category_id', $category->id)
            ->paginate(20);

        return view('web.topics.index', compact('topics', 'category'));
    }
}
