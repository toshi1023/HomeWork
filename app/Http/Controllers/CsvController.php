<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CsvServices\CsvInterface;

class CsvController extends Controller
{
    protected $csv;

    public function __construct(CsvInterface $csv)
    {
        // UserCsvServiceをインスタンス化
        $this->csv = app()->make(CsvInterface::class);
    }

    public function import(Request $request)
    {
        // falseが返ってきた場合はエラーメッセージ付きでリダイレクト
        if(!$this->csv->import($request)[0]) {
            return redirect()->to('/users')->withErrors($this->csv->import($request)[1]);
        }

        // 正常にcsvインポートが完了した場合は登録データ件数をリターン
        return view('csv.import', [
            'row_count' => $this->csv->import($request)[1],
        ]);
    }

    public function export(Request $request)
    {
        return $this->csv->export($request);
    }
}
