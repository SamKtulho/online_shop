<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        $brands = Brand::query()->mainPage()->get();
        $categories = Category::query()->mainPage()->get();
        $products = Product::query()->mainPage()->get();

        return view('index', compact('brands', 'categories', 'products'));
    }
}
