{{-- @extends('layouts.app') --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://example.com/fontawesome/v6.3.0/js/all.js" data-family-prefix="icon"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        @section('javascript')
        <script src="/js/confirm.js"></script>
        @endsection
    @yield('javascript')
    <!-- Fonts -->
   <link rel="dns-prefetch" href="//fonts.gstatic.com">
   <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="/css/layout.css" rel="stylesheet">
</head>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container navbar-brand mb-0 h1">
                メモ編集
        </div>
    </nav>
</div>
{{-- @section('content') --}}
<div class="row">
    <div class="col-sm-12">
        <div class="card mb-0">
            <form class="card-body my-card-body" action="{{ route('update') }}" method="POST">
                @csrf
                <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />
                <div class="form-group">
                    <textarea class="form-control" name="content" rows="3" placeholder="ここに入力">{{ $edit_memo[0]['content'] }}</textarea>
                </div>
                @error('content')
                    <div class="alert alert-danger">メモ内容を入力してください</div>
                @enderror
                @error('content')
                    <div class="alert alert-danger">タグ内容を入力してください</div>
                @enderror
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
            <form class="card-body" id="delete-form" action="{{ route('destory') }}" method="POST">
                    @csrf
                    <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />
                    <button type="submit" class="fa-solid fa-trash-can btn btn btn-danger" onclick="deleteHandle(event);">消去</i>
                </form>
        </div>
    </div>
</div>
{{-- @endsection --}}
