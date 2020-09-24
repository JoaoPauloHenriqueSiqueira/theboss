<?php

use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
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
                'title' => "Gerente",
                'is_admin' => 1,
                'company_id' => 1
            ],
            [
                "id" => 2,
                'title' => "Funcionário",
                'is_admin' => 0,
                'company_id' => 1
            ]
        ];

        foreach ($data as $row) {
            $type = DB::table('types')->find($row['id']);

            if (!$type) {
                DB::table('types')->insert($row);
            }
        }
    }
}
