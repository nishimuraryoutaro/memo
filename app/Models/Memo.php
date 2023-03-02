<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;

    public function getMyMemo(){
        $query_tag = \Request::query('tag');
        //===ベースのメソッド＝＝
        $query = Memo::query()->select('memos.*')
         //使っているuser_idがログインしているuserと一致するところ
        ->where('user_id', '=', \Auth::id())
        //deleted_atが空だけのもの
        ->whereNull('deleted_at')
        ->orderBy('updated_at', 'DESC');//ASC小さい順 DESC大きい順

        //もしクエリーパラメータtagがあれば
        if(!empty($query_tag)) {
            //タグの絞り込み

            $query->leftjoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
            ->where('memo_tags.tag_id', '=', $query_tag);
            //使っているuser_idがログインしているuserと一致するところ
        }
        $memos = $query->get();

        return $memos;
    }
}
