<?php
/**
 * Created by PhpStorm.
 * User: jincon
 * Date: 16/7/5
 * Time: 下午10:51
 */

/*
 * core是核心mvc框架目录，Core是核心类库，
 * class：是自动自动加载的类库，注意这个目录下因为自动加载，所以是不能进行实例的时候进行初始化，要注意了。
 * core：系统核心类库，必须加载，并按照一定的顺序
 * function：是系统核心函数的文件目录
 * lib：属于扩展类库不会自动加载。可以在加载的时进行实例化传递参数。
 *
 * 其他app下的目录
 * common下有3个主要目录
 *      class：可以放系统核心类库，可以在加载的时进行实例化传递参数。
 *      function：函数库。
 *      config：配置文件，默认加载config.php
 *
 */

define('VERSION','1.1');

// 记录开始运行时间
$GLOBALS['_beginTime'] = microtime(TRUE);
// 记录内存初始使用
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
if(MEMORY_LIMIT_ON) $GLOBALS['_startUseMems'] = memory_get_usage();


define('CORE_ROOT',dirname(__FILE__).'/');

defined('DEBUG')    or define('DEBUG',false); // 是否调试模式

//error_reporting(0); //屏蔽所有报错

define('IS_CGI',(0 === strpos(PHP_SAPI,'cgi') || false !== strpos(PHP_SAPI,'fcgi')) ? 1 : 0 );
define('IS_WIN',strstr(PHP_OS, 'WIN') ? 1 : 0 );
define('IS_CLI',PHP_SAPI=='cli'? 1   :   0);

spl_autoload_register(array('Core', 'loadClass'));


class Core {
    /**
     * @var string
     * 默认的模块名
     */
    public static $module = 'home';

    /**
     * @var string
     * 所有模块名，都要写，逗号分隔 home,admin
     */
    public static $modules = 'home';

    /**
     * @var string
     * 控制器方法名称
     */
    public static $control = 'home';

    /**
     * @var string
     * 动作方法名字
     */
    public static $action = 'index';

    /**
     * @var string
     * 0 c=home&a=index&.....
     * 1 home/index/xx/asd/  默认开启url样式，也暂时只开启pathinfo
     */
    public static $url_mode = '1';

    /**
     * 存储系统配置文件
     * @var
     */
    private static $_config;


    /**
     * 对象注册表
     *
     * @var array
     */
    private static $_objects = array();



    function __construct(){

    }

    /**
     * @desc：可以配置系统核心信息。
     * @param：
     * @param array $config
     * @author：
     */
    public static function config(){
        self::$_config = config::get();
        self::$module       =       self::$_config['DEFAULT_MODULE'];
        self::$modules      =       self::$_config['MODULE_ALLOW_LIST'];
        self::$control      =       self::$_config['DEFAULT_CONTROLLER'];
        self::$action       =       self::$_config['DEFAULT_ACTION'];
        self::$url_mode     =       self::$_config['URL_MODEL'];
    }


