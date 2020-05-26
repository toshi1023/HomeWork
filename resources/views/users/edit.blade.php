@extends('layouts.app')

@section('h3')
<i class="fas fa-user-lock"></i>
<a href="{{ route('users.index') }}">ユーザ一覧 / </a>
    @if($user->id == Auth::user()->id)
        &nbsp;あなたのページ
    @else
        &nbsp;{{ $user->email }}さんのページ
    @endif
@endsection

@section('content')
{{-- コンテンツ内に収めるためにもグリッドシステムで要調整 --}}
<div class="form-group row">
    <form action="{{ route('users.update', ['user' => $user->id]) }}" method="POST" id="register_form" enctype='multipart/form-data'>
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">
        {{-- 編集フォームをデザイン --}}
        <div class="col-md-6">
            <label for="last_name">苗字</label>
            <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name') ?? $user->last_name }}">

            <label for="first_name">名前</label>
            <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name') ?? $user->first_name }}">

            <label for="email">メールアドレス</label>
            <input type="text" id="email" name="email" class="form-control" value="{{ old('email') ?? $user->email }}">

            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>
        <div class="col-md-6">
            {{-- 画像削除時のメッセージ --}}
            <div id="fade_msg" style="display: none; color: red">画像の消去を反映するには登録ボタンを押してください</div>

            <label for="profile_image">プロフィール画像</label>
            {{-- 画像のプレビュー表示欄 --}}
            <div class="user-icon-dnd-wrapper">
                <input type="file" id="profile_image" name="profile_image" class="form-control-file" value="{{ old('profile_image') ?? $user->profile_image }}">
                @if ($user->profile_image != null)
                    <div class="preview"><img src="{{ asset('storage/images/'. $user->id .'/'. $user->profile_image) }}" width="350" height="250"></div>
                @else
                    <div class="preview"><img src="{{ asset('storage/images/noImage/Noimage_image.png') }}" width="350" height="250"></div>
                @endif
                <div id="drop_area"></div>
            </div>
            <input type="button" id="cancell" class="btn btn-danger" value="画像を消去">
            <input type="hidden" id="img_delete" name="img_delete" value=0>
            <br>
            <label for="company_id">会社名</label>
            <select name="company_id" id="company_id" class="form-control">
                <option value="">選択してください</option>
                {{-- 登録された会社名を初期表示するように設定 --}}
                @foreach($companies as $company)
                    @if ($company->id == $user->company_id)
                        <option value="{{ $company->id }}" selected>{{ $company->name }}</option>
                    @else
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endif
                @endforeach
            </select>

            <label for="memo">備考</label>
            <input type="text" name="memo" class="form-control" value="{{ old('memo') ?? $user->memo }}">
            <br>
            <br>
            <div class="form-group">
                <input type="button" class="btn btn-primary" value="登録する" id="btn_register"/>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/origin.js') }}"></script>
<script>
    $(function(){
        // "登録する"ボタンが押されたら処理を開始
        $('#btn_register').on('click', function(){

            // エラーチェックを実行
            $('.error-text').remove();
            let check = true;

            // 入力チェックする
            if ($('#last_name').val() == "") {
                $('#last_name').after('<p class="error-text" style="color: red">苗字は必須です</p>');
                check = false;
            }
            if ($('#email').val() == "") {
                $('#email').after('<p class="error-text" style="color: red">メールアドレスは必須です</p>');
                check = false;
            }
            if ($('#company_id').val() == "") {
                $('#company_id').after('<p class="error-text" style="color: red">会社名は必須です</p>');
                check = false;
            }

            // 入力チェックをくぐり、check定数がtrueの場合、submit処理が実行される
            if (check) {
                $('#register_form').submit();
            }

        });
    });
</script>
@endsection