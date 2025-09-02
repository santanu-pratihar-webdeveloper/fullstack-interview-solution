<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return json_encode(['status' => 'ok', 'message' => 'CI4 backend up']);
    }
}
