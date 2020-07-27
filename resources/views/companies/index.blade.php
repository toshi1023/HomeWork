@extends('layouts.app')

@section('h3')
    <i class="far fa-building"></i>
    会社一覧
        <div class="form-inline">
            {{-- csvエクスポート・インポート処理 --}}
            <button class="btn btn-danger btn-import pull-right" data-import_url="">csvをインポート</button>
            
            <form action="{{ route('csv.export') }}" method="get">
                <button type="submit" class="btn btn-primary btn-export pull-right">csvをエクスポート</button>
            </form>
        </div>

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
                        会社名
                    </th>
                    <th>
                        郵便番号
                    </th>
                    <th>
                        住所
                    </th>
                    <th>
                        電話番号
                    </th>
                    <th>
                        Map URL
                    </th>
                    <th>
                        操作
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($companies as $company)
                    <tr><td>{{ $company->id }}</td>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->post_no }}</td>
                        <td>{{ $company->address }}</td>
                        <td>{{ $company->tel }}</td>
                        <td>{{ $company->map_url }}</td>
                        
                        <td>
                            <a href="" type="button" class="btn btn-primary">{{ __('編集') }}</a>
                                /
                            
                                
                            <button type="button" class="btn btn-danger btn-delete">{{ __('削除') }}</button>
                            
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
