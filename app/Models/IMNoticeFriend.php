<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IMNoticeFriend extends Model
{
    use SoftDeletes;
    /**
     * 关联模型的数据表名
     * @var string
     */
    protected $table = 'im_notice_friend';

    /**
     * 约束表主键
     * @var int
     */
    protected $primaryKey = 'id';

    /**
     * 禁止自动管理 created_at 和 updated_at 列
     * @var bool
     */
    public $timestamps = true;

    /**
     * 模型日期列的存储格式
     * @var string
     */
    protected $dateFormat = 'U';


}
