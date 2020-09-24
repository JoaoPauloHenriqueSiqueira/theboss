<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function model()
    {
        return User::class;
    }

    public function allNotAdmin()
    {
        return DB::table('users')
            ->join('types', 'types.id', '=', 'users.type_id')
            ->select('users.*')
            ->orderBy("name")
            ->where("types.is_admin", false)
            ->get();
    }
}
