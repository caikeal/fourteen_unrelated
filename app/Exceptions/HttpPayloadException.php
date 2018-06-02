<?php

/*
 * This file is part of the caikeal/fourteen_unrelated .
 *
 * (c) caikeal <caiyuezhang@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Class HttpPayloadException.
 *
 * @author  Caikeal <caikeal@qq.com>
 */
class HttpPayloadException extends RuntimeException
{
    /**
     * @var array
     */
    protected $payload;

    /**
     * @param string    $message
     * @param array     $payload
     * @param Throwable $previous
     */
    public function __construct($message = '', $payload = [], Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->payload = $payload;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
