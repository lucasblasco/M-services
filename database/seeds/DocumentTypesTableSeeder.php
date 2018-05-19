<?php

use Illuminate\Database\Seeder;
use App\DocumentType;

class DocumentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(DocumentType::class, 3)->create();
    }
}
