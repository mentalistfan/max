<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Borrow;
use Illuminate\Support\Facades\Request as Req;
use Maatwebsite\Excel\Facades\Excel;

class BorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $borrows = Borrow::all();
        return view('admin.borrow.index', ['borrows' => $borrows]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.borrow.create');
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

        Borrow::create([
            'title' => $request->title,
            'description' => $request->description,
            'name' => $request->name,
            'seotitle' => $request->seotitle,
            'seodescription' => $request->seodescription,
            'seokeywords' => $request->seokeywords
        ]);

        return redirect('/admin/borrows')->with('message', 'Тип создан');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $borrow = Borrow::find($id);
        return view('admin.borrow.show', ['borrow' => $borrow]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $borrow = Borrow::find($id);
        return view('admin.borrow.edit', ['borrow' => $borrow]);
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

        $borrow = Borrow::find($request->id);
        $borrow->title = $request->title;
        $borrow->description = $request->description;
        $borrow->name = $request->name;
        $borrow->seotitle = $request->seotitle;
        $borrow->seodescription = $request->seodescription;
        $borrow->seokeywords = $request->seokeywords;
        $borrow->save();

        return redirect('/admin/borrows')->with('message', 'Тип заемщика обновлен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $borrow = Borrow::find($request->id);
        $borrow->delete();

        return redirect('/admin/borrows')->with('message', 'Тип заемщика удален');
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
                        $yet = Borrow::where('title', $t['title'])->get()->toArray();
                        
                        if(empty($yet)){
                            $newsett = Borrow::create([
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

            return redirect('admin/borrows')->with('message', 'Типы импортированы');
        }
    }
}
