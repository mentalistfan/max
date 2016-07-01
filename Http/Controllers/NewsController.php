<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\News;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Orchestra\Imagine\Facade as Imagine;
use Illuminate\Support\Facades\Request as Req;
use App\Mfo;
use App\Answer;
use DB;
class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::all();
        return view('admin.news.index', ['news' => $news]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mfos = Mfo::all();
        $answers = Answer::all();
        return view('admin.news.create', ['mfo' => $mfos, 'answers' => $answers]);
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
            'title' => 'required|max:255'
        ]);

        $bannername = '';
        if(Req::hasFile('banner')){
            $extension = Req::file('banner')->getClientOriginalExtension(); // getting image extension
            $fileName = rand(11111,99999);
        
            Req::file('banner')->move('uploads/images', $fileName.'.'.$extension);
            create_thumbnail('uploads/images', $fileName, $extension);
            $bannername = $fileName.'.'.$extension;
        }

        $news = News::create([
            'title' => $request->title,
            'seodescription' => $request->seodescription,
            'seokeywords' => $request->seokeywords,
            'seotitle' => $request->seotitle,
            'intro' => $request->intro,
            'content' => $request->content,
            'banner' => $bannername,
            'data' => $request->data
        ]);

        $mfos = $request->mfo;
        if(!empty($mfos)){
            foreach($mfos as $mfo){
                DB::table('newses_mfos')->insert(
                    [
                        'news_id' => $news->id,
                        'mfo_id'     => $mfo
                    ]
                );
            }
        }
        $answers = $request->answer;
        foreach($answers as $ans){
               DB::table('answers_newses')->insert(
                [
                    'news_id' => $news->id,
                    'answer_id'     => $ans
                ]
            ); 
        }

        return redirect('/admin/news')->with('message', 'Новость создана'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $one = News::find($id);
        return view('admin.news.show', ['one' => $one]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $one = News::find($id);
        $mfos = Mfo::all();
        $answers = Answer::all();
        return view('admin.news.edit', ['one' => $one, 'mfos' => $mfos, 'answers' => $answers]);
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
            'title' => 'required|max:255'
        ]);

        $bannername = '';
        if(Req::hasFile('banner')){
            $extension = Req::file('banner')->getClientOriginalExtension(); // getting image extension
            $fileName = rand(11111,99999);
        
            Req::file('banner')->move('uploads/images', $fileName.'.'.$extension);
            create_thumbnail('uploads/images', $fileName, $extension);
            $bannername = $fileName.'.'.$extension;
        }

        $news = News::find($request->id);
        $news->title = $request->title;
        $news->seokeywords = $request->seokeywords;
        $news->seodescription = $request->seodescription;
        $news->seotitle = $request->seotitle;
        $news->intro = $request->intro;
        $news->content = $request->content;
        if(Req::hasFile('banner')){
            $news->banner = $bannername;
        }
        $news->data = $request->data;
        $news->save();

        //articles
        $mfos = $request->mfo;
        $now = DB::table('newses_mfos')->where('news_id', $request->id)->lists('mfo_id');
        
        if(empty($mfos)){
            DB::table('newses_mfos')->where('news_id', $request->id)->delete();
        }
        else{
            foreach($mfos as $mfo){
                if(!in_array($mfo, $now)){
                    DB::table('newses_mfos')->insert(
                        [
                            'news_id' => $request->id,
                            'mfo_id' => $mfo
                        ]
                    );                
                }
            }

            foreach($now as $n){

                if(!in_array($n, $mfos)){
                    DB::table('newses_mfos')->where('mfo_id', $n)->delete();
                }    

            }
        }

        //answers
        $answers = $request->answer;
        $now2 = DB::table('answers_newses')->where('news_id', $request->id)->lists('answer_id');
        
        if(empty($answers)){
            DB::table('answers_newses')->where('news_id', $request->id)->delete();
        }
        else{
            foreach($answers as $answer){
                if(!in_array($answer, $now2)){
                    DB::table('answers_newses')->insert(
                        [
                            'news_id' => $request->id,
                            'answer_id' => $answer
                        ]
                    );                
                }
            }

            foreach($now2 as $n){

                if(!in_array($n, $answers)){
                    DB::table('answers_newses')->where('answer_id', $n)->delete();
                }    

            }
        }


        return redirect('/admin/news')->with('message', 'Новость обновлена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $one = News::find($request->id);
        $one->delete();
        return redirect('/admin/news')->with('message', 'Новость удалена');
    }
}

    function create_thumbnail($path, $filename, $extension)
    {
        $width  = 1170;
        $height = 481;
        $mode   = ImageInterface::THUMBNAIL_OUTBOUND;
        $size   = new Box($width, $height);

        $thumbnail   = Imagine::open("{$path}/{$filename}.{$extension}")->thumbnail($size, $mode);
        $destination = "{$filename}.thumb.{$extension}";

        $thumbnail->save("{$path}/{$destination}");
    }