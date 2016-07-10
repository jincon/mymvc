<?php
/**
 * Created by PhpStorm.
 * User: jincon
 * Date: 16/7/5
 * Time: 下午11:05
 */


function addslashes_deep($value){
    return is_array($value) ? array_map('addslashes_deep', $value) : (isset($value) ? addslashes($value) : null);
}


function stripslashes_deep($value){
    return is_array($value) ? array_map('stripslashes_deep', $value) : (isset($value) ? stripslashes($value) : null);
}


function htmlspecialchars_deep($value){
    return is_array($value) ? array_map('htmlspecialchars_deep', $value) : (isset($value) ? htmlspecialchars($value) : null);
}


function d($data){
    echo "<pre>";
    print_r($data);
}


/**
 * @desc：获取url参数
 * @param：
 * @param $name
 * @return mixed
@author：
 */
function I($name){
    return $_REQUEST[$name];
}


/**
 * @desc：重组URL
 * @param：
 * @param $url
 * @param $param
 */
function U($url, $param){

    /*
     * print_r($_SERVER);
     * [SCRIPT_NAME] => /demo/mvc/index.php
       [PHP_SELF] => /demo/mvc/index.php
     * */

    if(!$url) return '';
    if(config::get('URL_MODEL') == 1){
        $self = $_SERVER['SCRIPT_NAME'] ? $_SERVER['SCRIPT_NAME'] : '';
        $module = Core::$module;
        $_m = '';
        if($module != config::get('DEFAULT_MODULE')){
            $_m = $module;
        }
        $_tmp = '';
        foreach($param as $k=>$v){
            $_tmp .= $k.'/'.$v.'/';
        }
        return $self.'/'.substr($_m.'/'.$url."/".$_tmp,0,-1);
    }
}


/**
 * @desc：获取配置文件
 * @param：
 * @param $url
 * @param $param
 * @author：
 */
function C($name){
    if(!$name) return false;
    return config::get($name);
}


/**
 * @desc：url跳转
 * @param：
 * @param string $url
 */
function redirect($url=''){
    if($url){
        header('Location:'.$url);
    }
}


/**
 * 优化的require_once
 * @param string $filename 文件地址
 * @return boolean
 */
function require_cache($filename,$isreturn = 0) {
    static $_importFiles = array();
    if (!isset($_importFiles[$filename])) {
        if (file_exists_case($filename)) {
            $_t = require $filename;
            $_importFiles[$filename] = $isreturn ? $_t : true;
            unset($_t);
        } else {
            $_importFiles[$filename] = false;
        }
    }
    return $_importFiles[$filename];
}


/**
 * 区分大小写的文件存在判断
 * @param string $filename 文件地址
 * @return boolean
 */
function file_exists_case($filename) {
    if (is_file($filename)) {
        // if (IS_WIN && APP_DEBUG) {
        //     if (basename(realpath($filename)) != basename($filename))
        //         return false;
        // }
        return true;
    }
    return false;
}


/**
 * 过滤 xss
 * @param：
 * @param $val
 * @return mixed
 */
function remove_xss($val) {
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=@avascript:alert('XSS')>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
        // @ @ search for the hex values
        $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
        // @ @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
    }

    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);

    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(&#0{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
    return $val;
}




