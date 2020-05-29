<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--Mobile first-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>HomeWork</title>
    
    <meta name="description" content="Free Admin Template Based On Twitter Bootstrap 3.x">
    <meta name="author" content="">
    
    <meta name="msapplication-TileColor" content="#5bc0de" />
    <meta name="msapplication-TileImage" content="assets/img/metis-tile.png" />
    
    <!-- Bootstrap -->
    {{-- <link rel="stylesheet" href="lib/css/bootstrap.css"> --}}
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    {{-- <link rel="stylesheet" href="lib/css/font-awesome.css"> --}}
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    
    <!-- Metis core stylesheet -->
    {{-- このファイルがサイトの全体デザインを大きく設定している --}}
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    
    <!-- metisMenu stylesheet -->
    <link rel="stylesheet" href="{{ asset('lib/css/metisMenu.css') }}">
    
    <!-- onoffcanvas stylesheet -->
    <link rel="stylesheet" href="{{ asset('lib/css/onoffcanvas.css') }}">

    <!-- theme stylesheet -->
    <link rel="stylesheet/less" href="{{ asset('less/theme.less') }}">

    <!-- style-switcher stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/style-switcher.css') }}">
    
    <!-- animate.css stylesheet -->
    <link rel="stylesheet" href="{{ asset('lib/css/animate.css') }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    {{-- 追加 Styles --}}
    <link rel="stylesheet" href="{{ asset('css/origin.css') }}">

    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 
    <script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>

