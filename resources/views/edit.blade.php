@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        メモ編集
        <form class="card-body" action="{{ route('destory') }}" method="POST">
            @csrf
            <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />
            <button type="submit">消去</button>
        </form>
    </div>
    <form class="card-body" action="{{ route('update') }}" method="POST">
        @csrf
        <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />
        <div class="form-group">
            <textarea class="form-control" name="content" rows="3" placeholder="ここに入力">{{ $edit_memo[0]['content'] }}</textarea>
        </div>
        @foreach ( $tags as $t )
            <div class="form-check form-switch">
                {{-- テェックボックスで複数の値を送りたい時 name="tags[]"--}}
                {{-- 三項演算子 if文を1行で書く方法条件 ? trueだったら : falseだったら --}}
                {{-- もし$include_tagsにループで回っているタグのidが含まれれば。checkedを書く --}}
                {{-- in_array(配列に含まれれば)php構文 --}}
                <input class="form-check-input" type="checkbox" name="tags[]" id="{{ $t['id'] }}" value="{{ $t['id'] }}" {{ in_array($t['id'], $include_tags) ? 'checked' : ''}}>
                <label class="form-check-label" for="{{ $t['id'] }}">{{  $t['name'] }}</label>
            </div>
        @endforeach
        <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="新規タグ"/>
        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>
@endsection
