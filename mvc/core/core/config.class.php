<?php
/**
 * Created by PhpStorm.
 * User: jincon
 * Date: 16/7/8
 * Time: 下午1:26
 */
class config{
    public static function get($name=''){
        $config1 = require_cache(CORE_ROOT."convention.php",1);
        $config2 = require_cache(APP."common/config/config.php",1);
        $config = array_merge($config1,$config2);
        if(!$name){
            return $config;
        }else{
            return isset($config[$name]) ? $config[$name] : false;
        }
    }
}