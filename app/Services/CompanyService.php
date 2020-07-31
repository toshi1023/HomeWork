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

    /* 編集対象となる会社の情報を取得 */
    public function editQuery($request)
    {
        $this->query->where('id', $request);

        return $this->query;
    }

    /* 会社のDB保存処理
     * 第1引数:入力フォームから送信されたデータ
     * 第2引数:更新対象データ(新規保存の場合はnull)
    */
    public function save($request, $company_data=null)
    {
        try{

            // アップデートフラグがtrueの場合はユーザ情報を取得
            if (!empty($company_data)) {
                $this->company = $this->company::find($company_data->id);
            }

            // データの登録(画像以外)
            $this->company->name  = $request->name;
            $this->company->post_no = $request->post_no;
            $this->company->address      = $request->address;
            $this->company->tel = $request->tel;
            $this->company->fax = $request->fax;
            $this->company->map_url = $request->map_url;
            $this->company->memo = $request->memo;

            // データを保存
            $this->company->save();

            return true;

        } catch (\Exception $e) {
            \Log::error('database register error:'.$e->getMessage());
            return false;
        }
    }    

}