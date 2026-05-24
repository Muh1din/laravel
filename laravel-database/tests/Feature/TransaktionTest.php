<?php

namespace Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Override;
use Tests\TestCase;

class TransaktionTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete('DELETE FROM categories');
    }

    // test berhasil
    public function testTransaction()
    {
        DB::transaction(function() {
            DB::insert('INSERT INTO categories(id, name, description) VALUES (:id, :name, :description)', [
                "id" => "GADGET",
                "name" => "Gadget",
                "description" => "Gadget Category",
            ]);

            DB::insert('INSERT INTO categories(id, name, description) VALUES (:id, :name, :description)', [
                'id' => 'FOOD',
                'name' => 'Makanan',
                'description' => 'Makanan Category',
            ]);
        });

        $result = DB::select('SELECT * FROM categories');
        self::assertCount(2, $result);
    }

    // test gagal
    public function testTransactionFailed()
    {
        try {
            DB::transaction(function () {
                DB::insert('INSERT INTO categories(id, name, description) VALUES (:id, :name, :description)', [
                    "id" => "GADGET",
                    "name" => "Gadget",
                    "description" => "Gadget Category",
                ]);

                DB::insert('INSERT INTO categories(id, name, description) VALUES (:id, :name, :description)', [
                    'id' => 'GADGET',
                    'name' => 'Makanan',
                    'description' => 'Makanan Category',
                ]);
            });
        } catch (QueryException $e) {
            // expected
        }


        $result = DB::select('SELECT * FROM categories');
        self::assertCount(0, $result);
    }


    public function testManualTransaction()
    {
        try {
            DB::beginTransaction();
            DB::insert('INSERT INTO categories(id, name, description) VALUES (:id, :name, :description)', [
                "id" => "GADGET",
                "name" => "Gadget",
                "description" => "Gadget Category",
            ]);

            DB::insert('INSERT INTO categories(id, name, description) VALUES (:id, :name, :description)', [
                'id' => 'FOOD',
                'name' => 'Makanan',
                'description' => 'Makanan Category',
            ]);

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
        }


        $result = DB::select('SELECT * FROM categories');
        self::assertCount(2, $result);
    }


    public function testManualTransactionFailed()
    {
        try {
            DB::beginTransaction();
            DB::insert('INSERT INTO categories(id, name, description) VALUES (:id, :name, :description)', [
                "id" => "GADGET",
                "name" => "Gadget",
                "description" => "Gadget Category",
            ]);

            DB::insert('INSERT INTO categories(id, name, description) VALUES (:id, :name, :description)', [
                'id' => 'GADGET',
                'name' => 'Makanan',
                'description' => 'Makanan Category',
            ]);

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
        }


        $result = DB::select('SELECT * FROM categories');
        self::assertCount(0, $result);
    }
}
