@extends('layouts.app')

@section('content')
<div class="card">
    <h5 class="card-header">新規メモ作成</h5>
    <form class="card-body" action="/store" method="POST">
        @csrf
        <div class="form-group">
            <textarea class="form-control" name="content" rows="3" placeholder="ここに入力"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">WWWW</button>
    </form>
</div>
@endsection
