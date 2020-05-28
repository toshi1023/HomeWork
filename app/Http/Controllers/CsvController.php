<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use SplFileObject; // useしないと 自動的にnamespaceのパスが付与される

class CsvController extends Controller
{
    public function import(Request $request)
    {
        // ロケールを設定(日本語に設定)
        setlocale(LC_ALL, 'ja_JP.UTF-8');
    
        // アップロードしたファイルを取得
        // 'csv_file' はビューの inputタグのname属性
        $uploaded_file = $request->file('csv_import');

        // アップロードしたファイルの絶対パスを取得
        $file_path = $request->file('csv_import')->path($uploaded_file);


        // SplFileObjectを生成
        $file = new SplFileObject($file_path);
    
        // SplFileObject::READ_CSV が最速らしい(CSVとして行を読み込む)
        $file->setFlags(SplFileObject::READ_CSV);
    
        // インポート用のテーブルで1行目はヘッダのため、インポートを除外するために1を設定
        $row_count = 1;

        // 登録用のインスタンスを作成
        $user = new User();
        
        foreach ($file as $row)
        {
    
            // 最終行の処理(最終行が空っぽの場合の対策)
            if ($row === [null]) continue; 
            
            // 1行目はヘッダのため2行目から処理を回す
            if ($row_count > 1)
            {

                // CSVの文字コードがSJISなのでUTF-8に変更
                $email = mb_convert_encoding($row[0], 'UTF-8', 'SJIS');
                $password = mb_convert_encoding($row[1], 'UTF-8', 'SJIS');
                $first_name = mb_convert_encoding($row[2], 'UTF-8', 'SJIS');
                $last_name = mb_convert_encoding($row[3], 'UTF-8', 'SJIS');
                $company_id = mb_convert_encoding($row[4], 'UTF-8', 'SJIS');
                $memo = mb_convert_encoding($row[5], 'UTF-8', 'SJIS');
                $del_flg = mb_convert_encoding($row[6], 'UTF-8', 'SJIS');
                
            
                

                // 1件ずつデータをインポート
                $user->email = $email;
                $user->password = Hash::make($password);
                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->company_id = $company_id;
                $user->memo = $memo;
                $user->del_flg = $del_flg;
                
            }
    
            $row_count++;
    
        }

        $user->save();

        // 登録件数を変数に代入
        $row_count = $row_count - 2;

        return view('csv.import', [
            'row_count' => $row_count,
        ]);
    }

    public function export(Request $request)
    {

        $export_date = date('Y/m/d H:i:s'); // エクスポート時の日時を取得

        $headers = [ // Httpヘッダのヘッダー情報
            'Content-type' => 'text/csv', //CSV形式ですよー
            'Content-Disposition' => 'attachment; filename=users_'. $export_date .'.csv',  //添付ファイルありますよー、ファイル名は***.csvですよー
            'Pragma' => 'no-cache', //キャッシュ(情報保存)しないですよー
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0', //キャッシュ期限切れなら確認してやー
            'Expires' => '0', //キャッシュの有効期限は0で設定（キャッシュは無し）
        ];

        $callback = function() 
        {
            
            $createCsvFile = fopen('php://output', 'w'); //ファイル作成(第2引数のwは書き込み専用を指す)
            
            $columns = [ //1行目の情報
                'email',
                'password',
                'first_name',
                'last_name',
                'company_id',
                'login_time',
                'del_flg',
            ];
 
            mb_convert_variables('SJIS-win', 'UTF-8', $columns); //文字化け対策(1行ずつ文字コードを『UTF-8』から『SJIS』に変更を対応)
    
            fputcsv($createCsvFile, $columns); //1行目の情報を追記
 
            $users_table = \DB::table('users');  // データベースのテーブルを指定(名前空間の設定から、先頭に\を忘れないように気を付ける)
 
            $users_data = $users_table  //データベースからデータ取得
                ->select(['email','password','first_name','last_name','company_id','login_time','del_flg'])->get();
        
            foreach ($users_data as $row) {  //データを1行ずつ回す
                $csv = [
                    $row->email,  //オブジェクトなので -> で取得
                    $row->password,
                    $row->first_name,
                    $row->last_name,
                    $row->company_id,
                    $row->login_time,
                    $row->del_flg,
                ];
 
                mb_convert_variables('SJIS-win', 'UTF-8', $csv); //文字化け対策
 
                fputcsv($createCsvFile, $csv); //ファイルに追記する
            }
            fclose($createCsvFile); //ファイル閉じる
        };
        
        return response()->stream($callback, 200, $headers); //ここで実行

    }
}
