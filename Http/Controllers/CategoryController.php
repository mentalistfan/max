<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Article;
use App\News;
use Illuminate\Pagination\Paginator;
use Meta;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function articles()
    {
        Meta::meta('title', 'Полезные статьи');
        Meta::meta('description', 'Описание полезные статьи');
        Meta::meta('image', asset('/images/logo.png'));
        $articles = Article::paginate(6);
        return view('front.articles', ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function news()
    {
        Meta::meta('title', 'Новости');
        Meta::meta('description', 'Описание новости');
        Meta::meta('image', asset('/images/logo.png'));
        $news = News::paginate(6);
        return view('front.news', ['news' => $news]);
    }

    public function article($slug)
    {
        $article = Article::findBySlug($slug);
        Meta::meta('title', $article->seotitle);
        Meta::meta('description', $article->seodescription);
        Meta::meta('image', asset('/images/logo.png'));
        $n = (int)$article->see;
        $article->see = $n + 1;
        $article->save();

        $previous = Article::where('id', '<', $article->id)->max('id');
        $nextious = Article::where('id', '>', $article->id)->min('id');
        $prev = Article::find($previous);
        $next = Article::find($nextious);

        return view('front.article', ['article' => $article, 'prev' => $prev, 'next' => $next]);
    }

    public function singlenews($slug)
    {
        $news = News::findBySlug($slug);
        Meta::meta('title', $news->seotitle);
        Meta::meta('description', $news->seodescription);
        Meta::meta('image', asset('/images/logo.png'));
        $n = (int)$news->see;
        $news->see = $n + 1;
        $news->save();

        $previous = News::where('id', '<', $news->id)->max('id');
        $nextious = News::where('id', '>', $news->id)->min('id');
        $prev = News::find($previous);
        $next = News::find($nextious);

        return view('front.singlenews', ['news' => $news, 'prev' => $prev, 'next' => $next]);
    }

}
