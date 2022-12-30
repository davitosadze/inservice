<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// use App\Models\TestCategory;

use App\Models\Category;

use Illuminate\Support\Str;

use App\Models\CategoryAttribute;

class InserviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // DB::beginTransaction();
        // DB::table('categories')->truncate();
        // DB::table('category_attributes')->truncate();

        // try {

        //     collect(TestCategory::with('attrs')->get()->toArray())->map(function($category_item, $category_key) {

        //         $category = Category::firstOrNew(['name' => $category_item['title']]);
                
        //         $category->name = $category_item['title'];
        //         $category->type = $category_item['unit'];
        //         $category->description = $category_item['description'];

        //         $category->save();

        //         collect($category_item['attrs'])->map(function($attribute_item, $attribute_key) use($category) {

        //             $hasNested = isset($attribute_item['nested']) && !empty($attribute_item['nested']);

        //             $attribute = CategoryAttribute::firstOrNew(['name' => $attribute_item['title']]);
        //             if (!$attribute->id) { $attribute->uuid = (string) Str::uuid(); };

        //             $attribute->name = $attribute_item['title'];
        //             $attribute->price = $hasNested ? null :  $attribute_item['price'];
        //             $attribute->item = $hasNested ? null :  $category['type'];

        //             $attribute->category_id = $category['id'];

        //             $attribute->service_price = $hasNested ? null : $attribute_item['service_price'];
        //             $attribute->category_type = $hasNested ? true : false;
        //             $attribute->save();

        //             if ($hasNested) {

        //                 collect($attribute_item['nested'])->map(function($nested_item, $nested_key) use ($attribute, $category) {

        //                     $hasNested = isset($nested_item['nested']) && !empty($nested_item['nested']);

        //                     $attributeNested = CategoryAttribute::firstOrNew(['name' => $nested_item['title']]);
        //                     if (!$attributeNested->id) { $attributeNested->uuid = (string) Str::uuid(); };

        //                     $attributeNested->parent_uuid = $attribute->uuid;

        //                     $attributeNested->name = $nested_item['title'];
        //                     $attributeNested->price = $hasNested ? null : $nested_item['price'];

        //                     $attributeNested->item = $hasNested ? null : $category['type'];

        //                     $attributeNested->category_id = $category['id'];

        //                     $attributeNested->service_price = $hasNested ? null : $nested_item['service_price'];
        //                     $attributeNested->category_type = $hasNested ? true : false;

        //                     $attributeNested->save();

        //                     if ($hasNested) {

        //                         collect($nested_item['nested'])->map(function($nested_nested_item, $nested_nested_key) use ($attributeNested, $category) {

        //                             $hasNested = isset($nested_item['nested']) && !empty($nested_item['nested']);
                                    
        //                             $attributeNestedNested = CategoryAttribute::firstOrNew(['name' => $nested_nested_item['title']]);
        //                             if (!$attributeNestedNested->id) { $attributeNestedNested->uuid = (string) Str::uuid(); };

        //                             $attributeNestedNested->parent_uuid = $attributeNested->uuid;

        //                             $attributeNestedNested->name = $nested_nested_item['title'];
        //                             $attributeNestedNested->price = $hasNested ? null : $nested_nested_item['price'];

        //                             $attributeNestedNested->item = $hasNested ? null : $category['type'];

        //                             $attributeNestedNested->category_id = $category['id'];

        //                             $attributeNestedNested->service_price = $hasNested ? null : $nested_nested_item['service_price'];
        //                             $attributeNestedNested->category_type = $attributeNestedNested ? true : false;

        //                             $attributeNestedNested->save();
        //                         });
        //                     }
        //                 });
        //             }

        //         });

        //     });

        //     DB::statement('SET FOREIGN_KEY_CHECKS=1');
        //     DB::commit();

        // } catch(Exception $e) {

        //     DB::rollBack();
        // }
    }
}
