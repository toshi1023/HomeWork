<?php

namespace App\Services;

use App\Models\User;
use App\Models\Company;


class CompanyService implements CompanyInterface
{
    protected $user;
    protected $company;
    

    public function __construct(User $user, Company $company)
    {
        $this->user = $user;
        $this->company = $company;
        $this->query = $company::query();
    }

    /* 会社データを全件取得(論理削除は含まない) */
    public function allQuery()
    {
        $this->query->where('del_flg', 0)->latest('updated_at');

        return $this->query;
    }

    /* ログインユーザの会社情報を取得 */
    public function showQuery()
    {
        $this->query->where('id', \Auth::user()->company_id);

        return $this->query;
    }

}