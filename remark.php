<?php
/*
 * 项目兼容说明
 *
 * 修改newweb项目：
 * 在 /site/cjj/framework/core/base.php 文件 start_session() 中增加ini_set("session.serialize_handler", "php_serialize");
 *
 * 修改im项目：
 * 修改/site/im/vendor/laravel/framework/src/Illuminate/Session/Store.php
 * 原98行：$data = @unserialize($this->prepareForUnserialize($data));
 * 改为：$data = $this->prepareForUnserialize($data);
 * 原130行：serialize($this->attributes)
 * 改为：$this->attributes
 * 原575行：return is_string($id) && ctype_alnum($id) && strlen($id) === 40;
 * 改为：return is_string($id) && ctype_alnum($id) && strlen($id) <= 40;
 *
 *
 */