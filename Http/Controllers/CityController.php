<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\City;
use Illuminate\Support\Facades\Request as Req;
use Maatwebsite\Excel\Facades\Excel;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::all();
        return view('admin.city.index', ['cities' => $cities]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.city.create');
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

        City::create([
            'title' => $request->title,
            'abr' => $request->abr,
            'name' => $request->name,
            'seotitle' => $request->seotitle,
            'seodescription' => $request->seodescription,
            'seokeywords' => $request->seokeywords
        ]);

        return redirect('/admin/cities')->with('message', 'Город создан'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $city = City::find($id);
        return view('admin.city.show', ['city' => $city]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $city = City::find($id);
        return view('admin.city.edit', ['city' => $city]);
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
        $city = City::find($request->id);
        $city->title = $request->title;
        $city->abr = $request->abr;
        $city->name = $request->name;
        $city->seotitle = $request->seotitle;
        $city->seodescription = $request->seodescription;
        $city->seokeywords = $request->seokeywords;
        $city->save();
        return redirect('/admin/cities')->with('message', 'Город обновлен'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $city = City::find($request->id);
        $city->delete();
        return redirect('/admin/cities')->with('message', 'Город удален'); 
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
                foreach($reader as $cities){
                    foreach($cities as $city){
                        $title = $city['title'] ? $city['title'] : '';
                        $title2 = $city['title2'] ? $city['title2'] : '';
                        $abr = $city['abbr'] ? $city['abbr'] : '';
                        $slug = $city['slug'] ? $city['slug'] : '';
                        $yet = City::where('title', $city['title'])->get()->toArray();
                        
                        if(empty($yet)){
                            $newcity = City::create([
                                'title' => $title,
                                'name' => $title2,
                                'abr' => $abr,
                                'slug' => $slug
                            ]);    
                        } 
                    }
                }
            });

            return redirect('admin/cities')->with('message', 'Города импортированы');
        }
    }
}
