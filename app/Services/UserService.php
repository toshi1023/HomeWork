<?php

namespace App\Services;

use App\Models\User;
use App\Models\Company;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserService implements UserInterface
{
    protected $user;
    protected $company;
    protected $query;

    public function __construct(User $user, Company $company)
    {
        $this->user = $user;
        $this->company = $company;
        $this->query = $user::query();
    }

    /* 
     * ユーザを全件取得(論理削除は含まない)
     * 
     **/
    public function indexQuery()
    {
        
        $this->query
             ->leftjoin('companies', 'companies.id', '=', 'users.company_id')
             ->select('users.*', 'companies.name')
             ->where('users.del_flg', 0)
             ->orderBy('users.updated_at', 'desc');

        return $this->query;
    }

    /* ログインユーザの情報を取得 */
    public function showQuery()
    {
        $this->query->where('id', \Auth::user()->id);

        return $this->query;
    }

     /* 編集対象となるユーザの情報を取得 */
     public function editQuery($request)
     {
         $this->query->where('id', $request);
 
         return $this->query;
     }

    /* ファイルアップロード処理 */
    public function fileUpload($request, $filename)
    {
        if ($request->file('profile_image')->isValid([])) {
            // 画像の保存先フォルダ名を変数にセット(登録されたユーザIDをフォルダ名に設定)
            $folder_path = $request->email;

            // アップロードするディレクトリ名を指定
            $up_dir = 'images/' . $folder_path;

            // アップロード処理
            $request->file('profile_image')->storeAs($up_dir, $filename, 'public');
        }
        
        return;

    }

    /* ユーザのDB保存処理
     * 第1引数:入力フォームから送信されたデータ
     * 第2引数:ファイル名
     * 第3引数:更新対象データ(新規保存の場合はnull)
    */
    public function save($request, $filename=null, $user_data=null)
    {
        try{

            // アップデートフラグがtrueの場合はユーザ情報を取得
            if (!empty($user_data)) {
                $this->user = $this->user::find($user_data->id);
            }

            // ファイル名がnullの場合は画像の保存処理を実行しない
            if ($filename !== null) {
                
                // 削除フラグがfalseの場合にアップロードした画像をDBに保存
                if($request->img_delete == 1){
                    $this->user->profile_image = null;
                } else {
                    $this->user->profile_image = $filename;
                }

                // ファイルアップロード処理
                $this->fileUpload($request, $filename);
            }

            // データの登録(画像以外)
            $this->user->last_name  = $request->last_name;
            $this->user->first_name = $request->first_name;
            $this->user->email      = $request->email;
            $this->user->company_id = $request->company_id;
            $this->user->memo       = $request->memo;

            // データ更新の場合は新しいパスワードの入力がない限り保存しない
            if(!empty($user_data)){
                if(($request->password) != null) {
                    $this->user->password   = Hash::make($request->password);
                }
                
            } else {
                $this->user->password   = Hash::make($request->password);
            }

            // データを保存
            $this->user->save();

            return true;

        } catch (\Exception $e) {
            \Log::error('database register error:'.$e->getMessage());
            return false;
        }
    }    

    /* 
     * データの削除処理
     * 引数：削除対象のユーザデータ
     * */
    public function destroy($request)
    {
        try{
            
            // 変更対象ユーザを取得
            $user = $this->user::find($request);

            // 論理削除フラグをtrueに変更
            $user->del_flg = 1;

            $user->save();

            return true;

        } catch (\Exception $e) {
            \Log::error('database delete error:'.$e->getMessage());
            return false;
        }
    }
}