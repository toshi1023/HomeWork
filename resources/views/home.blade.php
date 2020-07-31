@extends('layouts.app')

@section('h3')
<i class="fa fa-home"></i>&nbsp;
    HomeWork
@endsection

@section('content')
    {{-- TOPページのメインコンテンツデザイン --}}
    <div class="col-lg-12">
        <h1>Welcome to HomeWork !</h1>
        <p>このページではユーザと会社の登録と削除を実行することが出来ます</p>
{{-- コードの記載位置によって実際の文章の表示位置が変わる --}}
<pre>
    <code class="lang-shell">
    ユーザの作成方法
1 サイドメニューの[ユーザ作成]ボタンをクリック
2 フォームに必須項目を入力
3 登録ボタンを押すとユーザが保存される

$ 登録後は[ユーザ一覧]にて確認しよう
    </code>
</pre>
<pre>
    <code class="lang-shell">
    会社の追加方法
1 サイドメニューの[会社登録]ボタンをクリック
2 フォームに必須項目を入力
3 登録ボタンを押すと会社が登録される

$ 登録後は[会社一覧]にて確認しよう
    </code>
</pre>
    </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.inner -->
        </div>
        <!-- /.outer -->
    </div>
    <!-- /#content -->
    <br>
    <br>
    <br>
    <!-- /#wrap -->
    <footer class="Footer bg-dark dker">
        <p>2020 &copy; HomeWork Created By Toshiya Murasaki</p>
    </footer>
    <!-- /#footer -->
    <!-- #helpModal -->
    <div id="helpModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body">
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                        aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                        culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- /#helpModal -->
@endsection
