<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\MemoTag;
use DB;
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
        $tags = Tag::where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();

        return view('create',compact('tags'));//compactで変数を渡してview側で使えるようにする
    }
    public function store(Request $request)
    {
        $posts = $request->all();
        //バリデーション 'content' = viewのname属性
        $request->validate(['content' => 'required']);
        //バリデーション
        //データベースに入れるにはinsertを使う(配列で定義)
        //カラム名かkey=>'content'と'user_id,, value=取ってき値($postsとAuth::id)
        //\Auth::idはログインしている人のid
        //homeにリダイレクト
        //insertすると同時にテーブルに入れたidを取得
    //タグ機能のトランザクション
        DB::transaction(function() use($posts){
            $memo_id = Memo::insertGetId(['content' => $posts['content'],'user_id' =>\Auth::id()]);
            $tag_exists = Tag::where('user_id', '=', \Auth::id())->where('name', '=', $posts['new_tag'])->exists();
            if(!empty($posts['new_tag']) && !$tag_exists ){
                $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
                MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag_id]);
            }
            if(!empty($posts['tags'][0])){
                foreach($posts['tags'] as $tag){
                    MemoTag::insert(['memo_id' => $memo_id, 'tag_id' =>$tag]);
                }
        }
    });
        return redirect( route('home'));

    }
    public function edit($id)
    {
        //データを取得
        //findでMemosテーブルの主キーからidが一致するのを取得
        $edit_memo = Memo::select('memos.*','tags.id AS tag_id')//memosテーブル全てとtags.idを取得(tag.idだとmemos.idもあるのでtags.idの方が優先されてしまうのでASで別名tag_idでを使う)
        //memo_tagsテーブルはからの場合があるのでleftjoinでmemo_tags.memo_idとmemos_idが一致するようくっつける
            ->leftjoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
            ->leftjoin('tags', 'memo_tags.tag_id', '=', 'tags.id')
            ->where('memos.user_id', '=', \Auth::id())
            ->where('memos.id', '=', $id)//memos_idがURLパラメータのidと一致する部分
            ->whereNull('memos.deleted_at')
            //deleted_atが空だけのもの
            ->get();
        //含まれるタグだけを注視う
    

        $include_tags = [];
        foreach($edit_memo as $memo){
            array_push($include_tags, $memo['tag_id']);
        }

        $tags = Tag::where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();
        //compactで変数を渡してview側で使えるようにする
        return view('edit',compact('edit_memo', 'include_tags', 'tags'));

    }
    public function update(Request $request)
    {
        $posts = $request->all();
        //バリデーション 'content' = viewのname属性
        $request->validate(['content' => 'required', 'tags' => 'required']);
        //トランザクション
           DB::transaction(function () use($posts){
           Memo::where('id', $posts['memo_id'])->update(['content' => $posts['content']]);
            //一旦メモとタグの紐付けを消去
           MemoTag::where('memo_id', '=', $posts['memo_id'])->delete();
           //再度メモとタグの紐付け
           foreach ($posts['tags'] as $tag) {
                MemoTag::insert(['memo_id' => $posts['memo_id'], 'tag_id' => $tag]);
           }
           //もし、新しいタグの入力があれば、インサートして紐付ける
            $tag_exists = Tag::where('user_id', '=', \Auth::id())->where('name', '=', $posts['new_tag'])->exists();
           if(!empty($posts['new_tag']) && !$tag_exists ){
            $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
            MemoTag::insert(['memo_id' => $posts['memo_id'], 'tag_id' => $tag_id]);
        }

        });
        //whereがないと全てのデータがupdateされてしまう

        return redirect( route('home') );

    }
    public function destory(Request $request)
    {
        $posts = $request->all();
        //whereがないと全てのデータがupdateされてしまう
        //->delete(['content' => $posts['content']]);だと物理削除になってしまうのでダメ
        Memo::where('id', $posts['memo_id'])
        ->update(['deleted_at' => date("Y-m-d H:i:s", time())]);
        
        return redirect( route('home') );

    }
}