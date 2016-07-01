<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Method;
use App\Comment;
use App\News;
use App\Article;
use App\Answer;
use App\City;
use Meta;
use App\Subscriber;
use App\Mfo;
use DB;
use Mail;
use Illuminate\Support\Arr;
use App\Order;
use App\User;
use Bican\Roles\Models\Role;
use Hash;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        Meta::meta('title', 'Главная страница');
        Meta::meta('description', 'Описание главной страницы');
        Meta::meta('image', asset('/images/logo.png'));
        $methods = Method::all();
        $comments = Comment::all();
        $news = News::all()->take(5);
        $articles = Article::all()->take(5);
        $questions = Answer::all()->take(4);
        $cities = City::all();
        $mfo = Mfo::all()->take(5);
        return view('front.index', ['methods' => $methods, 'comments' => $comments, 'news' => $news, 'articles' => $articles, 'questions' => $questions, 'cities' => $cities, 'mfo' => $mfo]);
    }

    public function filter(Request $request)
    {
        $ids = [];
        $pay = ($request->pay) ? $request->pay : 'Яндекс.Деньги';
        $pay_id = Method::where('title', $pay)->lists('id');
        $mfo_by_pay = DB::table('methods_mfos')->whereIn('method_id', $pay_id)->lists('mfo_id');
        $companies = Mfo::whereIn('id', $mfo_by_pay);
        
        if($request->summa){
            $companies = $companies->where('summa', '>=', (int)$request->summa);
        }
        if($request->times){
            $companies = $companies->where('maxtime', '>=', (int)$request->times)->where('mintime', '<=', (int)$request->times);
        }
        if($request->city){
            $town = City::where('title', $request->city)->lists('id');
            $mfo_by_city = DB::table('cities_mfos')->whereIn('city_id', $town)->lists('mfo_id');
            $companies = $companies->whereIn('id', $mfo_by_city);
        }
        $companies = $companies->get();
        //return response()->json($companies);
        return view('front.ajax', ['companies' => $companies]);
    }

    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:subscribers'
        ]);
        Subscriber::create([
            'email' => $request->email
        ]);
        
        return response()->json(['mess' => 'Вы подписались на рассылку']);
    }

    public function initcity(Request $request)
    {
        $request->session()->put('usercity', $request->city);
        $city = City::find($request->city);
        return response()->json(['city' => $city->title]);
    }

    public function takecity(Request $request)
    {
        $cityid = $request->session()->get('usercity');
        if($cityid){
            $city = City::find($cityid);
            return response()->json(['city' => $city->title]);    
        }
        else{
            return response()->json(['city' => 'Город']);   
        }
        
    }

    public function sendrequest(Request $request)
    {
        $data = [];
        $data['type'] = $request->type;
        $data['name'] = $request->name;
        $data['phone'] = $request->phone;
        $data['email'] = $request->email;
        $data['age'] = $request->age;
        $data['city'] = $request->city;
        $data['dohod'] = $request->dohod;
        $data['summ'] = $request->summ;

        $order = Order::create([
            'type' => $request->type,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'city' => $request->city,
            'age' => $request->age,
            'dohod' => $request->dohod,
            'summ' => $request->summ
        ]);
        
        /*Mail::send('emails.request', $data, function($m){
            $m->from('noreply@mfo.loc', 'Заявка с сайта');

            $m->to('trishin.jenya@yandex.ru', 'Сайт МФО')->subject('Заявка с сайта');
        });*/
        mail("trishin.jenya@yandex.ru", "Создана заявка", "Id заявки: $order->id \nВид займа: $request->type\n Имя: $request->name\n Телефон: $request->phone\n Email: $request->email\n Возраст: $request->age\n Город: $request->city\n Доход: $request->dohod\n Сумма займа: $request->summ");
        mail($request->email, "Вы создали заявку на сайте МФО", "Вы создали заявку на сайте МФО. Наш менеджер свяжется с Вами для уточнения деталей.");
    }

    public function sendmessage(Request $request)
    {
        $data = [];
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['question'] = $request->question;
        
        /*Mail::send('emails.feedback', ['data' => $data], function($message) use ($request){
            //$m->from('noreply@mfo.loc', 'Обратная связь с сайта');

            $message->to($request->email, 'Сайт МФО')->subject('Обратная связь с сайта');
        });*/
        mail("trishin.jenya@yandex.ru", "Обратная связь", "Имя: $request->name \n E-mail: $request->email \n Вопрос: $request->question");
    }

    
}
