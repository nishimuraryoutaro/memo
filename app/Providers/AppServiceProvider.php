<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Memo;
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
            $memos = Memo::select('memos.*')
        //  使っているuser_idがログインしているuserと一致するところ
            ->where('user_id', '=', \Auth::id())
            //deleted_atが空だけのもの
            ->whereNull('deleted_at')
            //メモの並び順(更新か新しいもの順)
            ->orderBy('updated_at', 'DESC')//ASC小さい順 DESC大きい順
            ->get();
            //第一引数はviewで使う時の命名、第二引数は渡したい変数or 配列
            $view->with('memos', $memos);
        });
}
}
