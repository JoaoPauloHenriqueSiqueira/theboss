<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTablesSeeder extends Seeder
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
                'name' => "João Siqueira",
                'email' => 'joao.jp9307@gmail.com',
                'company_id' => 1,
                'password' => bcrypt('123456'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "id" => 2,
                'name' => "Leonardo",
                'email' => 'leonardo@gmail.com',
                'company_id' => 1,
                'password' => bcrypt('leonardo123456'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($data as $row) {
            $user = DB::table('users')->find($row['id']);

            if (!$user) {
                DB::table('users')->insert($row);
            }
        }
    }
}
