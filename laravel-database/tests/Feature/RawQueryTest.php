<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Override;
use Tests\TestCase;

class RawQueryTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete('delete from categories');
    }

    public function testCrud()
    {
        DB::insert('insert into categories(id, name, description) values (?,?,?)', [
            "GADGET",
            "Gadget",
            "Gadget Category"
        ]);

        $result = DB::select('select * from categories where id = ?', ['GADGET']);

        self::assertCount(1, $result);
        self::assertEquals('GADGET', $result[0]->id);
        self::assertEquals('Gadget', $result[0]->name);
        self::assertEquals('Gadget Category', $result[0]->description);
    }

    public function testCrudBindingNamedParameter()
    {
        DB::insert('insert into categories(id, name, description) values (:id, :name, :description)', [
            "id" => "GADGET",
            "name" => "Gadget",
            "description" => "Gadget Category"
        ]);

        $result = DB::select('select * from categories where id = ?', ['GADGET']);

        self::assertCount(1, $result);
        self::assertEquals('GADGET', $result[0]->id);
        self::assertEquals('Gadget', $result[0]->name);
        self::assertEquals('Gadget Category', $result[0]->description);
    }
}
