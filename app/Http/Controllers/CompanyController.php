<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        return view('users.create', [
            'companies' => $companies,
        ]);
    }
}
