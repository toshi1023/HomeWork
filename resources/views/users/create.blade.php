@extends('layouts.app')

@section('h3')
    <i class="fas fa-user-edit"></i>
    &nbsp;ユーザ作成
@endsection

@section('content')
<div class="row">
    <form action="{{ route('users.store') }}" method="POST" id="register_form" enctype='multipart/form-data'>
        {{ csrf_field() }}
        <div class="col-md-6">
            <label for="last_name">苗字</label>
            <input type="text" id="last_name" name="last_name" class="form-control">

            <label for="first_name">名前</label>
            <input type="text" id="first_name" name="first_name" class="form-control">

            <label for="email">メールアドレス</label>
            <input type="text" id="email" name="email" class="form-control">

            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>
            <div class="col-md-6">
                {{-- 画像削除時のメッセージ --}}
                <div id="fade_msg" style="display: none; color: red">画像を削除しました</div>

                <div class="preview"><img src="{{ asset('storage/images/noImage/Noimage_image.png') }}" width="350" height="250"></div>
                <input type="file" id="profile_image" name="profile_image" class="form-control-file">
                <div id="preview_field"></div>
                <div id="drop_area">drag and drop<br>or<br>click here.</div>
                <div id="icon_clear_button">X</div>
                <label for="profile_image">プロフィール画像</label>
                    <input type="button" id="cancell" class="btn btn-danger" value="画像を消去">
                    <input type="hidden" id="img_delete" name="img_delete" value=0>

                    <label for="company_id">会社名</label>
                    <select name="company_id" id="company_id" class="form-control">
                        <option value="" selected>選択してください</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>

                    <label for="memo">備考</label>
                    <input type="text" name="memo" class="form-control">
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
<script src="{{ asset('js/origin.js') }}">
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
            if ($('#password').val() == "") {
                $('#password').after('<p class="error-text" style="color: red">パスワードは必須です</p>');
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