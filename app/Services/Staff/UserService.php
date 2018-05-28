<?php

/*
 * This file is part of the caikeal/fourteen_unrelated .
 *
 * (c) caikeal <caiyuezhang@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Services\Staff;

use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class UserService.
 */
class UserService extends BaseService
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * 扫码返回用户信息.
     *
     * @param $code
     *
     * @return mixed
     *
     * @author Caikeal <caikeal@qq.com>
     */
    public function scan($code)
    {
        $user = $this->userRepository->findByNo($code);
        if (!$user) {
            throw new ModelNotFoundException('未知的用户');
        }

        return $user;
    }
}
