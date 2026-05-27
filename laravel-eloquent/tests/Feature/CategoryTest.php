<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PhpParser\Node\Expr\FuncCall;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function testInsert(){
        $category = new Category();
        $category->id = 'GADGET';
        $category->name = 'Gadget';

        $result = $category->save();

        self::assertTrue($result);
    }

    public function testInsertMany(){

        $categories = [];

        for ($i=0; $i < 10; $i++) { 
            $categories[] = [
                'id' => 'id'. $i,
                'name' => 'name' . $i
            ];
        }

        $category = Category::query()->insert($categories); // retun value-nya adalah object model category

        self::assertTrue($category);

        $result = Category::query()->count();
        self::assertEquals(10, $result);
    }

    public function testFindCategory(){
       $this->seed(CategorySeeder::class);

       $category = Category::query()->find("FOOD");

       self::assertNotNull($category);
       self::assertEquals("FOOD", $category->id);
       self::assertEquals("Food", $category->name);
       self::assertEquals("Food Category", $category->description);
    }

    public function testUpdate(){
        $this->seed(CategorySeeder::class);

        $category = Category::query()->find("FOOD");
        $category->name = "Food Updated";

        $result = $category->update();

        self::assertTrue($result);
    }

    public function testSelect(){
        for ($i=0; $i < 5; $i++) { 
            $categories = new Category();
            $categories->id = "id $i";
            $categories->name = "name $i";

            $categories->save();
        }

        $categories = Category::query()->whereNull("description")->get();
        self::assertEquals(5, $categories->count());
        $categories->each(function($category){
            $category->description = "update";
            $category->update();
        });
    }

    public function testUpdateMany(){
        $categories = [];

        for ($i=0; $i < 10 ; $i++) { 
            $categories [] = [
                "id" => "id $i",
                "name" => "name $i",
            ];
        }

       // insert
        $result = Category::query()->insert($categories);
        self::assertTrue($result);

       // update
        Category::query()->whereNull("description")->update([
            "description" => "Updated"
        ]);

       // select
       $total = Category::query()->where("description", "=", "Updated")->count();
       self::assertEquals(10, $total);
    }

    public function testDelete(){
        $this->seed(CategorySeeder::class);

        // cari id yang akan dihapus
        $category = Category::query()->find("FOOD");
        $result = $category->delete();
    }

}
