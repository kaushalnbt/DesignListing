<?php

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Finish;
use App\Models\Size;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $category1 = ProductCategory::create(['name' => 'Wood']);
        $category2 = ProductCategory::create(['name' => 'Marble']);

        $finish1 = Finish::create(['name' => 'Glossy']);
        $finish2 = Finish::create(['name' => 'Matte']);

        $size1 = Size::create(['size_ft' => '2x4', 'size_mm' => '600x1200']);
        $size2 = Size::create(['size_ft' => '3x6', 'size_mm' => '900x1800']);

        $product = Product::create(['name' => 'Oak Wood', 'category_id' => $category1->id]);
        $product->finishes()->attach([$finish1->id, $finish2->id]);
        $product->sizes()->attach([$size1->id, $size2->id]);
    }
}
