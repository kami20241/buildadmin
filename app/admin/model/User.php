<?php

namespace app\admin\model;

use think\Model;

/**
 * User
 */
class User extends Model
{
    // 数据库连接配置标识
    protected $connection = 'kami';

    // 表名
    protected $name = 'user';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    protected $type = [
        'rebate' => 'float'
    ];

}