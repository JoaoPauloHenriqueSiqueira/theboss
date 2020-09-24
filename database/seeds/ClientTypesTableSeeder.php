<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ClientTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                "id" => 1,
                'name' => "Jurídica",
                "doc" => "CNPJ",
            ],
            [
                "id" => 2,
                'name' => "Física",
                "doc" => "CPF"
            ]
        ];

        foreach ($data as $row) {
            $type = DB::table('client_types')->find($row['id']);

            if (!$type) {
                DB::table('client_types')->insert($row);
            }
        }
    }
}
