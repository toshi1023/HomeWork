<?php

namespace App\Services;

interface UserInterface
{
    public function indexQuery($index);
    public function showQuery();
    public function fileUpload($request, $filename);
    public function save($request, $filename);
}