    private static function init(){
        //自动核心类库加载，有顺序
        $coreLib = array(
            'newexception',
            'db',
            'model',
            'config',
            'controller',
        );
        foreach ($coreLib as $value) {
            self::require_cache(CORE_ROOT.'core/'.$value.'.class.php');
        }

        //自动加载核心函数库
        $path = glob(CORE_ROOT.'function/*.func.php');
        foreach ($path as $value) {
            self::require_cache($value);
        }
        //end

        //配置初始化。
        self::config();

        // 定义系统一些常量
        define('HOST',    self::$_config['HOST']);
        define('BASE_URL',    self::$_config['BASE_URL']);
        define('NOW_TIME',      $_SERVER['REQUEST_TIME']);
        define('REQUEST_METHOD',$_SERVER['REQUEST_METHOD']);
        define('IS_GET',        REQUEST_METHOD =='GET' ? true : false);
        define('IS_POST',       REQUEST_METHOD =='POST' ? true : false);
        define('IS_AJAX',       ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST[self::$_config['VAR_AJAX_SUBMIT']]) || !empty($_GET[self::$_config['VAR_AJAX_SUBMIT']])) ? true : false);
        define('DATA_PATH',     self::$_config['DATA_PATH']);
        define('CACHE_PATH',    self::$_config['CACHE_PATH']);
        define('__STATIC__',    self::$_config['HOST'].self::$_config['STATIC_PATH']);
        define('__CSS__',       __STATIC__.'css/');
        define('__IMAGE__',     __STATIC__.'image/');
        define('__JS__',        __STATIC__.'js/');
        //end



        // ============ init ============

        if(self::$_config['SESSION_AUTOSTART']){
            Session::start();
        }


        date_default_timezone_set(self::$_config['DEFAULT_TIMEZONE']);


        //set_error_handler(array("excep", "handleException"));
        //set_exception_handler(array("excep", "handleException"));

        // 系统信息
        if(version_compare(PHP_VERSION,'5.4.0','<')) {
            ini_set('magic_quotes_runtime',0);
            define('MAGIC_QUOTES_GPC',get_magic_quotes_gpc()? true : false);
        }else{
            define('MAGIC_QUOTES_GPC',false);
        }

        if(!MAGIC_QUOTES_GPC) // Maybe would be removed in php6
        {
            $_POST = addslashes_deep($_POST);
            $_GET = addslashes_deep($_GET);
            $_REQUEST = addslashes_deep($_REQUEST);
            $_SERVER = addslashes_deep($_SERVER);
            $_COOKIE = addslashes_deep($_COOKIE);
        }
    }

    /**
     * @desc：分析 拆解 URL参数
     * @param：
     * @author：
     */
    private static function uri(){
        /*
            url_mode = 0
            [QUERY_STRING] => a=1&b=2
            [REQUEST_URI] => /demo/mvc/index.php?a=1&b=2
            [SCRIPT_NAME] => /demo/mvc/index.php
            [PHP_SELF] => /demo/mvc/index.php
            url_mode = 1
            [QUERY_STRING] =>
            [REQUEST_URI] => /demo/mvc/index.php/home/index/a/1/b/2
            [SCRIPT_NAME] => /demo/mvc/index.php
            [PATH_INFO] => /home/index/a/1/b/2
         * */
        if(self::$url_mode == 1){
            $uri = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'';
            $uri = trim($uri,'/');
        }

        if($uri){

            $uriArr = explode('/',$uri);
            $moduleArr = explode(',',self::$modules);

            if(in_array($uriArr[0],$moduleArr) && $uriArr[0] == self::$module){
                array_shift($uriArr);
            }elseif(in_array($uriArr[0],$moduleArr)){
                self::$module = array_shift($uriArr);
            }

            if(isset($uriArr[0])){
                self::$control = $uriArr[0];
                unset($uriArr[0]);
            }

            if(isset($uriArr[1])){
                self::$action = $uriArr[1];
                unset($uriArr[1]);
            }

            //分解参数到GET全局变量中
            foreach($uriArr as $k=>$v){
                if($k%2==0){
                    $_GET[$v] = '';
                }else{
                    $_GET[$uriArr[$k-1]] = $v;
                }
            }
        }
    }


    /**
     * @desc：启动核心
     * @param：
     * @author：
     */
    public static function run(){

        self::init();

        self::uri();

        $controlfile = APP.'/'.self::$module.'/controller/'.self::$control.'.php';
        if(file_exists($controlfile)){
            self::require_cache($controlfile);
        }

        if(!class_exists(self::$control.'_controller')){
            throw new newexception('不存在的控制器：'.self::$control.'_controller');
        }
        $class = self::$control.'_controller';
        $obj = new $class();

        if(!method_exists(self::$control.'_controller',self::$action)){
            throw new newexception('不存在的方法：'.self::$action);
        }

        $a = self::$action;
        $obj->$a();
    }

    public static function getModule(){
        return self::$module;
    }

    public static function getControl(){
        return self::$control;
    }

    public static function getAction(){
        return self::$action;
    }

    public static function error($mess=''){
        $mess = $mess?$mess:'系统发生错误，请检查';
        self::halt($mess);
    }

    public static function show_404(){
        self::require_cache(CORE_ROOT.'view/404.php');
        die();
    }

    public static function show_403(){
        self::require_cache(CORE_ROOT.'view/403.php');
        die();
    }

    public static function halt($message=''){
        $GLOBALS['message'] = $message;
        self::require_cache(CORE_ROOT.'view/error.php');
        die();
    }

    /**
     * @desc：自动加载，类库放在class里面，如果在lib，不会自动加载，需要手动加载。
     * @param：
     * @param string $type
     */
    public static function autoload($type=''){
        switch ($type) {
            case 'class':
                $path = glob(CORE_ROOT.'class/*.class.php');
                foreach ($path as $value) {
                    self::require_cache($value);
                }
                break;

            default:

                break;
        }
    }

    public static function loadClass(){
        self::autoload('class');
    }


    /**
     * 优化的require_once
     * @param string $filename 文件地址
     * @return boolean/mixed
     */
    public static function require_cache($filename,$isreturn = 0) {
        static $_importFiles = array();
        if (!isset($_importFiles[$filename])) {
            if (is_file($filename)) {
                $_t = require $filename;
                $_importFiles[$filename] = $isreturn ? $_t : true;
                unset($_t);
            } else {
                $_importFiles[$filename] = false;
//                if(class_exists('newexception')){
//                    throw new newexception('不存在的文件路径：'.$filename);
//                }
            }
        }
        return $_importFiles[$filename];
    }


    /**
     * 返回唯一的实例(单例模式)
     *
     * 程序开发中,model,module, widget, 或其它类在实例化的时候,将类名登记到doitPHP注册表数组($_objects)中,当程序再次实例化时,直接从注册表数组中返回所要的对象.
     * 若在注册表数组中没有查询到相关的实例化对象,则进行实例化,并将所实例化的对象登记在注册表数组中.此功能等同于类的单例模式.
     *
     * 注:本方法只支持实例化无须参数的类.如$object = new pagelist(); 不支持实例化含有参数的.
     * 如:$object = new pgelist($total_list, $page);
     *
     * <code>
     * $object = Core::singleton('pagelist');
     * </code>
     *
     * @access public
     * @param string $className  要获取的对象的类名字
     * @return object 返回对象实例
     */
    public static function singleton($className) {

        //参数分析
        if (!$className) {
            return false;
        }

        $className = trim($className);

        if (isset(self::$_objects[$className])) {
            return self::$_objects[$className];
        }

        return self::$_objects[$className] = new $className();
    }

}


// spl_autoload_register(function ($class) {
//     include_once CORE_ROOT.'class/' . $class . '.php';
// });