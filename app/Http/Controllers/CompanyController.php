<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use Illuminate\Support\MessageBag;
use App\Services\CompanyInterface;
use App\Services\UserInterface;

class CompanyController extends Controller
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

    /* 会社一覧アクション */
    public function index()
    {
        // ユーザデータの取得(削除フラグfalseに絞って取得)
        $companies = $this->companies->allQuery()->get();

        return view('companies.index', [
            'companies' => $companies,
        ]);
    }


    /* 会社作成アクション */
    public function create()
    {
        // 会社データをすべて取得(削除フラグfalseに絞って取得)
        $companies = $this->companies->allQuery()->get();

        return view('companies.create', [
            'companies' => $companies,
        ]);
    }

    public function store(Request $request) 
    {
        // 所在地の一意チェック(会社の一意チェック)
        $request->validate([
            // usersテーブルのemailカラムについて、del_flgが0のレコードを対象に、一意チェックする
            'email' => [Rule::unique('companies', 'address')->where('del_flg', 0)]
        ]);

        // データの保存がうまく行ったとき
        if ($this->companies->save($request)) {
            return redirect()->to('/companies')->with('message', '新規で会社を登録しました。');
        }

        // データの保存に失敗したとき
        $this->messages->add('', '会社の登録に失敗しました。管理者に問い合わせてください。');
        return redirect()->to('/companies/create')->withErrors($this->messages);
    }
}
