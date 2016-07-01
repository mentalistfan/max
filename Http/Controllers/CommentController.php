<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Comment;
use App\Mfo;
use DateTime;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mfos = Mfo::all();
        $comments = Comment::all();
        return view('admin.comments.index', ['comments' => $comments, 'mfos' => $mfos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mfos = Mfo::all()->lists('title', 'id');
        return view('admin.comments.create', ['mfos' => $mfos]);
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
            'author' => 'required|max:255',
            'email'  => 'required|email'
        ]);

        if($request->data != ''){
            $data = $request->data;
        }
        else{
            $data = date('d/m/Y');
        }
        Comment::create([
            'author' => $request->author,
            'email' => $request->email,
            'text' => $request->text,
            'mfo_id' => $request->mfo_id,
            'rating' => $request->rating,
            'show' => $request->show,
            'data' => $data
        ]);

        return redirect('/admin/comments')->with('message', 'Комментарий создан'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = Comment::find($id);
        return view('admin.comments.show', ['comment' => $comment]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comment = Comment::find($id);
        $mfos = Mfo::all()->lists('title', 'id');
        return view('admin.comments.edit', ['comment' => $comment, 'mfos' => $mfos]);
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
            'author' => 'required|max:255',
            'email'  => 'required|email'
        ]);

        $date = new DateTime($request->data);
        //return $date; 
        $usableDate = $date->format('Y.m.d');
        //return $usableDate;
        $comment = Comment::find($request->id);
        $comment->author = $request->author;
        $comment->email = $request->email;
        $comment->text = $request->text;
        $comment->mfo_id = $request->mfo_id;
        $comment->rating = $request->rating;
        $comment->show = $request->show;
        $comment->data = $usableDate;
        $comment->save();

        return redirect('/admin/comments')->with('message', 'Комментарий обновлен'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $comment = Comment::find($request->id);
        $comment->delete();
        return redirect('/admin/comments')->with('message', 'Комментарий удален'); 
    }

    public function filter(Request $request)
    {
        $mfos = Mfo::all();
        $mfoa = [];
        if(empty($request->from) and !empty($request->mfo)){
            $mfoa = $request->mfo;
            $comments = Comment::whereIn('mfo_id', $mfoa)->get();
        }
        else if(empty($request->mfo)){
            if($request->to == ''){
                $from = new DateTime($request->from);
                $fromDate = $from->format('Y-m-d');
                $comments = Comment::where('data', $fromDate)->get();
            }
            else{
                $from = new DateTime($request->from);
                $fromDate = $from->format('Y-m-d');

                $to = new DateTime($request->to);
                $toDate = $to->format('Y-m-d');
    
                $comments = Comment::whereBetween('data', [$fromDate, $toDate])->get();
            }
        }
        else{
            
        }


        return view('admin.comments.filter', ['comments' => $comments, 'mfos' => $mfos, 'mfoa' => $mfoa]);
    }
}
