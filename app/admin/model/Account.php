<?php

namespace app\admin\model;

use think\Model;

/**
 * Account
 */
class Account extends Model
{
    // 数据库连接配置标识
    protected $connection = 'kami';

    // 表名
    protected $name = 'account';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    protected $type = [
        'money'       => 'float',
        'active'      => 'float',
        'withdrawed'  => 'float',
        'withdrawing' => 'float',
    ];

}