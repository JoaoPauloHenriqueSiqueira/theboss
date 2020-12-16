<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ClientsTableSeeder extends Seeder
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
                "name" => "José Negrini",
                "phone" => "",
                "cell_phone" => "14992122222",
                "company_id" => 1,
                "cpf_cnpj" => "39226071870",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "name" => "Cristina Negrini",
                "phone" => "",
                "cell_phone" => "14992122222",
                "company_id" => 1,
                "cpf_cnpj" => "32226071870",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "name" => "Creuza Maria",
                "phone" => "",
                "cell_phone" => "14992122222",
                "company_id" => 1,
                "cpf_cnpj" => "34446071870",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "name" => "Roberto Siquera",
                "phone" => "",
                "cell_phone" => "14992122222",
                "company_id" => 1,
                "cpf_cnpj" => "39226071872",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "name" => "Patricia Maria",
                "phone" => "",
                "cell_phone" => "14992122222",
                "company_id" => 1,
                "cpf_cnpj" => "55226071870",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "name" => "Vanilde Maron",
                "phone" => "",
                "cell_phone" => "14992122222",
                "company_id" => 1,
                "cpf_cnpj" => "39226072470",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "name" => "Neto Carniini",
                "phone" => "",
                "cell_phone" => "14992122222",
                "company_id" => 1,
                "cpf_cnpj" => "44226071870",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]
        ];


        foreach ($data as $row) {
            DB::table('clients')->insert($row);
        }
    }
}
