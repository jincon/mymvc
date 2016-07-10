<?php
/**
 * Created by PhpStorm.
 * User: jincon
 * Date: 16/7/8
 * Time: 下午12:16
 */
class model{

    protected static $_instance = null;
    public $db = null;
    public $table;


    /**
     * 单例模式实例化当前模型类
     *
     * @access public
     * @return object
     */
    public static function init($table='') {
        if (self::$_instance === null) {
            self::$_instance = new self($table);
        }
        return self::$_instance;

    }

    /**
     * 构造函数。
     *
     * @param $table
     * @return object
     */
    private function __construct($table){
        if($table) {
            $this->table = $table;
        }
        $config = config::get();
        $this->db = db::getInstance(array('dsn' => $config['DB_TYPE'].':host='.$config['DB_HOST'].';port='.$config['DB_PORT'].';dbname='.$config['DB_NAME'],'username'=>$config['DB_USER'],'password'=>$config['DB_PWD'] ));
        return true;
    }

    public function table(){
        return $this->table;
    }

//    public function __call($name, $arg) {
//        return call_user_func_array(array($this, $name), $arg);
//    }

    /**
     * @desc：向数据库插入数组格式数据
     * @param：
     * @param $table
     * @param array $param
     */
    public function add($param=array()){
        if(empty($param) || !$this->table){
            return false;
        }
        return $this->db->insert($this->table,$param,1);
    }


    /**
     * @desc：返回插入的id
     * @param：
     * @return int
     */
    public function insert_id(){
        return $this->db->lastInsertId();
    }


    /**
     * @desc：删除某一条数据
     * delete("id=?",array(3))
     *
     * @param：
     * @param $where
     * @param $array
     * @return bool
     */
    function delete($where,$array){
        if(empty($array) || !$this->table){
            return false;
        }
        return $this->db->delete($this->table,$where,$array);
    }

    /**
     * @desc：更新数据
     * update(array('wechat_name'=>'888'),'id = ?',array(96));
     *
     * @param：
     * @param $data
     * @param $where
     * @param $array
     */
    function update($data ,$where, $array){
        if(empty($data) || empty($where) || empty($array) || !$this->table){
            return false;
        }
        $this->db->update($this->table,$data,$where,$array);
    }

    /**
     * @desc：替换
     * replace('test',array('id'=>'4','title'=>'title','url'=>'4444455555'));
     * @param：
     * @param $data
     */
    public function replace($data){
        if(empty($data) || !$this->table){
            return false;
        }
        return $this->db->replace($this->table,$data);
    }

    /**
     * @desc：执行SQL
     * @param：
     * @param $sql
     */
    public function query($sql, $params = array()){
        return $this->db->query($sql, $params = array());
    }

    public function fetchRow($model = PDO::FETCH_ASSOC){
        return $this->db->fetchRow($model);
    }

    public function fetchAll($model = PDO::FETCH_ASSOC){
        return $this->db->fetchAll($model);
    }

    public function error(){
        return $this->db->lastError();
    }

    public function getOne($sql, $params = array()) {
        return $this->db->getOne($sql, $params);
    }

    public function getAll($sql, $params = array()) {
        return $this->db->getAll($sql, $params);
    }

    // 统计数量
//    function count($where=''){
//
//    }

    /* get($id) 取得一条数据 或
    *  get($postquery = '',$cur = 1,$psize = 30) 取得多条数据
    */
//    function get(){
//
//    }
//
//    function get_one($id){
//
//    }
//
//    function get_all($postquery = '',$cur = 1,$psize = 30) {
//
//    }

    /**
     * @desc：返回执行的sql
     * @param：
     * @return string
     */
    public function __string(){
        return $this->db->getLastSql();
    }

}