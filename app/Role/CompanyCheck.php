<?php

namespace App\Role;


class CompanyCheck
{
    public function check($user)
    {
        if ($user) {
            if ($user->company->active) {
                return true;
            }
        }
        return false;
    }
}
