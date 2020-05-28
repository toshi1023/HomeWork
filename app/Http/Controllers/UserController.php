<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// バリデーションの拡張機能を追加
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Company;


class UserController extends Controller
{
    // ユーザ一覧アクション
    public function index()
    {

        // ユーザデータの取得(削除フラグfalseに絞って取得)
        $users = User::with('company')->where('del_flg', 0)->latest('updated_at')->get();

        return view('users.index', [
            'users' => $users,
        ]);
    }


    // 作成アクション
    public function create()
    {

        $companies = Company::all();

        return view('users.create', [
            'companies' => $companies,
        ]);
    }


    // 作成データの登録アクション
    public function store(Request $request)
    {

        // 保存に使用するインスタンスを作成
        $user = new User();

        // メールアドレスの一意チェック
        $request->validate([
            // usersテーブルのemailカラムについて、
            // del_flgが0のレコードを対象に、一意チェックする.
            'email' => [Rule::unique('users', 'email')->where('del_flg', 0)]
        ]);

        // 画像がセットされていたら、画像保存用の処理へ
        if(!empty($_FILES['profile_image']['name'])){

            // フォルダの名前取得
            $upload_name = $_FILES['profile_image']['name'];

            // アップロードしたファイルのバリデーション設定
            $this->validate($request, [
                'profile_image' => [
                    'required',
                    'file',
                    'image',
                    'mimes:jpeg,png',
                ]
            ]);

            // 削除フラグがfalseの場合にアップロードした画像をDBに保存
            if($request->img_delete == 1){
                $user->profile_image = null;
            } else {
                $user->profile_image = $upload_name;
            }
            // データの登録(画像以外)
            $user->last_name = $request->last_name;
            $user->first_name = $request->first_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->company_id = $request->company_id;
            $user->memo = $request->memo;

            // データを保存
            $user->save();

            // 画像の保存先フォルダ名を変数にセット(登録されたユーザIDをフォルダ名に設定)
            $folder_path = $user->id;

            // アップロードするディレクトリ名を指定
            $up_dir = 'images/' . $folder_path;

            // アップロードに成功しているか確認
            if ($request->file('profile_image')->isValid([])) {
                
                $filename = $request->file('profile_image')->storeAs($up_dir, $upload_name, 'public');

                return redirect()->to('/users')->with('message', 'プロフィール画像付きのユーザを作成しました。');

            } else {

                return redirect()->to('/users')->with('message', 'イメージ画像の登録に失敗しました。');
            }
        } else {
            // データの登録(画像はNoimage_image.pngでDBに登録)
            $user->profile_image = null;
            $user->last_name = $request->last_name;
            $user->first_name = $request->first_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->company_id = $request->company_id;
            $user->memo = $request->memo;

            // データを保存
            $user->save();

            return redirect()->route('users.index')->with('message', 'ユーザを作成しました');
        }
    }


    // マイページアクション
    public function show(User $user)
    {
        
        $company = Company::find($user->company_id);

        return view('users.show', [
            'user' => $user,
            'company' => $company,
        ]);
    }


    // 編集アクション
    public function edit(User $user)
    {

        // 選択されたユーザの情報と会社情報を取得
        $user = User::find($user->id);
        $companies = Company::all();

        return view('users.edit', [
            'user' => $user,
            'companies' => $companies,
        ]);
    }


    // 編集データの更新アクション
    public function update(Request $request, User $user)
    {
        $user = User::find($user->id);


        // メールアドレスの一意チェック(自分以外のデータと比較するように設定)
        $request->validate([
            'email' => [Rule::unique('users', 'email')->ignore($user->id)->where('del_flg', 0)]
        ]);
        
        if($_FILES['profile_image']['name'] != null){
       
            // ファイルの名前取得
            $upload_name = $_FILES['profile_image']['name'];

            // フォルダ名の取得
            $folder_path = $user->id;

            // アップロードしたファイルのバリデーション設定
            $this->validate($request, [
                'profile_image' => [
                    'required',
                    'file',
                    'image',
                    'mimes:jpeg,png',
                ]
            ]);

            // 削除フラグがfalseの場合にアップロードした画像をDBに保存
            if($request->img_delete == 1){
                $user->profile_image = null;
            } else {
                $user->profile_image = $upload_name;
            }
            // データの登録(画像以外)
            $user->last_name = $request->last_name;
            $user->first_name = $request->first_name;
            $user->email = $request->email;
            // 新しいパスワードの入力がない限り保存しない
            if(($request->password) != null){
                $user->password = Hash::make($request->password);
            }
            $user->company_id = $request->company_id;
            $user->memo = $request->memo;

            // データを保存
            $user->save();

            // アップロードするディレクトリ名を指定
            $up_dir = 'images/' . $folder_path;

            // アップロードに成功しているか確認
            if ($request->file('profile_image')->isValid([])) {
                
                $request->file('profile_image')->storeAs($up_dir, $upload_name, 'public');
                
                return redirect()->to('/users')->with('message', 'プロフィール画像含めてユーザを編集しました。');

            } else {

                return redirect()->to('/users')->with('message', 'イメージ画像の登録に失敗しました。');
            }
        } else {
            // データの登録(削除フラグのついていない画像以外)
            if($request->img_delete == 1){
                $user->profile_image = null;
            }
            $user->last_name = $request->last_name;
            $user->first_name = $request->first_name;
            $user->email = $request->email;
            // 新しいパスワードの入力がない限り保存しない
            if(($request->password) != null){
                $user->password = Hash::make($request->password);
            }
            $user->company_id = $request->company_id;
            $user->memo = $request->memo;

            // データを保存
            $user->save();

            return redirect()->route('users.index')->with('message', 'ユーザを編集しました');
        }
    }


    // データの削除アクション
    public function destroy(User $user)
    {
        // 削除フラグをtrueに変更
        $user = User::find($user->id);
        $user->del_flg = 1;
        $user->save();
        return redirect()->route('users.index')->with('message', 'ユーザを削除しました');
    }

        
}
