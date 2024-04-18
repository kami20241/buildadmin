<?php

namespace app\admin\model\app;

use think\Model;

/**
 * Version
 */
class Version extends Model
{
    // 数据库连接配置标识
    protected $connection = 'kami';

    // 表名
    protected $name = 'app_version';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

}