<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Mfo;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Orchestra\Imagine\Facade as Imagine;
use Illuminate\Support\Facades\Request as Req;
use App\Method;
use App\City;
use App\Answer;
use DB;
use App\Sett;
use App\Borrow;

class MfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mfos = Mfo::all();
        return view('admin.mfo.index', ['mfos' => $mfos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $methods = Method::all();
        $cities = City::all();
        $answers = Answer::all();
        $setts = Sett::all();
        $borrows = Borrow::all();
        return view('admin.mfo.create', ['methods' => $methods, 'cities' => $cities, 'answers' => $answers, 'setts' => $setts, 'borrows' => $borrows]);
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

        $logoname = '';
        if(Req::hasFile('logo')){
            $extension = Req::file('logo')->getClientOriginalExtension(); // getting image extension
            $fileName = rand(11111,99999);
        
            Req::file('logo')->move('uploads/images', $fileName.'.'.$extension);
            create_thumbnail('uploads/images', $fileName, $extension);
            $logoname = $fileName.'.'.$extension;
        }

        $n = Mfo::create([
            'title' => $request->title,
            'seodescription' => $request->seodescription,
            'seokeywords' => $request->seokeywords,
            'seotitle' => $request->seotitle,
            'banner' => $bannername,
            'video' => $request->video,
            'howtake' => $request->howtake,
            'terms' => $request->terms,
            'advantages' => $request->advantages,
            'redemption' => $request->redemption,
            'logo' => $logoname,
            'site' => $request->site,
            'document' => $request->document,
            'registerdata' => $request->registerdata,
            'rating' => $request->rating,
            'mintime' => $request->mintime,
            'maxtime' => $request->maxtime,
            'summa' => $request->summa,
            'minpercent' => $request->minpercent,
            'maxpercent' => $request->maxpercent,
            'srok' => $request->srok,
            'transfer' => $request->transfer,
            'wrong' => $request->wrong,
            'desc' => $request->desc,
            'action' => $request->action,
            'actiondesc' => $request->actiondesc
        ]);

        $methods = $request->method;

        if(!empty($methods)){
            foreach($methods as $m){
                DB::table('methods_mfos')->insert(
                    [
                        'method_id' => $m,
                        'mfo_id'     => $n->id
                    ]
                );
            }
        }

        $cities = $request->city;
        if(!empty($cities)){
            foreach($cities as $c){
                DB::table('cities_mfos')->insert(
                    [
                        'city_id' => $c,
                        'mfo_id'     => $n->id
                    ]
                );
            }
        }

        $answers = $request->answer;
        if(!empty($answers)){
            foreach($answers as $ans){
                DB::table('answers_mfos')->insert(
                    [
                        'answer_id' => $ans,
                        'mfo_id'     => $n->id
                    ]
                );
            }
        }

        $setts = $request->sett;
        if(!empty($setts)){
            foreach($setts as $sett){
                DB::table('setts_mfos')->insert(
                    [
                        'sett_id' => $sett,
                        'mfo_id'     => $n->id
                    ]
                );
            }
        }

        $borrows = $request->borrow;
        if(!empty($borrows)){
            foreach($borrows as $borrow){
                DB::table('borrows_mfos')->insert(
                    [
                        'borrow_id' => $borrow,
                        'mfo_id'     => $n->id
                    ]
                );
            }
        }

        return redirect('/admin/mfo')->with('message', 'МФО создано'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mfo = Mfo::find($id);
        return view('admin.mfo.show', ['mfo' => $mfo]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mfo = Mfo::find($id);
        $methods = Method::all();
        $cities = City::all();
        $answers = Answer::all();
        $setts = Sett::all();
        $borrows = Borrow::all();
        return view('admin.mfo.edit', ['mfo' => $mfo, 'methods' => $methods, 'cities' => $cities, 'answers' => $answers, 'setts' => $setts, 'borrows' => $borrows]);
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

        $logoname = '';
        if(Req::hasFile('logo')){
            $extension = Req::file('logo')->getClientOriginalExtension(); // getting image extension
            $fileName = rand(11111,99999);
        
            Req::file('logo')->move('uploads/images', $fileName.'.'.$extension);
            create_thumbnail('uploads/images', $fileName, $extension);
            $logoname = $fileName.'.'.$extension;
        }
        $mfo = Mfo::find($request->id);
        $mfo->title = $request->title;
        $mfo->seodescription = $request->seodescription;
        $mfo->seokeywords = $request->seokeywords;
        $mfo->seotitle = $request->seotitle;
        if(Req::hasFile('banner')){
            $mfo->banner = $bannername;
        }
        $mfo->video = $request->video;
        $mfo->howtake = $request->howtake;
        $mfo->terms = $request->terms;
        $mfo->advantages = $request->advantages;
        $mfo->redemption = $request->redemption;
        if(Req::hasFile('logo')){
            $mfo->logo = $logoname;
        }
        $mfo->site = $request->site;
        $mfo->document = $request->document;
        $mfo->registerdata = $request->registerdata;
        $mfo->rating = $request->rating;
        $mfo->mintime = $request->mintime;
        $mfo->maxtime = $request->maxtime;
        $mfo->summa = $request->summa;
        $mfo->minpercent = $request->minpercent;
        $mfo->maxpercent = $request->maxpercent;
        $mfo->srok = $request->srok;
        $mfo->transfer = $request->transfer;
        $mfo->wrong = $request->wrong;
        $mfo->desc = $request->desc;
        $mfo->action = $request->action;
        $mfo->actiondesc = $request->actiondesc;
        $mfo->save();

        //methods
        $methods = $request->method;
        $now = DB::table('methods_mfos')->where('mfo_id', $request->id)->lists('method_id');
        
        if(empty($methods)){
            DB::table('methods_mfos')->where('mfo_id', $request->id)->delete();
        }
        else{
            foreach($methods as $method){
                if(!in_array($method, $now)){
                    DB::table('methods_mfos')->insert(
                        [
                            'method_id' => $method,
                            'mfo_id' => $request->id
                        ]
                    );                
                }
            }

            foreach($now as $n){

                if(!in_array($n, $methods)){
                    DB::table('methods_mfos')->where('method_id', $n)->delete();
                }    

            }
        }

        //cities
        $cities = $request->city;
        $now = DB::table('cities_mfos')->where('mfo_id', $request->id)->lists('city_id');
        
        if(empty($cities)){
            DB::table('cities_mfos')->where('mfo_id', $request->id)->delete();
        }
        else{
            foreach($cities as $city){
                if(!in_array($city, $now)){
                    DB::table('cities_mfos')->insert(
                        [
                            'city_id' => $city,
                            'mfo_id' => $request->id
                        ]
                    );                
                }
            }

            foreach($now as $n){

                if(!in_array($n, $cities)){
                    DB::table('cities_mfos')->where('city_id', $n)->delete();
                }    

            }
        }

        //answers
        $answers = $request->answer;
        $now = DB::table('answers_mfos')->where('mfo_id', $request->id)->lists('answer_id');
        
        if(empty($answers)){
            DB::table('answers_mfos')->where('mfo_id', $request->id)->delete();
        }
        else{
            foreach($answers as $answer){
                if(!in_array($answer, $now)){
                    DB::table('answers_mfos')->insert(
                        [
                            'answer_id' => $answer,
                            'mfo_id' => $request->id
                        ]
                    );                
                }
            }

            foreach($now as $n){

                if(!in_array($n, $answers)){
                    DB::table('answers_mfos')->where('answer_id', $n)->delete();
                }    

            }
        }

        //setts
        $setts = $request->sett;
        $now = DB::table('setts_mfos')->where('mfo_id', $request->id)->lists('sett_id');
        
        if(empty($setts)){
            DB::table('setts_mfos')->where('mfo_id', $request->id)->delete();
        }
        else{
            foreach($setts as $sett){
                if(!in_array($sett, $now)){
                    DB::table('setts_mfos')->insert(
                        [
                            'sett_id' => $sett,
                            'mfo_id' => $request->id
                        ]
                    );                
                }
            }

            foreach($now as $n){

                if(!in_array($n, $setts)){
                    DB::table('setts_mfos')->where('sett_id', $n)->delete();
                }    

            }
        }

        //borrows
        $borrows = $request->borrow;
        $now = DB::table('borrows_mfos')->where('mfo_id', $request->id)->lists('borrow_id');
        
        if(empty($borrows)){
            DB::table('borrows_mfos')->where('mfo_id', $request->id)->delete();
        }
        else{
            foreach($borrows as $borrow){
                if(!in_array($borrow, $now)){
                    DB::table('borrows_mfos')->insert(
                        [
                            'borrow_id' => $borrow,
                            'mfo_id' => $request->id
                        ]
                    );                
                }
            }

            foreach($now as $n){

                if(!in_array($n, $borrows)){
                    DB::table('borrows_mfos')->where('borrow_id', $n)->delete();
                }    

            }
        }

        return redirect('/admin/mfo')->with('message', 'МФО обновлена'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $mfo = Mfo::find($request->id);
        $mfo->delete();
        return redirect('/admin/mfo')->with('message', 'МФО удалено');
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