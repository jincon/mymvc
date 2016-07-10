<?php
/**
 * Created by PhpStorm.
 * User: jincon
 * Date: 16/7/9
 * Time: 下午2:48
 */
return  array(

    'HOST'                  =>  'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).'/',
    'BASE_URL'              =>  str_replace(array('\\', '//'), '/', dirname($_SERVER['SCRIPT_NAME'])).'/',
    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  '',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  '',    // 数据库表前缀
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8

    /* 应用设定 */
    'STATIC_PATH'            =>  'static/',  //相对于根目录而已
    'DATA_PATH'              =>  ROOT.'data/',
    'CACHE_PATH'             =>  ROOT.'data/cache/',


    'MEMCACHED'             => array(
        'host'=>'',
        'port'=>'',
        'persistent'=>'',
    ),
    'MEMCACHE'             => array(
        'host'=>'',
        'port'=>'',
        'persistent'=>'',
    ),

    'MODULE_ALLOW_LIST'     =>  array('home'),
    'MODULE_DENY_LIST'      =>  array('common'),


    /* Cookie设置 */
    'COOKIE_EXPIRE'         =>  0,       // Cookie有效期
    'COOKIE_DOMAIN'         =>  '',      // Cookie有效域名
    'COOKIE_PATH'           =>  '/',     // Cookie路径
    'COOKIE_PREFIX'         =>  '',      // Cookie前缀 避免冲突
    'COOKIE_SECURE'         =>  false,   // Cookie安全传输
    'COOKIE_HTTPONLY'       =>  '',      // Cookie httponly设置

    'COOKIE'                => array(

    ),

    //session
    'SESSION_AUTOSTART'     => true,     //是否自动开启session
    'SESSION_EXPIRE'        => '3600',   //设置session最大存活时间。session有效期
    'SESSION_PATH'          => '',       //存储路径


    'MONGODB'               => array(
        'dbname'=>'test',
        'dsn'    => 'mongodb://localhost:27017',
        'option' => array('connect' => true),
    ),


    /* 默认设定 */
    'DEFAULT_MODULE'        =>  'home',  // 默认模块
    'DEFAULT_CONTROLLER'    =>  'index', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'index', // 默认操作名称
    'DEFAULT_CHARSET'       =>  'utf-8', // 默认输出编码
    'DEFAULT_TIMEZONE'      =>  'PRC',	// 默认时区
    'DEFAULT_AJAX_RETURN'   =>  'JSON',  // 默认AJAX 数据返回格式,可选JSON XML ...
    'DEFAULT_JSONP_HANDLER' =>  'jsonpReturn', // 默认JSONP格式返回的处理方法
    'DEFAULT_FILTER'        =>  'htmlspecialchars', // 默认参数过滤方法 用于I函数...



    /* 数据缓存设置 */
    'DATA_CACHE_TIME'       =>  0,      // 数据缓存有效期 0表示永久缓存
    'DATA_CACHE_PREFIX'     =>  '',     // 缓存前缀
    'DATA_CACHE_TYPE'       =>  'File',  // 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
    'DATA_CACHE_PATH'       =>  '',// 缓存路径设置 (仅对File方式缓存有效)
    'DATA_CACHE_KEY'        =>  '',	// 缓存文件KEY (仅对File方式缓存有效)
    'DATA_CACHE_SUBDIR'     =>  false,    // 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
    'DATA_PATH_LEVEL'       =>  1,        // 子目录缓存级别

    /* 错误设置 */
    'ERROR_MESSAGE'         =>  '页面错误！请稍后再试～',//错误显示信息,非调试模式有效
    'ERROR_PAGE'            =>  '',	// 错误定向页面
    'SHOW_ERROR_MSG'        =>  false,    // 显示错误信息
    'TRACE_MAX_RECORD'      =>  100,    // 每个级别的错误信息 最大记录数

    /* 模板引擎设置 */
    'TMPL_TEMPLATE_SUFFIX'  =>  '.html',     // 默认模板文件后缀


    /* URL设置 */
    'URL_MODEL'             =>  1,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式

    'URL_HTML_SUFFIX'       =>  '',  //URL伪静态后缀设置

    /* 系统变量名称设置 */
    'VAR_AJAX_SUBMIT'       =>  'ajax',  // 默认的AJAX提交变量

);