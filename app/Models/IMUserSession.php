<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IMUserSession extends Model
{
    use SoftDeletes;
    /**
     * 关联模型的数据表名
     * @var string
     */
    protected $table = 'im_user_session';

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


    /**
     * 获取用户会话状态所属会话
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userSession(){
        return $this->belongsTo('App\Models\IMSession','session_id','session_id');
    }

}
