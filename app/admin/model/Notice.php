<?php

namespace app\admin\model;

use think\Model;

/**
 * Notice
 */
class Notice extends Model
{
    // 数据库连接配置标识
    protected $connection = 'kami';

    // 表名
    protected $name = 'notice';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;


    public function getContentAttr($value): string
    {
        return !$value ? '' : htmlspecialchars_decode($value);
    }
}