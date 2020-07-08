<?php

namespace App\Services;

interface UserInterface
{
    // indexページ用のデータ取得メソッド
    public function indexQuery();

    // showページ用のデータ取得メソッド
    public function showQuery();

    // editページ用のデータ取得メソッド
    public function editQuery($request);

    // ファイルアップロード用のデータ処理メソッド
    public function fileUpload($request, $filename);

    // データ保存メソッド
    public function save($request, $filename, $user_data);

    // データ削除メソッド
    public function destroy($request);
}