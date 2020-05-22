@extends('layouts.app')

@section('h3')
    <i class="fas fa-user-lock"></i>
    &nbsp;マイページ
@endsection

@section('content')
<br>
{{-- コンテンツ内に収めるためにもグリッドシステムで要調整 --}}
<div class="form-group row">
    {{-- 編集ページへリダイレクトするボタンを配置 --}}
    <div class="col-sm-1"><a href="{{ route('users.edit', ['user' => Auth::user()->id]) }}" type="button" class="btn btn-primary">編集する</a></div>
    {{-- 確認用フォームをデザイン --}}
    <div class="col-md-offset-1 col-md-4">
        <form action="" method="POST" id="register_form" enctype='multipart/form-data'>
            {{ csrf_field() }}

            <label for="profile_image">プロフィール画像</label>
            <br>
            <img src="{{ asset('storage/images/'. $user->id .'/'. $user->profile_image) }}" width="400" height="300">
            <br>

            <label for="last_name">苗字</label>
            <input disabled type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name') ?? $user->last_name }}">

            <label for="first_name">名前</label>
            <input disabled type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name') ?? $user->first_name }}">

            <label for="email">メールアドレス</label>
            <input disabled type="text" id="email" name="email" class="form-control" value="{{ old('email') ?? $user->email }}">

            <label for="password">パスワード</label>
            <input disabled type="password" id="password" name="password" class="form-control" value="{{ old('password') ?? $user->password }}">

            <label for="company_id">会社名</label>
            <select disabled name="company_id" id="company_id" class="form-control">
                <option value="{{ $company->id }}" selected>{{ $company->name }}</option>
            </select>

            <label for="memo">備考</label>
            <input disabled type="text" name="memo" class="form-control" value="{{ old('memo') ?? $user->memo }}">
            <br>
            <br>
        </form>
    </div>
</div>
@endsection