<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Order;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        return view('admin.orders.index', ['orders' => $orders]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.orders.create');
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
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'required|email',
            'summ' => 'required'
        ]);

        Order::create([
            'type' => $request->type,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'city' => $request->city,
            'age' => $request->age,
            'dohod' => $request->dohod,
            'summ' => $request->summ
        ]);

        return redirect('/admin/orders')->with('message', 'Заявка создана');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        return view('admin.orders.edit', ['order' => $order]);
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
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'required|email',
            'summ' => 'required'
        ]);

        $order = Order::find($request->id);
        $order->type = $request->type;
        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->email = $request->email;
        $order->city = $request->city;
        $order->age = $request->age;
        $order->dohod = $request->dohod;
        $order->summ = $request->summ;
        $order->save();

        return redirect('/admin/orders')->with('message', 'Заявка обновлена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $order = Order::find($request->id);
        $order->delete();
        return redirect('/admin/orders')->with('message', 'Заявка удалена');
    }
}
