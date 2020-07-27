<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// バリデーションの拡張機能を追加
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use App\Models\User;
use App\Models\Company;
use App\Services\CompanyInterface;
use App\Services\UserInterface;

class UserController extends Controller
{
    protected $companies;
    protected $users;
    protected $messages;

    public function __construct(MessageBag $messages)
    {
        // CompanyServiceをインスタンス化
        $this->companies = app()->make(CompanyInterface::class);

        // UserServiceをインスタンス化
        $this->users = app()->make(UserInterface::class);
        
        // セッションに保存するメッセージ用メソッドをインスタンス化
        $this->messages = $messages;
    }

    /* ユーザ一覧アクション */
    public function index()
    {
        // ユーザデータの取得(削除フラグfalseに絞って取得)
        $users = $this->users->indexQuery($index=true)->get();

        return view('users.index', [
            'users' => $users,
        ]);
    }


    /* 作成アクション */
    public function create()
    {
        // 会社データをすべて取得(削除フラグfalseに絞って取得)
        $companies = $this->companies->allQuery()->get();

        return view('users.create', [
            'companies' => $companies,
        ]);
    }


    /* 作成データの登録アクション */
    public function store(Request $request)
    {

        // メールアドレスの一意チェック
        $request->validate([
            // usersテーブルのemailカラムについて、del_flgが0のレコードを対象に、一意チェックする
            'email' => [Rule::unique('users', 'email')->where('del_flg', 0)]
        ]);

        // 画像がセットされていたら、画像保存用の処理へ
        if(!empty($_FILES['profile_image']['name'])){

            // ファイル名前取得
            $filename = $_FILES['profile_image']['name'];

            // アップロードしたファイルのバリデーション設定
            $this->validate($request, [
                'profile_image' => [
                    'required',
                    'file',
                    'image',
                    'mimes:jpeg,png',
                ]
            ]);

            // (画像あり)データの保存がうまく行ったとき
            if ($this->users->save($request, $filename)) {
                return redirect()->to('/users')->with('message', '(画像付き)ユーザを作成しました。');
            }

            // (画像あり)データの保存に失敗したとき
            $this->messages->add('', '画像の保存に失敗しました。管理者に問い合わせてください。');
            return redirect()->to('/users/create')->withErrors($this->messages);
        }
        
        // (画像なし)データの保存がうまく行ったとき
        if ($this->users->save($request)) {
            return redirect()->route('users.index')->with('message', 'ユーザを作成しました');
        }

        // (画像なし)データの保存に失敗したとき
        $this->messages->add('', 'ユーザの作成に失敗しました。管理者に問い合わせてください。');
        return redirect()->to('/users/create')->withErrors($this->messages);
    }


    // マイページアクション
    public function show($id)
    {
        // ログインユーザの所属する会社データとユーザデータを取得
        $company = $this->companies->showQuery()->first();
        $user = $this->users->showQuery()->first();
        
        return view('users.show', [
            'user'    => $user,
            'company' => $company,
        ]);
    }


    // 編集アクション
    public function edit($id)
    {

        // 選択されたユーザの情報と会社情報を取得
        $user = $this->users->editQuery($id)->first();
        $companies = $this->companies->allQuery()->get();

        return view('users.edit', [
            'user' => $user,
            'companies' => $companies,
        ]);
    }


    /* 編集データの更新アクション */
    public function update(Request $request, $id)
    {
        // 編集対象のユーザのデータを取得
        $user = $this->users->editQuery($id)->first();

        // メールアドレスの一意チェック(自分以外のデータと比較するように設定)
        $request->validate([
            'email' => [Rule::unique('users', 'email')->ignore($user->id)->where('del_flg', 0)]
        ]);
        
        if(!empty($_FILES['profile_image']['name'])){
       
            // ファイル名取得
            $filename = $_FILES['profile_image']['name'];

            // アップロードしたファイルのバリデーション設定
            $this->validate($request, [
                'profile_image' => [
                    'required',
                    'file',
                    'image',
                    'mimes:jpeg,png',
                ]
            ]);

            // (画像あり)データの更新がうまく行ったとき
            if ($this->users->save($request, $filename, $user)) {
                return redirect()->to('/users')->with('message', '(画像付き)ユーザを更新しました。');
            }
            
            // (画像あり)データの更新に失敗したとき
            $this->messages->add('', '画像の保存に失敗しました。管理者に問い合わせてください。');
            return redirect()->to('/users\/'.$request->id.'/edit')->withErrors($this->messages);
        }

        // (画像なし)データの更新がうまく行ったとき
        if ($this->users->save($request, null, $user)) {
            return redirect()->to('/users')->with('message', 'ユーザを更新しました。');
        }
        
        // (画像なし)データの更新に失敗したとき
        $this->messages->add('', 'ユーザの更新に失敗しました。管理者に問い合わせてください。');
        return redirect()->to('/users\/'.$request->id.'/edit')->withErrors($this->messages);
    }


    // データの削除アクション
    public function destroy($id)
    {
        // dd($user);
        // exit;
        // 削除フラグをtrueに変更
        if ($this->users->destroy($id)) {
            return redirect()->route('users.index')->with('message', 'ユーザを削除しました');
        }

        $this->messages->add('', 'ユーザの削除に失敗しました。管理者に問い合わせてください。');
        return redirect()->to('/users')->withErrors($this->messages);
        
    }

        
}
