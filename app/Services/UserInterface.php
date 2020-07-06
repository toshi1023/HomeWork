<?php

namespace App\Services;

interface UserInterface
{
    public function allQuery($index);
    public function showQuery();
    public function fileUpload($request);
    public function save($request, $filename);
}