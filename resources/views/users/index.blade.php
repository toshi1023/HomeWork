@extends('layouts.app')

@section('h3')
    <i class="fas fa-users"></i>
    &nbsp;ユーザ一覧
        {{-- csvエクスポート・インポート処理 --}}
        <button class="btn btn-danger btn-import pull-right" data-import_url="{{ route('csv.import') }}">csvをインポート</button>
        
        <form action="{{ route('csv.export') }}" method="get">
            <button type="submit" class="btn btn-primary btn-export pull-right" onclick="return confirm('データを出力しますか？')">csvをエクスポート</button>
        </form>

    {{-- @1 csv用モーダル実装 --}}
    <div class="modal fade" id="csvForm" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <form action="" method="post" id="csv_form" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="ModalLabel" style="color: red">csvファイルのインポート</h4>
                    </div>
                    
                        <div class="modal-body">
                
                            <div class="form-group">
                                {{-- インポートファイルの選択 --}}
                                <input type="file" id="csv_import" name="csv_import" class="form-control-file">
                            </div>
                
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                        <button type="submit" class="btn btn-danger btn-commit" id="">インポート</button>
                        </div>
                </div>
            </div>
        </form>
    </div>
    {{-- @1 --}}
@endsection

@section('content')
{{-- コンテンツ内に収めるためにもグリッドシステムで要調整 --}}
<div class="row ml-md-2">
    <div class="col-sm-offset-1 col-md-10">
        {{-- 同section内に記述しないと反映されない --}}
        <script>
            jQuery(function($){
                // 言語を日本語化
                $.extend( $.fn.dataTable.defaults, { 
                language: {
                    url: "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Japanese.json"
                } 
            }); 
                // DataTableを反映
                $("#homework-table").DataTable({
                    columnDefs: [
                        // 6列目を消す(visibleをfalseにすると消える)
                        // { targets: 5, visible: false },
                    ]
                    
                    // ページ遷移後も状態を保存する機能
                    // stateSave: true,
                });
            });
        </script>
        {{-- ユーザ一覧を表示(DataTablesを使用) --}}
        <table id="homework-table" class="table">
            <thead>
                <tr>
                    <th>
                        No
                    </th>
                    <th>
                        苗字
                    </th>
                    <th>
                        名前
                    </th>
                    <th>
                        会社名
                    </th>
                    <th>
                        メールアドレス
                    </th>
                    <th>
                        プロフィール画像
                    </th>
                    <th>
                        操作
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr><td>{{ $user->id }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->company->name }}</td>
                        <td>{{ $user->email }}</td>
                        @if (($user->profile_image) != null)
                            <td><img src="{{ asset('storage/images/'. $user->id .'/'. $user->profile_image) }}" width="200" height="120"></td>
                        @else
                            <td><img src="{{ asset('storage/images/noImage/Noimage_image.png') }}" width="200" height="120"></td>
                        @endif
                        
                            <td>
                                <a href="{{ route('users.edit', ['user' => $user->id]) }}" type="button" class="btn btn-primary">{{ __('編集') }}</a>
                                    /
                                
                                    
                                <button type="button" class="btn btn-danger btn-delete" data-name="{{ $user->last_name }}" data-id="{{ $user->id }}" data-url="{{ route('users.destroy', ['user' => $user->id]) }}" data-message='本当に削除しますか?'>{{ __('削除') }}</button>
                                
                            </td>
                    </tr>
                @endforeach

                {{-- @2 モーダル削除機能実装 --}}
                <div class="modal fade" id="modalForm" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
                    {{-- @3 削除フォーム --}}
                    <form role="form" id="form1" action="" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <h4 class="modal-title" id="ModalLabel" style="color: red">ユーザ削除</h4>
                                </div>
                                
                                    <div class="modal-body">
                            
                                        <div class="form-group">
                                            <p style="font-size: 20px" id="delete_message_name"></p>
                                            
                                        </div>
                            
                                    </div>
                                    <div class="modal-footer">
                                    <input type="hidden" name="user_id" value="" id="delete_user_id">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                                    <button type="submit" class="btn btn-danger btn-commit" id="">削除する</button>
                                    </div>
                                
                            </div>
                        </div>
                    </form>
                    {{-- @3 --}}
                </div>      
                {{-- @2 --}}
            </tbody>
        </table>
    </div>
</div>
@endsection