</head>
<body>
    <div class="bg-dark dk" id="wrap">
        <div id="top">
            {{-- 最上部のメニューバーデザイン --}}
            <nav class="navbar navbar-inverse navbar-static-top">
                <div class="container-fluid">
            
            
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <header class="navbar-header">
            
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        {{-- TOPのタイトルをクリックすればホーム画面にリダイレクト --}}
                        <a href="#" class="navbar-brand"><img src="assets/img/logo.png" alt=""></a>
            
                    </header>
            
            
                    {{-- 最上部のメニューバー --}}
                    <div class="collapse navbar-collapse navbar-ex1-collapse">
            
                        <!-- .nav -->
                        <ul class="nav navbar-nav">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class='dropdown '>
                                {{-- <a href="" class="dropdown-toggle" data-toggle="dropdown">
                                    マイページ <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="">編集</a></li>
                                </ul> --}}
                                <a href="{{ route('users.show', ['user' => Auth::user()->id]) }}">マイページ</a>
                            </li>
                        </ul>
                        <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav navbar-right">
                            <li>ようこそ<span style="color: rgb(104, 174, 231)">{{ Auth::user()->email }}</span>さん</li>
                        </ul>
                        <!-- /.nav -->
                    </div>
                </div>
                <!-- /.container-fluid -->
            </nav>
            <!-- /.navbar -->
    
                {{-- 検索機能のデザイン --}}
                <header class="head">
                        <div class="search-bar">
                            <form class="main-search" action="">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Live Search ...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary btn-sm text-muted" type="button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <!-- /.search-bar -->
                    <div class="main-bar">
                        <h3>
                            @yield('h3')
                        </h3>
                        {{-- バリデーションエラーのメッセージを表示 --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    {{-- メッセージがあればメッセージ用のモーダルを表示git --}}
                    @if (session('message'))
                        <script>
                            $(function(){
                                $('#messageForm').modal('show');
                            });
                        </script>
                    @endif
                    {{-- メッセージの表示用モーダル --}}
                    <div class="modal fade" id="messageForm" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                </div>
                                
                                <div class="modal-body">
                        
                                    <div class="form-group">
                                        <p style="font-size: 20px; color: rgb(0, 140, 255)" id="message_name">{{ session('message') }}</p>
                                    </div>
                        
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                                </div>
                            </div>
                        </div>
                    </div> 

                    <!-- /.main-bar -->
                </header>
                <!-- /.head -->
        </div>
        <!-- /#top -->
    
        {{-- サイド側のデザイン --}}
        <div id="left">
            {{-- サインインしたアカウントの表示 --}}
            <div class="media user-media bg-dark dker">
                <div class="user-media-toggleHover">
                    <span class="fa fa-user"></span>
                </div>
                <div class="user-wrapper bg-dark">
                    <a class="user-link" href="{{ route('users.show', ['user' => Auth::user()->id]) }}">
                        @if ((Auth::user()->profile_image) != null)
                            <img class="media-object img-thumbnail user-img" alt="User Picture" src="{{ asset('storage/images/'. Auth::user()->id .'/'. Auth::user()->profile_image) }}" width="100" height="60">
                        @else
                            <img class="media-object img-thumbnail user-img" alt="User Picture" src="{{ asset('storage/images/noImage/Noimage_image.png') }}" width="100" height="60">
                        @endif
                        {{-- <span class="label label-danger user-label">16</span>　→　何かしらのラベル --}}
                    </a>
            
                    <div class="media-body">
                        <h5 class="media-heading">プロフィール</h5>
                        <ul class="list-unstyled user-info">
                            <li><a href="">{{ Auth::user()->email }}</a></li>
                            <li>最終ログイン日時 : <br>
                                <small><i class="fa fa-calendar"></i>&nbsp;{{ Auth::user()->login_time }}</small>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- media user-media bg-dark dker -->
            
            {{-- サイド側のメニューデザイン --}}
            <!-- #menu -->
            <ul id="menu" class="bg-blue dker">
                <li class="nav-header">Menu</li>
                <li class="nav-divider"></li>
                <li class="">
                <a href="{{ route('home') }}">
                    <i class="fa fa-home"></i><span class="link-title">&nbsp;Home</span>
                </a>
                </li>
                <li class="">
                <a href="{{ route('users.index') }}">
                    <i class="fas fa-users"></i><span class="link-title">&nbsp;ユーザ一覧</span>
                </a>
                </li>
                <li class="">
                <a href="{{ route('users.create') }}">
                    <i class="fas fa-user-edit"></i><span class="link-title">&nbsp;ユーザ作成</span>
                </a>
                </li>
                <li class="">
                <a href="javascript:;">
                    <i class="fas fa-user-lock"></i>
                    <span class="link-title">マイページ</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="collapse">
                    <li>
                        <a href="{{ route('users.show', ['user' => Auth::user()->id]) }}">
                            <i class="fa fa-angle-right"></i>&nbsp; マイページ
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.edit', ['user' => Auth::user()->id]) }}">
                            <i class="fa fa-angle-right"></i>&nbsp; マイページ編集 
                        </a>
                    </li>
                    <li>
                        <a href="#" id="logout">
                            <i class="fa fa-angle-right"></i>&nbsp; ログアウト 
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
                </li>
            </ul>
            <!-- /#menu -->
        </div>
        <!-- /#left -->

        {{-- ＜＜＜　yield　＞＞＞ --}}
        <div id="content">
            <div class="outer mt-2">
                <div class="inner bg-light lter">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <!-- /#bg-dark dk -->

<!--jQuery -->
<script src="{{ asset('lib/js/jquery.js') }}"></script>

<!--Bootstrap -->
<script src="{{ asset('lib/js/bootstrap.js') }}"></script>

<!-- MetisMenu -->
<script src="{{ asset('lib/js/metisMenu.js') }}"></script>
<!-- onoffcanvas -->
<script src="{{ asset('lib/js/onoffcanvas.js') }}"></script>
<!-- Screenfull -->
<script src="{{ asset('lib/js/screenfull.js') }}"></script>

<!-- Metis core scripts -->
<script src="{{ asset('js/core.js') }}"></script>
<!-- Metis demo scripts -->
<script src="{{ asset('js/app.js') }}"></script>


<script src="{{ asset('js/style-switcher.js') }}"></script>
    
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>

{{-- 追加Scripts --}}
<script src="{{ asset('js/origin.js') }}"></script>

{{--＜＜＜ yield ＞＞＞--}}
@yield('scripts')

{{-- ログアウト処理 --}}
@if(Auth::check())
<script>
  document.getElementById('logout').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('logout-form').submit();
  });
</script>
@endif

</body>
</html>
