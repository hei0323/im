<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IMFriend extends Model
{
    use SoftDeletes;
    /**
     * 关联模型的数据表名
     * @var string
     */
    protected $table = 'friend_id';

    /**
     * 约束表主键
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 禁止自动管理 created_at 和 updated_at 列
     * @var bool
     */
    public $timestamps = false;
}
