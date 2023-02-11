@extends('layouts.app')

@section('content')
<div class="card">
    <h5 class="card-header">メモ編集</h5>
    <form class="card-body" action="{{ route('store') }}" method="POST">
        @csrf
        <div class="form-group">
            <textarea class="form-control" name="content" rows="3" placeholder="ここに入力">
            {{ $edit_memo['content'] }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>
@endsection
