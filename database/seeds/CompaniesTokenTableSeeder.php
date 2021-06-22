<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CompaniesTokenTableSeeder  extends Seeder
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
                "company_id" => 1,
                "max_attempts" => 3,
                "attempts" => 1,
                "token" => "1232123",
                "api_token" => "123",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "id" => 2,
                "company_id" => 2,
                "max_attempts" => 3,
                "attempts" => 1,
                "token" => "231312",
                "api_token" => "1234",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]
        ];

        foreach ($data as $row) {
            $tax = DB::table('company_token_actives')->find($row['id']);

            if (!$tax) {
                DB::table('company_token_actives')->insert($row);
            }
        }
    }
}
