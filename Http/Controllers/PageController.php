<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Meta;
use App\History;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	Meta::meta('title', 'Проверить МФО');
        Meta::meta('description', 'Проверить МФО');
        Meta::meta('image', asset('/images/logo.png'));
        return view('front.reestr');
    }

    public function history()
    {
    	Meta::meta('title', 'Проверить кредитную историю');
        Meta::meta('description', 'Проверить кредитную историю');
        Meta::meta('image', asset('/images/logo.png'));
    	$histories = History::all();
    	return view('front.history', ['histories' => $histories]);
    }

    
}
