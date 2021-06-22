<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CompaniesTableSeeder extends Seeder
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
                "name" => "Augusto Presentes Teste",
                "cnpj" => "23720554000169",
                "phone" => "123123123",
                "email" => "augustopresentes@gmail.com",
                "active" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
                'is_api' => 1
            ],
            [
                "id" => 2,
                "name" => "Salão da Tica",
                "cnpj" => "23720554000167",
                "email" => "salaotica@gmail.com",
                "active" => 1,
                "phone" => "123123123",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]
        ];

        foreach ($data as $row) {
            $tax = DB::table('companies')->find($row['id']);

            if (!$tax) {
                DB::table('companies')->insert($row);
            }
        }
    }
}
