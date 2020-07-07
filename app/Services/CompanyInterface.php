<?php

namespace App\Services;

interface CompanyInterface
{
    // データを全件取得するメソッド
    public function allQuery();

    // showページ用のデータ取得メソッド
    public function showQuery();
}