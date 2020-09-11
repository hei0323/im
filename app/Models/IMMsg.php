<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IMMsg extends Model
{
    use SoftDeletes;
    /**
     * 关联模型的数据表名
     * @var string
     */
    protected $table = 'im_msg';

    /**
     * 约束表主键
     * @var string
     */
    protected $primaryKey = 'im_msg_id';

    /**
     * 禁止自动管理 created_at 和 updated_at 列
     * @var bool
     */
    public $timestamps = false;
}
