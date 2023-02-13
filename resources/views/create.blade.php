@extends('layouts.app')

@section('content')
<div class="card">
    <h5 class="card-header">新規メモ作成</h5>
    <form class="card-body" action="{{ route('store') }}" method="POST">
        @csrf
        <div class="form-group">
            <textarea class="form-control" name="content" rows="3" placeholder="ここに入力"></textarea>
        </div>
        <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="新規タグ"/>
        <button type="submit" class="btn btn-primary">WWWW</button>
    </form>
</div>
@endsection
