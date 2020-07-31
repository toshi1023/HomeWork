@extends('layouts.app')

@section('h3')
    <i class="fas fa-edit"></i>
    &nbsp;会社を登録
@endsection

@section('content')
<div class="row">
    <form action="{{ route('companies.store') }}" method="POST" id="register_form" enctype='multipart/form-data'>
        {{ csrf_field() }}
        <div class="col-md-6">
            <label for="name">会社名</label>
            <input type="text" id="name" name="name" class="form-control">
            
            <label for="name"><label for="post_no">郵便番号</label>
            
            <div class="input-group col-xs-6 col-md-4">
                <span class="input-group-addon">〒</span>
                <input name="post_no" maxlength="9" type="tel" class="form-control" placeholder="000-0000" onchange="javascript:getAddName(this.value)">
            </div>
            
            <div>
                <label for="address">所在地</label>
                <input type="text" id="address" name="address" class="form-control">
            <div>
            <div class="input-group col-xs-6 col-md-4">
                <label for="tel">電話番号</label>
                <input type="text" id="tel" name="tel" class="form-control" placeholder="000-0000-0000">
            </div>
            
            <div class="input-group col-xs-6 col-md-4">
                <label for="fax">FAX</label>
                <input type="text" id="fax" name="fax" class="form-control">
            </div>
            
            <label for="map_url">HP(URL)</label>
            <input type="text" id="map_url" name="map_url" class="form-control">

            <label for="memo">備考</label>
            <textarea name="memo" class="form-control"></textarea>
            <br>
            <div class="form-group">
                <input type="button" class="btn btn-primary" value="登録する" id="btn_register"/>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $(function(){
        // "登録する"ボタンが押されたら処理を開始
        $('#btn_register').on('click', function(){

            // エラーチェックを実行
            $('.error-text').remove();
            let check = true;

            // 入力チェックする
            if ($('#name').val() == "") {
                $('#name').after('<p class="error-text" style="color: red">会社名は必須です</p>');
                check = false;
            }
            if ($('#address').val() == "") {
                $('#address').after('<p class="error-text" style="color: red">所在地は必須です</p>');
                check = false;
            }
            if ($('#tel').val() == "") {
                $('#tel').after('<p class="error-text" style="color: red">電話番号は必須です</p>');
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