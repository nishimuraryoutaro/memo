@extends('layouts.app')

@section('content')
<div class="card">
    <h5 class="card-header">新規メモ作成</h5>
    <form class="card-body my-card-body" action="{{ route('store') }}" method="POST">
        @csrf
        <div class="form-group">
            <textarea class="form-control" name="content" rows="3" placeholder="ここに入力"></textarea>
        </div>
        @error('content')
            <div class="alert alert-danger">メモ内容を入力してください</div>
        @enderror
    @foreach ( $tags as $t )
            <div class="form-check form-switch">
                {{-- テェックボックスで複数の値を送りたい時 name="tags[]"--}}
                <input class="form-check-input" type="checkbox" name="tags[]" id="{{ $t['id'] }}" value="{{ $t['id'] }}">
                <label class="form-check-label" for="{{ $t['id'] }}">{{  $t['name'] }}</label>
            </div>
    @endforeach
        <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="新規タグ"/>
        {{-- <button type="submit" class="btn btn-primary">WWWW</button> --}}
        <div class="p-2 w-full">
            <button type="submit" class="flex mx-auto text-white btn btn-primary bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">追加</button>
        </div>
    </form>
</div>
@endsection


