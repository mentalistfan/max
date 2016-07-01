<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Meta;
use App\Subscriber;
use App\Mfo;
use DB;
use App\Comment;
use App\Article;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        $mfo = Mfo::findBySlug($slug);
        Meta::meta('title', $mfo->seotitle);
        Meta::meta('description', $mfo->seodescription);
        Meta::meta('image', asset('/images/logo.png'));
        $n = (int)$mfo->see;
        $mfo->see = $n + 1;
        $mfo->save();
        $random = Mfo::all()->random(3);

        return view('front.company', ['mfo' => $mfo, 'random' => $random]);
    }

    public function comments($slug)
    {
        $mfo = Mfo::findBySlug($slug);
        Meta::meta('title', $mfo->seotitle);
        Meta::meta('description', $mfo->seodescription);
        Meta::meta('image', asset('/images/logo.png'));
        $n = (int)$mfo->see;
        $mfo->see = $n + 1;
        $mfo->save();

        return view('front.companycomments', ['mfo' => $mfo]);
    }

    public function addcomment(Request $request)
    {
        Comment::create([
            'author' => $request->name,
            'email' => $request->email,
            'text' => $request->text,
            'rating' => $request->rating,
            'mfo_id' => $request->mfo,
            'show' => '0',
            'data' => date("Y-m-d H:i:s")
        ]);

        return response()->json(['text' => 'Вы успешно добавили комментарий']);
    }

    public function news($slug)
    {
        $mfo = Mfo::findBySlug($slug);
        Meta::meta('title', $mfo->seotitle);
        Meta::meta('description', $mfo->seodescription);
        Meta::meta('image', asset('/images/logo.png'));

        return view('front.companynews', ['mfo' => $mfo]);
    }

    public function voice(Request $request)
    {
        $comment = Comment::find($request->id);
        if($request->type == 'yes'){
            $n = (int)$comment->plus;
            $n = $n + 1;
            $comment->plus = $n;
            $comment->save();
        }
        else{
            $n = (int)$comment->minus;
            $n = $n - 1;
            $comment->plus = $n;
            $comment->save();
        }
        return response()->json(['voice' => $n]);
    }

    public function reviews()
    {
        $comments = Comment::all();
        return view('front.reviews', ['comments' => $comments]);
    }
}
