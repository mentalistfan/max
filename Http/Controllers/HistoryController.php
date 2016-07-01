<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request as Req;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Orchestra\Imagine\Facade as Imagine;
use App\History;
use App\Method;
use DB;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $his = History::all();
        return view('admin.history.index', ['his' => $his]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $methods = Method::all();
        return view('admin.history.create', ['methods' => $methods]);
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

        $logoname = '';
        if(Req::hasFile('logo')){
            $extension = Req::file('logo')->getClientOriginalExtension(); // getting image extension
            $fileName = rand(11111,99999);
        
            Req::file('logo')->move('uploads/images', $fileName.'.'.$extension);
            create_thumbnail('uploads/images', $fileName, $extension);
            $logoname = $fileName.'.'.$extension;
        }
        if($request->action){
            $a = $request->action;
        }
        else{
            $a = 0;
        }

        $his = History::create([
            'title' => $request->title,
            'seotitle' => $request->seotitle,
            'seodescription' => $request->seodescription,
            'seokeywords' => $request->seokeywords,
            'price' => $request->price,
            'logo' => $logoname,
            'desc' => $request->desc,
            'how' => $request->how,
            'action' => $a,
            'actiondesc' => $request->actiondesc,
            'time' => $request->time,
            'site' => $request->site
        ]);

        $methods = $request->method;

        if(!empty($methods)){
            foreach($methods as $m){
                DB::table('methods_histories')->insert(
                    [
                        'method_id' => $m,
                        'history_id'     => $his->id
                    ]
                );
            }
        }

        return redirect('admin/histories')->with('message', 'Новая организация создана');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $his = History::find($id);
        $methods = Method::all();
        return view('admin.history.edit', ['his' => $his, 'methods' => $methods]);
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

        $logoname = '';
        if(Req::hasFile('logo')){
            $extension = Req::file('logo')->getClientOriginalExtension(); // getting image extension
            $fileName = rand(11111,99999);
        
            Req::file('logo')->move('uploads/images', $fileName.'.'.$extension);
            create_thumbnail('uploads/images', $fileName, $extension);
            $logoname = $fileName.'.'.$extension;
        }
        if($request->action){
            $a = $request->action;
        }
        else{
            $a = 0;
        }

        $history = History::find($request->id);
        $history->title = $request->title;
        $history->seotitle = $request->seotitle;
        $history->seodescription = $request->seodescription;
        $history->seokeywords = $request->seokeywords;
        $history->price = $request->price;
        if(Req::hasFile('logo')){
            $history->logo = $logoname;
        }
        $history->desc = $request->desc;
        $history->how = $request->how;
        $history->action = $a;
        $history->actiondesc = $request->actiondesc;
        $history->time = $request->time;
        $history->site = $request->site;
        $history->save();

        //methods
        $methods = $request->method;
        $now = DB::table('methods_histories')->where('history_id', $request->id)->lists('method_id');
        
        if(empty($methods)){
            DB::table('methods_histories')->where('history_id', $request->id)->delete();
        }
        else{
            foreach($methods as $method){
                if(!in_array($method, $now)){
                    DB::table('methods_histories')->insert(
                        [
                            'method_id' => $method,
                            'history_id' => $request->id
                        ]
                    );                
                }
            }

            foreach($now as $n){

                if(!in_array($n, $methods)){
                    DB::table('methods_histories')->where('method_id', $n)->delete();
                }    

            }
        }

        return redirect('admin/histories')->with('message', 'Организация обновлена');        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $h = History::find($request->id);
        $h->delete();
        return redirect('admin/histories')->with('message', 'Организация удалена');  
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