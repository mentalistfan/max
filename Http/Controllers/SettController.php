<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Sett;
use Illuminate\Support\Facades\Request as Req;
use Maatwebsite\Excel\Facades\Excel;

class SettController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setts = Sett::all();
        return view('admin.sett.index', ['setts' => $setts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sett.create');
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

        Sett::create([
            'title' => $request->title,
            'description' => $request->description,
            'name' => $request->name,
            'seotitle' => $request->seotitle,
            'seodescription' => $request->seodescription,
            'seokeywords' => $request->seokeywords
        ]);

        return redirect('/admin/setts')->with('message', 'Назначение создано');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sett = Sett::find($id);
        return view('admin.sett.show', ['sett' => $sett]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sett = Sett::find($id);
        return view('admin.sett.edit', ['sett' => $sett]);
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
        $sett = Sett::find($request->id);
        $sett->title = $request->title;
        $sett->description = $request->description;
        $sett->name = $request->name;
        $sett->seotitle = $request->seotitle;
        $sett->seodescription = $request->seodescription;
        $sett->seokeywords = $request->seokeywords;
        $sett->save();

        return redirect('/admin/setts')->with('message', 'Назначение обновлено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $sett = Sett::find($request->id);
        $sett->delete();

        return redirect('/admin/setts')->with('message', 'Назначение удалено');
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
                        $yet = Sett::where('title', $t['title'])->get()->toArray();
                        
                        if(empty($yet)){
                            $newsett = Sett::create([
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

            return redirect('admin/setts')->with('message', 'Способы импортированы');
        }
    }
}
