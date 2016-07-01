<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Answer;
use App\Article;
use App\News;
use App\Mfo;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $answers = Answer::all();
        return view('admin.answers.index', ['answers' => $answers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $articles = Article::all();
        $news = News::all();
        $mfos = Mfo::all();
        return view('admin.answers.create', ['articles' => $articles, 'news' => $news, 'mfos' => $mfos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'question' => 'required|max:255'
        ]);

        $newanswer = Answer::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'seodescription' => $request->seodescription,
            'seokeywords' => $request->seokeywords,
            'data' => $request->data,
            'seotitle' => $request->seotitle,
            'title' => $request->title
        ]);

        $articles = $request->article;
        foreach($articles as $article){
            DB::table('answers_articles')->insert(
                [
                    'answer_id' => $newanswer->id,
                    'article_id' => $article
                ]
            );
        }

        $newses = $request->news;
        foreach($newses as $news){
            DB::table('answers_newses')->insert(
                [
                    'answer_id' => $newanswer->id,
                    'news_id' => $news
                ]
            );
        }

        $mfos = $request->mfo;
        foreach($mfos as $mfo){
            DB::table('answers_mfos')->insert(
                [
                    'answer_id' => $newanswer->id,
                    'mfo_id' => $mfo
                ]
            );
        }

        return redirect('admin/answers')->with('message', 'Вопрос создан');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $answer = Answer::find($id);
        return view('admin.answers.show', ['answer' => $answer]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $answer = Answer::find($id);
        $articles = Article::all();
        $news = News::all();
        $mfos = Mfo::all();
        return view('admin.answers.edit', ['answer' => $answer, 'articles' => $articles, 'news' => $news, 'mfos' => $mfos]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'question' => 'required|max:255'
        ]);

        $answer = Answer::find($request->id);
        $answer->question = $request->question;
        $answer->answer = $request->answer;
        $answer->seodescription = $request->seodescription;
        $answer->seokeywords = $request->seokeywords;
        $answer->data = $request->data;
        $answer->seotitle = $request->seotitle;
        $answer->title = $request->title;
        $answer->save();

        //articles
        $articles = $request->article;
        $now = DB::table('answers_articles')->where('answer_id', $request->id)->lists('article_id');
        
        if(empty($articles)){
            DB::table('answers_articles')->where('answer_id', $request->id)->delete();
        }
        else{
            foreach($articles as $article){
                if(!in_array($article, $now)){
                    DB::table('answers_articles')->insert(
                        [
                            'answer_id' => $request->id,
                            'article_id' => $article
                        ]
                    );                
                }
            }

            foreach($now as $n){

                if(!in_array($n, $articles)){
                    DB::table('answers_articles')->where('article_id', $n)->delete();
                }    

            }
        }
        //newses
        $newses = $request->news;
        $now2 = DB::table('answers_newses')->where('answer_id', $request->id)->lists('news_id');
        if(empty($newses)){
            DB::table('answers_newses')->where('answer_id', $request->id)->delete();
        }
        else{
            foreach($newses as $news){
                if(!in_array($news, $now2)){
                    DB::table('answers_newses')->insert(
                        [
                            'answer_id' => $request->id,
                            'news_id' => $news
                        ]
                    );                
                }
            }

            foreach($now2 as $n){

                if(!in_array($n, $newses)){
                    DB::table('answers_newses')->where('news_id', $n)->delete();
                }    

            }
        }

        //mfos
        $mfos = $request->mfo;
        $now3 = DB::table('answers_mfos')->where('answer_id', $request->id)->lists('mfo_id');
        if(empty($mfos)){
            DB::table('answers_mfos')->where('answer_id', $request->id)->delete();
        }
        else{
            foreach($mfos as $mfo){
                if(!in_array($mfo, $now3)){
                    DB::table('answers_mfos')->insert(
                        [
                            'answer_id' => $request->id,
                            'mfo_id' => $mfo
                        ]
                    );                
                }
            }

            foreach($now3 as $n){

                if(!in_array($n, $mfos)){
                    DB::table('answers_mfos')->where('mfo_id', $n)->delete();
                }    

            }
        }

        

        return redirect('admin/answers')->with('message', 'Вопрос отредактирован');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $answer = Answer::find($request->id);
        $answer->delete();
        return redirect('admin/answers')->with('message', 'Вопрос удален');
    }
}
