<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class HomeController extends Controller
{
    public function __invoke(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $brands = Brand::query()->mainPage()->get();
        $categories = Category::query()->mainPage()->get();
        $products = Product::query()->mainPage()->get();

        return view('index', compact('brands', 'categories', 'products'));
    }
}
