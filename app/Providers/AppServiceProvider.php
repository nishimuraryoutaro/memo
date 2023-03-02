<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Memo;
use App\Models\Tag;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function($view) {
            //自分で作ったfunctionはインスタス化が必要
            $memo_model = new Memo();
            //メモの取得
            $memos = $memo_model->getMyMemo();

            $tags = Tag::where('user_id', '=',\Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();
            //第一引数はviewで使う時の命名、第二引数は渡したい変数or 配列
            $view->with('memos', $memos)->with('tags', $tags);
        });
}
}
