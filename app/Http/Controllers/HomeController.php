<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //データを取得
        $memos = Memo::select('memos.*')
        //  使っているuser_idがログインしているuserと一致するところ
            ->where('user_id', '=', \Auth::id())
            //deleted_atが空だけのもの
            ->whereNull('deleted_at')
            //メモの並び順(更新か新しいもの順)
            ->orderBy('updated_at', 'DESC')//ASC小さい順 DESC大きい順
            ->get();

        return view('create');
    }
    public function store(Request $request)
    {
        $posts = $request->all();
        //dd($psots)
        //データベースに入れるにはinsertを使う(配列で定義)
        //カラム名かkey=>'content'と'user_id,, value=取ってき値($postsとAuth::id)
        //\Auth::idはログインしている人のid
        Memo::insert(['content' => $posts['content'],'user_id' =>\Auth::id()]);
        //homeにリダイレクト
        return redirect( route('home'));

    }
}
