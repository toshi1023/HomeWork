// @1 ファイルドロップ
$(function () {
    // #1 クリックで画像を選択する場合
    $('#drop_area').on('click', function () {
      $('#profile_image').click();
    });
  
    $('#profile_image').on('change', function () {
      // 画像が複数選択されていた場合(files.length : ファイルの数)
      if (this.files.length > 1) {
        alert('アップロードできる画像は1つだけです');
        $('#profile_image').val('');
        return;
      }

      handleFiles($('#profile_image')[0].files);
    });
    // #1

    // ドラッグしている要素がドロップ領域に入ったとき・領域にある間
    $('#drop_area').on('dragenter dragover', function (event) {
        event.stopPropagation();
        event.preventDefault();
        $('#drop_area').removeClass('dashed'); // 点線の枠を設定したクラスをリセット
        $('#drop_area').addClass('solid');  // 枠を実線にする
    });

    // ドラッグしている要素がドロップ領域から外れたとき
    $('#drop_area').on('dragleave', function (event) {
        event.stopPropagation();
        event.preventDefault();
        $('#drop_area').removeClass('solid'); // 実線の枠を設定したクラスをリセット
        $('#drop_area').addClass('dashed');  // 枠を点線に戻す
    });

    // #2ドラッグしている要素がドロップされたとき
    $('#drop_area').on('drop', function (event) {
        event.preventDefault();
    
        // jQueryの場合、イベントからdataTransferフィールドを受け取ることができないため、
        // originalEventを使用している
        $('#profile_image')[0].files = event.originalEvent.dataTransfer.files;
    
        // 画像が複数選択されていた場合
        if ($('#profile_image')[0].files.length > 1) {
            alert('アップロードできる画像は1つだけです');
            $('#profile_image').val('');
            return;
        }

        handleFiles($('#profile_image')[0].files);

    });
    // #2

    // 選択された画像ファイルの操作
    function handleFiles(files) {
        var file = files[0];
        var reader = new FileReader();

        // 画像ファイル以外の場合は何もしない
        // A.indexOf(B)はAにBの値を含むかを判別！含む場合は0以上の値を返し、含まない場合は-1を返す
        if(file.type.indexOf("image") < 0){
            return false;
        }

        reader.onload = (function (file) {  // 読み込みが完了したら
            
            // previeクラスのdivにimgタグを以下のプロパティ付きで実装
            return function(e) {
                $('.preview').empty();
                $('.preview').append($('<img>').attr({
                    src: e.target.result, // readAsDataURLの読み込み結果がresult
                    width: "350px",
                    height: "250px",
                    class: "preview",
                    title: file.name
                }));  // previewに画像を表示
            };   
        })(file);

        reader.readAsDataURL(file); // ファイル読み込みを非同期でバックグラウンドで開始

        // 削除フラグを解除
        $('#img_delete').val(0);
    }


    // drop_area以外でファイルがドロップされた場合、ファイルが開いてしまうのを防ぐ
    $(document).on('dragenter', function (event) {
        event.stopPropagation();
        event.preventDefault();
    });
    $(document).on('dragover', function (event) {
        event.stopPropagation();
        event.preventDefault();
    });
    $(document).on('drop', function (event) {
        event.stopPropagation();
        event.preventDefault();
    });
});
// @1


// @2 プレビュー画像削除時のメッセージ設定
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

        $('#drop_area').removeClass('solid'); // 枠を点線に戻す

        $('#fade_msg').fadeIn("slow", function () {
            //コールバックで3秒後にフェードアウト	
            $(this).delay(3000).fadeOut("slow");
        });

        // 削除フラグを設定
        $('#img_delete').val(1);
        
    });
});
// @2

// @3 モーダルの設定
$(function(){
    // #1 ユーザ削除用のモーダルに関する設定
    $('.btn-delete').on('click', function(){
        // destroyアクションへsubmitするURLを取得
        var url = $(this).data('url');
        $('#delete_message_name').html($(this).data('name')+"さんのアカウントを削除しますか？");
        $('#delete_user_id').val($(this).data('id'));
        $('#modalForm').modal('show');
        // destroyアクションのURLをモーダル側のフォームにセット
        $('#form1').attr('action',url);
    })
    // #1


    // #2 csvインポート用のモーダルに関する設定
    $('.btn-import').on('click', function(){
        // importアクションへsubmitするURLを取得

        var import_url;

        // 現在のURLによってインポート先を判別
        if (window.location.pathname === "/users") {
            import_url = $(this).data('import_url');
        }
        if (window.location.pathname === "/companies") {
            import_url = $(this).data('import_url2');
        }
        
        $('#csvForm').modal('show');

        // importアクションのURLをモーダル側のフォームにセット
        $('#csv_form').attr('action',import_url);
    })
    // #2
});
// @3

/* ZipCloudの利用(郵便番号の自動検索・入力機能) */
var getAddName = function( $addNum ){
	var _zipcloudAPI = document.body.appendChild(document.createElement("script"));
		_zipcloudAPI.src = "http://zipcloud.ibsnet.co.jp/api/search?zipcode=" + $addNum + "&callback=getAddNameByZipcloudAPI";
	document.body.removeChild(_zipcloudAPI);
};
var getAddNameByZipcloudAPI = function( $getAdd ){
	var _addFormatted  = "";
	if($getAdd.status == 200){
			_addFormatted += $getAdd.results[0].address1; // 都道府県名
			_addFormatted += $getAdd.results[0].address2; // 市町村名
			_addFormatted += $getAdd.results[0].address3; // 町域名
	}
	document.getElementById("address").value = _addFormatted;
};
