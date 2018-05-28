<?php

/*
 * This file is part of the caikeal/fourteen_unrelated .
 *
 * (c) caikeal <caiyuezhang@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Http\Controllers\Staff\Api\V1;

use App\Http\Controllers\Staff\Api\BaseController;
use App\Http\Requests\Staff\ScanUserRequest;
use App\Services\Staff\UserService;

/**
 * Class UserController.
 */
class UserController extends BaseController
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param ScanUserRequest $request
     *
     * @return mixed
     *
     * @author Caikeal <caikeal@qq.com>
     */
    public function scan(ScanUserRequest $request)
    {
        $code = $request->input('code');
        $user = $this->userService->scan($code);

        return $user;
    }
}
