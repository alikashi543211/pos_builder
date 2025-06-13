<?php

namespace App\Services;

use App\Libraries\Products\ListProductLibrary;

class ProductService
{
    public $listProductLibrary;
    public function __construct()
    {
        $this->listProductLibrary = new ListProductLibrary();
    }

    public function index($request)
    {
        return $this->listProductLibrary->list($request);
    }
}
