<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Meta;
use App\Mfo;
use App\Method;
use DB;
use App\City;
use App\Sett;
use App\Borrow;

class CatalogController extends Controller
{
    
    public function index(Request $request)
    {
        Meta::meta('title', 'Рейтинг займов');
        Meta::meta('description', 'Рейтинг займов');
        Meta::meta('image', asset('/images/logo.png'));
        $mfos = Mfo::all()->sortByDesc('rating');
        $count = $mfos->count();

        return view('front.catalog', ['mfos' => $mfos, 'count' => $count]);
    }
    public function cash(Request $request)
    {
        Meta::meta('title', 'Займы наличными');
        Meta::meta('description', 'Займы наличными');
        Meta::meta('image', asset('/images/logo.png'));
        $summa = (int)$request->summa;
        $time = $request->time;
        $pay_id = Method::where('title', 'Наличными')->lists('id');
        $mfo_by_pay = DB::table('methods_mfos')->whereIn('method_id', $pay_id)->lists('mfo_id');
        $comp = Mfo::whereIn('id', $mfo_by_pay);
        
        if($request->session()->get('usercity')){
            $city = City::find($request->session()->get('usercity'));
            $city = $city->title;

            $town = City::where('title', $city)->lists('id');
            $mfo_by_city = DB::table('cities_mfos')->whereIn('city_id', $town)->lists('mfo_id');
            $comp = $comp->whereIn('id', $mfo_by_city);
        }
        else{
            $city = '';
        }
        if($summa != ''){
            $comp = $comp->where('summa', '>=', $summa);
        }
        if($time != ''){
            $comp = $comp->where('maxtime', '>=', (int)$request->time)->where('mintime', '<=', (int)$request->time);
        }
        //return $pay_id;
        $companies = $comp->get()->sortBy('minpercent');
        $count = $comp->count();

        return view('front.cash', ['mfos' => $companies, 'count' => $count, 'city' => $city]);
    }

    public function online(Request $request)
    {
        Meta::meta('title', 'Займы онлайн');
        Meta::meta('description', 'Займы онлайн');
        Meta::meta('image', asset('/images/logo.png'));
        $methods = Method::where('id', '!=', '8')->get();

        $summa = (int)$request->summa;
        $time = $request->time;
        if($request->type){
            $type = Method::find($request->type);
            $pay_id = Method::where('title', $type->title)->lists('id');
            $typeName = $type->title;
        }
        else{
            $pay_id = Method::where('title', 'QIWI кошелек')->lists('id');    
            $typeName = 'QIWI кошелек';
        }
        
        $mfo_by_pay = DB::table('methods_mfos')->whereIn('method_id', $pay_id)->lists('mfo_id');
        $comp = Mfo::whereIn('id', $mfo_by_pay);
        
        if($request->session()->get('usercity')){
            $city = City::find($request->session()->get('usercity'));
            $city = $city->title;

            $town = City::where('title', $city)->lists('id');
            $mfo_by_city = DB::table('cities_mfos')->whereIn('city_id', $town)->lists('mfo_id');
            $comp = $comp->whereIn('id', $mfo_by_city);
        }
        else{
            $city = '';
        }
        //return $pay_id;
        if($summa != ''){
            $comp = $comp->where('summa', '>=', $summa);
        }
        if($time != ''){
            $comp = $comp->where('maxtime', '>=', (int)$request->time)->where('mintime', '<=', (int)$request->time);
        }

        $companies = $comp->get()->sortBy('minpercent');
        $count = $comp->count();

        return view('front.online', ['mfos' => $companies, 'count' => $count, 'city' => $city, 'methods' => $methods, 'typeName' => $typeName]);
    }

    public function cashsorting(Request $request)
    {
        $pay_id = Method::where('title', 'Наличными')->lists('id');
        $mfo_by_pay = DB::table('methods_mfos')->whereIn('method_id', $pay_id)->lists('mfo_id');
        $comp = Mfo::whereIn('id', $mfo_by_pay);
        
        if($request->session()->get('usercity')){
            $city = City::find($request->session()->get('usercity'));
            $city = $city->title;

            $town = City::where('title', $city)->lists('id');
            $mfo_by_city = DB::table('cities_mfos')->whereIn('city_id', $town)->lists('mfo_id');
            $comp = $comp->whereIn('id', $mfo_by_city);
        }
        if($request->summa){
            $comp = $comp->where('summa', '>=', (int)$request->summa);
        }
        if($request->times){
            $comp = $comp->where('maxtime', '>=', (int)$request->times)->where('mintime', '<=', (int)$request->times);
        }

        $companies = $comp->get()->sortBy($request->sort);
        $count = $comp->count();
        return view('front.ajax', ['companies' => $companies]);
    }

    public function catalogsorting(Request $request)
    {
        if($request->sort == 'mintime'){
            $mfos = Mfo::all()->sortBy($request->sort); 
        }
        else if($request->sort == 'reviews'){
            $mfos = Mfo::with('comments')->get()->sortByDesc(function($mfo)
            {
                return $mfo->comments->count();
            });
        }
        else{
            $mfos = Mfo::all()->sortByDesc($request->sort);
        }
        return view('front.ajax', ['companies' => $mfos]);
    }

    public function onlinesorting(Request $request)
    {
        $pay_id = Method::where('title', $request->onlinetype)->lists('id');
        $mfo_by_pay = DB::table('methods_mfos')->whereIn('method_id', $pay_id)->lists('mfo_id');
        $comp = Mfo::whereIn('id', $mfo_by_pay);
        
        if($request->session()->get('usercity')){
            $city = City::find($request->session()->get('usercity'));
            $city = $city->title;

            $town = City::where('title', $city)->lists('id');
            $mfo_by_city = DB::table('cities_mfos')->whereIn('city_id', $town)->lists('mfo_id');
            $comp = $comp->whereIn('id', $mfo_by_city);
        }
        if($request->summa){
            $comp = $comp->where('summa', '>=', (int)$request->summa);
        }
        if($request->time){
            $comp = $comp->where('maxtime', '>=', (int)$request->times)->where('mintime', '<=', (int)$request->times);
        }

        $companies = $comp->get()->sortBy($request->sort);
        $count = $comp->count();
        return view('front.ajax', ['companies' => $companies]);
    }

    public function city()
    {
        Meta::meta('title', 'Займы по городам');
        Meta::meta('description', 'Займы по городам');
        Meta::meta('image', asset('/images/logo.png'));
        $letters = array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','щ','ш','ь','ы','ъ','э','ю','я');
        $cities = City::all();
        return view('front.city', ['cities' => $cities]);
    }

    public function currentcity($slug)
    {
        $city = City::findBySlug($slug);
        Meta::meta('title', 'Займы по городу '.$city->title);
        Meta::meta('description', 'Займы по городу '.$city->title);
        Meta::meta('image', asset('/images/logo.png'));
        return view('front.currentcity', ['city' => $city]);
    }

    public function citysorting(Request $request)
    {
        $mfo_by_city = DB::table('cities_mfos')->where('city_id', $request->city)->lists('mfo_id');
        $comp = Mfo::whereIn('id', $mfo_by_city);
        $companies = $comp->get()->sortBy($request->sort);
        return view('front.ajax', ['companies' => $companies]);
    }

    public function filter(Request $request)
    {
        if($request->type){
            $sett = Sett::find($request->type);    
        }
        else if($request->who){
            $sett = Borrow::find($request->who);
        }
    
        return view('front.filter', ['sett' => $sett]);
    }
}
