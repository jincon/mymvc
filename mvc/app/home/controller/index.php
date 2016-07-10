<?php
/**
 * Created by PhpStorm.
 * User: jincon
 * Date: 16/7/5
 * Time: 下午11:48
 */
//
class index_controller extends controller{


//    function __construct(){
//        parent::__construct();
//    }

    function index(){
        //echo $this->module;
        echo "hello mvc";
        $m = model::init("test");


//        echo $m->table();
//        var_dump($m->db->getOne('select * from test limit 1'));

//        $res = $m->query('select * from test limit 1');
//        $res1 = $m->db->fetchRow();
//        var_dump($res1);

    }

    function test(){
        echo "test";

        //get params
//        $page = @(int)$_GET['page'];
//        $pager = $this->lib('Page');
//        echo $pager->loadCss('classic');
//        $pager_html = $pager->total(100)->num(10)->page($page)->url('/?page=')->output();
//        echo $pager_html;

        //echo $this->lib('Captcha')->show();  //验证码

//        $p = $this->lib('page','123');
//        echo $p->p();



        //echo $this->lib('Pinyin')->output('包金昆');

        //echo $this->lib('page');

        //$this->import('function.demo');
//        $d =  $this->import('class.demo');
//        $d->t();

        //echo new code();

        //echo U('ab/cd',array('a'=>1,'b'=>2));

        //$a = '1';
        //$this->assign('a',$a);

        $this->display();
    }
}
