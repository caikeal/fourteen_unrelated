<?php

/*
 * This file is part of the caikeal/fourteen_unrelated .
 *
 * (c) caikeal <caiyuezhang@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * 根据用户码获取用户信息.
     *
     * @param $no
     *
     * @return mixed
     *
     * @author Caikeal <caikeal@qq.com>
     */
    public function findByNo($no)
    {
        $user = User::where('no', $no)->first();

        return $user;
    }
}
