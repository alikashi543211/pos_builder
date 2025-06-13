<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public $productService;
    public function __construct()
    {
        $this->productService = new ProductService();
    }
    public function index(Request $request)
    {
        return $this->productService->index($request);
    }
}
