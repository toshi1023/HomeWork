<?php

namespace App\Services\CsvServices;

use App\Models\User;
use App\Models\Company;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use SplFileObject; // useしないと 自動的にnamespaceのパスが付与される
use Validator;

class UserCsvService implements CsvInterface
{
    protected $user;
    protected $company;
    protected $SqlFileObject;

    public function __construct(User $user, Company $company)
    {
        // Modelをインスタンス化
        $this->user = $user;
        $this->company = $company;
    }

    /* ユーザデータのCsvをインポートする処理 */
    public function import($request)
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

        // DBに登録済みのメールアドレスを取得
        $registerd_email = $this->user->select('email')->where('del_flg', 0)->get();

        try  {
            \DB::beginTransaction();

            $time = 0;

            foreach ($file as $row)
            {
                // ヘッダを除く1行目の各項目に値が1件も入っていない場合エラー処理
                if ($row === [null] && $time === 1) {
                    $validator = 'データが存在しません';
                    throw new \Exception;
                }

                // 最終行の処理(最終行が空っぽの場合の対策)
                if ($row === [null]) continue; 
                
                // 1行目はヘッダのため2行目から処理を回す
                if ($row_count > 1)
                {

                    // CSVの文字コードがSJISのためUTF-8に変更
                    $email = mb_convert_encoding($row[0], 'UTF-8', 'SJIS');
                    $password = mb_convert_encoding($row[1], 'UTF-8', 'SJIS');
                    $first_name = mb_convert_encoding($row[2], 'UTF-8', 'SJIS');
                    $last_name = mb_convert_encoding($row[3], 'UTF-8', 'SJIS');
                    $company_id = mb_convert_encoding($row[4], 'UTF-8', 'SJIS');
                    $memo = mb_convert_encoding($row[5], 'UTF-8', 'SJIS');

                    $data = [
                        'email' => $email,
                        'password' => $password,
                        'first_name' => $first_name,
                        'last_name' => $last_name, 
                        'company_id' => $company_id, 
                        'memo' => $memo, 
                    ];

                    // バリデーションで使用するカスタムメッセージを設定
                    $messages = [
                        'required' => $row_count.'行目の :attributeは入力が必須です',
                        'email' => $row_count.'行目の :attributeはメール形式で入力してください',
                    ];
                    // バリデーションチェック
                    // ※bailルールにより最初のバリデーションに失敗したら、
                    // 残りのバリデーションルールの判定を停止する
                    $validator = Validator::make($data,[
                        'email' => 'bail|required|string|email|max:255',
                        'password' => 'required|string|max:255',
                    ], $messages);

                    // バリデーションチェックに引っかかったときは
                    // エラーメッセージをセッションに保存
                    if ($validator->fails()) {
                        
                        // バリデーションエラーが発生すれば登録したデータをすべてロールバック
                        throw new \Exception;
                    }

                    // パスワードをハッシュ化
                    $data['password'] = Hash::make($data['password']);

                    $update = null;
            
                    // 既存のメールアドレスとの重複チェック
                    foreach ($registerd_email as $email_data)
                    {
                        // $registerd_emailはデータを->get()つまりオブジェクト型で取得しているため、
                        // $email_data->emailでとらないとstring型でとれない
                        if($data['email'] == $email_data->email){
                            $update = $data['email'];
                            break;
                        }
                    }
                    
                    // メールアドレスがDBに登録されていたらupdate
                    // されていなかったらinsertを実行
                    if(isset($update)){
                        $this->user->where('email', '=', $update)->update($data);
                    } else {
                        // 1件ずつデータをインポート
                        $this->user->insert($data);
                    }
                    
                }
                
                $time++;
                $row_count++;
            }

            // 処理が正常に完了した場合はコミット
            \DB::commit();

        } catch (\Exception $e) {

            \DB::rollback();
            
            return [false, $validator];
        }

        // 登録件数を変数に代入
        $row_count = $row_count - 2;

        return [true, $row_count];
    }

    /* ユーザデータのCsvをエクスポートする処理 */
    public function export($request)
    {
        $export_date = date('Y/m/d H:i:s'); // エクスポート時の日時を取得

        $headers = [ // Httpヘッダのヘッダー情報
            'Content-type' => 'text/csv', //CSV形式ですよー
            'Content-Disposition' => 'attachment; filename=users_'. $export_date .'.csv',  //添付ファイルありますよー、ファイル名は***.csvですよー
            'Pragma' => 'no-cache', //キャッシュ(情報保存)しないですよー
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0', //キャッシュ期限切れなら確認してやー
            'Expires' => '0', //キャッシュの有効期限は0で設定（キャッシュは無し）
        ];

        // エクスポートして結果をcallback変数に代入
        $callback = function() 
        {
            
            $createCsvFile = fopen('php://output', 'w'); //ファイル作成(第2引数のwは書き込み専用を指す)
            
            $columns = [ //1行目の情報
                'email',
                'password',
                'first_name',
                'last_name',
                'company_id',
                'memo',
                'login_time',
                'del_flg',
            ];
 
            mb_convert_variables('SJIS-win', 'UTF-8', $columns); //文字化け対策(1行ずつ文字コードを『UTF-8』から『SJIS』に変更を対応)
    
            fputcsv($createCsvFile, $columns); //1行目の情報を追記
 
            $users_data = $this->user  //データベースからデータ取得
                               ->select(['email','password','first_name','last_name','company_id','memo','login_time','del_flg'])
                               ->get();
        
            foreach ($users_data as $row) {  //データを1行ずつ回す
                $csv = [
                    $row->email,  //オブジェクトなので -> で取得
                    $row->password,
                    $row->first_name,
                    $row->last_name,
                    $row->company_id,
                    $row->memo,
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