<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Method;
use Illuminate\Support\Facades\Request as Req;
use Maatwebsite\Excel\Facades\Excel;

class MethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $methods = Method::all();
        return view('admin.method.index', ['methods' => $methods]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.method.create');
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

        Method::create([
            'title' => $request->title,
            'description' => $request->description,
            'name' => $request->name,
            'seotitle' => $request->seotitle,
            'seodescription' => $request->seodescription,
            'seokeywords' => $request->seokeywords
        ]);

        return redirect('/admin/methods')->with('message', 'Способ создан');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $method = Method::find($id);
        return view('admin.method.show', ['method' => $method]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $method = Method::find($id);
        return view('admin.method.edit', ['method' => $method]);
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

        $method = Method::find($request->id);
        $method->title = $request->title;
        $method->description = $request->description;
        $method->name = $request->name;
        $method->seotitle = $request->seotitle;
        $method->seodescription = $request->seodescription;
        $method->seokeywords = $request->seokeywords;
        $method->save();

        return redirect('/admin/methods')->with('message', 'Способ обновлен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $method = Method::find($request->id);
        $method->delete();

        return redirect('/admin/methods')->with('message', 'Способ удален');
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'list' => 'required'
        ]);

        if(Req::hasFile('list')){
            $extension = Req::file('list')->getClientOriginalExtension(); // getting image extension
            $fileName = rand(11111,99999).'.'.$extension;
            Req::file('list')->move('uploads/xls', $fileName);

            Excel::load("uploads/xls/".$fileName, function($reader){
                $reader = $reader->toArray();
                
                foreach($reader as $s){
                    foreach($s as $t){
                        $yet = Method::where('title', $t['title'])->get()->toArray();
                        
                        if(empty($yet)){
                            $newsett = Method::create([
                                'title' => $t['title'],
                                'description' => '',
                                'name' => '',
                                'seotitle' => '',
                                'seodescription' => '',
                                'seokeywords' => ''
                            ]);    
                        } 
                    }
                }
            });

            return redirect('admin/methods')->with('message', 'Способы импортированы');
        }
    }
}
