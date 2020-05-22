// @1 登録フォームの画像プレビューを動的に設定
$(function(){
    //画像ファイルプレビュー表示のイベント追加 fileを選択時に発火するイベントを登録
    $('form').on('change', 'input[type="file"]', function(e) {
        var file = e.target.files[0],
            reader = new FileReader(),
            $preview = $(".preview");
            t = this;

        // 画像ファイル以外の場合は何もしない
        if(file.type.indexOf("image") < 0){
        return false;
        }

        // ファイル読み込みが完了した際のイベント登録
        reader.onload = (function(file) {
        return function(e) {
            //既存のプレビューを削除
            $preview.empty();
            // .prevewの領域の中にロードした画像を表示するimageタグを追加
            $preview.append($('<img>').attr({
                    src: e.target.result,
                    width: "350px",
                    height: "250px",
                    class: "preview",
                    title: file.name
                }));
        };
        })(file);

        reader.readAsDataURL(file);
    });
});
// @1


// @2 登録フォームの入力チェックを動的に設定
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
// @2