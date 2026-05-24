<?php

namespace Tests\Feature;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Override;
use Tests\TestCase;

class QueryBuilderTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete('delete from categories');
    }

    public function insertCategories(){
        DB::table('categories')->insert(['id' => 'SMARTPHONE', 'name' => 'Smartphone']);

        DB::table('categories')->insert(['id' => 'FOOD', 'name' => 'Food']);

        DB::table('categories')->insert(['id' => 'LAPTOP', 'name' => 'Laptop']);

        DB::table('categories')->insert(['id' => 'FASHION', 'name' => 'Fashion']);
    }

    function testInsert(){

        DB::table('categories')->insert([
            "id" => "GADGET",
            "name" => "Gadget"
        ]);

        DB::table('categories')->insert([
            "id" => "FOOL",
            "name" => "Food"
        ]);

        $result = DB::select("SELECT COUNT(id) as total FROM categories");
        self::assertEquals(2, $result[0]->total);
    }

    public function testselect(){
        $this->testInsert();

        $collection = DB::table('categories')->select(['id', 'name'])->get();
        self::assertNotNull($collection);

        $collection->each(fn ($item) => Log::info(json_encode($item)));
    }

    public function testWhere(){
        $this->insertCategories();

        $collection = DB::table('categories')->where(function(Builder $builder) {
            $builder->where('id', '=' , 'SMARTPHONE');
            $builder->orWhere('id', '=', 'FOOD');
        })->get();

        self::assertCount(2, $collection);

        $collection->each(fn($item) => Log::info(json_encode($item)));
    }

    public function testWhereDate(){
        $this->insertCategories();

        $collection = DB::table('categories')->whereDate('created_at', now()->toDateString())->get();
        self::assertCount(4, $collection);

        $collection->each(fn($item) => Log::info(json_encode($item)) );
    }

}
