<?php

namespace App\Models;

use App\Libraries\Utils\Math;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no',
        'name',
        'open_id',
        'wx_name',
        'wx_avatar',
        'mobile',
        'no_qrcode',
        'password',
        'from_store_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * 生成会员.
     *
     * @return string
     *
     * @author Caikeal <caikeal@qq.com>
     */
    public static function generateUserNo()
    {
        $sn = Math::generateSn('14');

        /* 到数据库里查找是否已存在 */
        try {
            self::findOrFail($sn);
        } catch (ModelNotFoundException $e) {
            return $sn;
        }

        /* 如果有重复的，则重新生成 */

        return self::generateUserNo();
    }
}
