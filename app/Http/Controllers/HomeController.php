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

        return view('create',compact('memos'));//compactで変数を渡してview側で使えるようにする
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
    public function edit($id)
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
        //findでMemosテーブルの主キーからidが一致するのを取得
        $edit_memo = Memo::find($id);

        return view('edit',compact('memos', 'edit_memo'));//compactで変数を渡してview側で使えるようにする
    }
    public function update(Request $request)
    {
        $posts = $request->all();
        //dd($posts);
        //whereがないと全てのデータがupdateされてしまう
        Memo::where('id', $posts['memo_id'])->update(['content' => $posts['content']]);
        return redirect( route('home') );

    }
    public function destory(Request $request)
    {
        $posts = $request->all();
        //dd($posts);
        //whereがないと全てのデータがupdateされてしまう
        //->delete(['content' => $posts['content']]);だと物理削除になってしまうのでダメ
        Memo::where('id', $posts['memo_id'])->update(['deleted_at' => date("Y-m-d H:i:s", time())]);
        return redirect( route('home') );

    }
}
