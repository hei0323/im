<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IMGroup extends Model
{
    use SoftDeletes;
    /**
     * 关联模型的数据表名
     * @var string
     */
    protected $table = 'im_group';

    /**
     * 约束表主键
     * @var string
     */
    protected $primaryKey = 'im_group_id';

    /**
     * 模型日期列的存储格式
     * @var string
     */
    protected $dateFormat = 'U';
}
