// @1 登録フォームの画像プレビューを動的に設定
$(function(){
    //画像ファイルプレビュー表示のイベント追加 fileを選択時に発火するイベントを登録
    // 引数eは変更された対象を代入(例：click(function(e){}; の文ではクリックされた要素をeに格納する))
    // on('change', '子要素')はjavascriptで変更された後のHTMLにも有効
    $('form').on('change', 'input[type="file"]', function(e) {
        // 変更された画像の情報を変数に代入
        var file = e.target.files[0],
            reader = new FileReader(),
            // previewはhtml側でdivのクラスとして設定しており、プレビュー画像の表示位置を表す
            $preview = $(".preview");

            // @1 これがないと何故か動かなくなる
            t = this;
            // @1

        // 画像ファイル以外の場合は何もしない
        // A.indexOf(B)はAにBの値を含むかを判別！含む場合は0以上の値を返し、含まない場合は-1を返す
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
                    // ロードされた画像ファイルのData URIスキームは e.target.result に格納される
                    // ※画像を文字列に置き換える技術で、imgタグのsrcに入れるもの
                    src: e.target.result,
                    width: "350px",
                    height: "250px",
                    class: "preview",
                    title: file.name
                }));
            };
            // @2 これがないと何故か動かなくなる
        })(file);
            // @2

        // readAsDataURL メソッドは、指定された Blob ないし File オブジェクトを読み込むために使用する
        reader.readAsDataURL(file);

        // 削除フラグを解除
        $('#img_delete').val(0);
    });
});
// @1


// プレビュー画像削除時のメッセージ設定
$(function(){
    $('#cancell').on('click', function(){
        $preview = $(".preview");

        //既存のプレビューを削除
        $preview.empty();
        // .prevewの領域の中にロードした画像を表示するimageタグを追加
        $preview.append($('<img>').attr({
            // ロードされた画像ファイルのData URIスキームは e.target.result に格納される
            // ※画像を文字列に置き換える技術で、imgタグのsrcに入れるもの
            src: "http://laravel.localhost/storage/images/noImage/Noimage_image.png",
            width: "350px",
            height: "250px",
            class: "preview",
            title: "Noimage_image.png",
        }));

        $('#fade_msg').fadeIn("slow", function () {
            //コールバックで3秒後にフェードアウト	
            $(this).delay(3000).fadeOut("slow");
        });

        // 削除フラグを設定
        $('#img_delete').val(1);
        
    });
});


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
