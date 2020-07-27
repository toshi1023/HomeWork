<?php

namespace App\Services\CsvServices;

interface CsvInterface
{
    // csvのインポート機能を実装
    public function import($request);

    // csvのエクスポート機能を実装
    public function export($request);
}