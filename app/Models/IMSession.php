<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IMSession extends Model
{
    use SoftDeletes;
    /**
     * 关联模型的数据表名
     * @var string
     */
    protected $table = 'im_session';

    /**
     * 约束表主键
     * @var int
     */
    protected $primaryKey = 'session_id';

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
     * 获取当前会话的所有用户会话状态
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userSession(){
        return $this->hasMany('App\Models\IMUserSession','session_id','session_id');
    }


}
