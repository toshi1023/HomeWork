@extends('layouts.app')

@section('h3')
    <i class="fas fa-copy"></i>
    &nbsp;CSV出力
@endsection

@section('content')
    <div class="row ml-md-2">
        <div class="col-sm-offset-1 col-md-10">
            <p>csvへの出力が完了しました</p>
            <p>
                {{ $row_count }}件のデータを登録しました
            </p>
            
        </div>
    </div>
@endsection