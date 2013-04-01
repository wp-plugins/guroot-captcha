<?php
const COOKIE_NAME = 'GURCAPTCHA_Cookie';
const SERVER_SIDE_TABLE_NAME = 'GURCAPTCHA_Session';

class GURCAPTCHA_cookie {
    public $id=null;
    private static $instance = null;
    
    /**
     * 
     * @return GURCAPTCHA_cookie
     */
    public static function getInstance(){
        if (is_null(self::$instance))
            self::$instance = new GURCAPTCHA_cookie();                
        return self::$instance;
    }
    
    
    private function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
//        $this->wpdb->show_errors();
        $this->_initServerSideData();
        $this->_cookieInit();        
    }
     
    
    private function _initServerSideData(){
        if(!tableExist($this->wpdb->prefix . SERVER_SIDE_TABLE_NAME)){
            $this->wpdb->query("CREATE TABLE ". $this->wpdb->prefix . SERVER_SIDE_TABLE_NAME."
                (id varchar(30),
                data varchar(300),
                time INT,
                INDEX USING HASH (id),
                INDEX USING HASH (time))
                ENGINE = MEMORY");
        }
    }
    
    public function addServerSideData($id, $data) {    
        // Delete everything older than one month
        $this->wpdb->query('DELETE FROM '. $this->wpdb->prefix . SERVER_SIDE_TABLE_NAME .' WHERE time<unix_timestamp(now() - interval 1 month)');
        // If 30 000 entries.. delete 50 oldests..
        if ($this->wpdb->get_var('SELECT COUNT(*) FROM ' . $this->wpdb->prefix . SERVER_SIDE_TABLE_NAME) >= 30000)
            $this->wpdb->query('DELETE FROM '. $this->wpdb->prefix . SERVER_SIDE_TABLE_NAME .' 
                                ORDER BY time ASC
                                LIMIT 50');
        $r = $this->wpdb->get_row($this->wpdb->prepare('SELECT *  FROM ' . $this->wpdb->prefix . SERVER_SIDE_TABLE_NAME . '
             WHERE id=%s', $id));
        if ($this->wpdb->num_rows) {
            $data = array_merge(unserialize($r->data), $data);            
            $this->wpdb->update($this->wpdb->prefix . SERVER_SIDE_TABLE_NAME, array(
                'data' => serialize($data),
                'time' => time()
                    ), array('id' => $id), array('%s', '%d'), array('%s'));
        } else
            $this->wpdb->insert($this->wpdb->prefix . SERVER_SIDE_TABLE_NAME, array('id' => $id,
                'data' => serialize($data),
                'time' => time()
                    ), array('%s', '%s', '%d'));
    }
    
    /**
     * 
     * @param int $id
     * @param String $key
     * @return Mixed
     */
    public function getServerSideData($id, $key = null) {
        $r = $this->wpdb->get_row($this->wpdb->prepare('SELECT *  FROM ' . $this->wpdb->prefix . SERVER_SIDE_TABLE_NAME . '
             WHERE id=%s', $id));
        $data = unserialize($r->data);
        if (!empty($key)) {
            return $data[$key];
        }
        else
            return false;
    }

    private function _cookieInit() {
        if (!isset($_COOKIE[COOKIE_NAME])) {
            setcookie(COOKIE_NAME, serialize(array()), strtotime('+1 day'));
            $this->id = uniqid();
            $this->addCookieInfo(array("id" => $this->id));
            
        } 
    }

    public function addCookieInfo($arrayInfo) {
        if (isset($_COOKIE[COOKIE_NAME]))
            $r = array_merge($r, $arrayInfo);
        else
            $r = $arrayInfo;
        setcookie(COOKIE_NAME, serialize($r), strtotime('+1 day'));
    }

    public function getCookieInfo($key){
         if (isset($_COOKIE[COOKIE_NAME])){
             $data = unserialize(stripslashes($_COOKIE[COOKIE_NAME]));             
             return $data[$key];
         }
         return false;
    }
    
    public function deletecookieInfo($key) {
        $array = unserialize($_COOKIE[COOKIE_NAME]);
        unset($array[Key]);
        setcookie(COOKIE_NAME, serialize($array), strtotime('+1 day'));
    }
    
    public function getId(){
        if($this->id !== null){
            return $this->id;
        }
        return $this->getCookieInfo('id');
    }
    
}

?>
