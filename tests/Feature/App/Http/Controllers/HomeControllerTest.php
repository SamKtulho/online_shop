<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\HomeController;
use Database\Factories\BrandFactory;
use Database\Factories\ProductFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_main_page_is_ok()
    {
        ProductFactory::new()
            ->count(5)
            ->create([
                'is_on_main_page' => true,
                'sorting' => 999
            ]);

        $productFirstPosition = ProductFactory::new()
            ->createOne([
                'is_on_main_page' => true,
                'sorting' => 1
            ]);

        BrandFactory::new()
            ->count(5)
            ->create([
                'is_on_main_page' => true,
                'sorting' => 999
            ]);

        $brandFirstPosition = BrandFactory::new()
            ->createOne([
                'is_on_main_page' => true,
                'sorting' => 1
            ]);

        $this->get(action(HomeController::class))
            ->assertOk()
            ->assertViewHas('products.0', $productFirstPosition)
            ->assertViewHas('brands.0', $brandFirstPosition);

    }

}
