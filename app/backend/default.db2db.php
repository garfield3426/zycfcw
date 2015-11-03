<?php
class Db2db extends Action{
    public $AuthLevel = ACT_OPEN;
    private
        $enabled = true,
        $dbRead,
        $dbWrite;

    
    public function __construct(){
        parent::__construct();
        if(!$this->enabled) exit();

        $this->dbRead = dbFactory(array(
            'driver' => 'ado_access',
            'host' => 'Driver=Microsoft Access Driver (*.mdb);Dbq='.realpath(DB_DIR.'/db_old.mdb').';Uid=;Pwd=;',
            'dbname' => '',
            'user' => '',
            'pass' => ''
        ));
        /*
        $this->dbWrite = dbFactory(array(
            'driver' => 'pdo',
            'dsn' => 'sqlite:'.DB_DIR.'/db.sqlite',
        ));
         */

        $this->dbWrite = dbFactory(array(
            'driver' => 'mysql',
            'host' => 'localhost',
            'dbname' => 'vennger_com',
            'user' => 'root',
            'pass' => 't710s'
        ));

        //$this->dbRead->debug = true;
        //$this->dbWrite->debug = true;
        $this->dbWrite->Execute('set names "utf8"');
    }

    
    public function process(){
        $tables = array(
            /*
            'fw_article',
            'fw_category',
             */
            'fw_guestbook',
            /*
            'fw_jobs',
            'fw_join',
            'fw_log',
            'fw_mb_group',
            'fw_mb_group_priv',
            'fw_mb_notice',
            'fw_member',
            'fw_orderform',
            'fw_pd_logistics',
            'fw_pd_order',
            'fw_pd_order_list',
            'fw_poll',
            'fw_poll_log',
             */
            'fw_product',
            /*
            'fw_resume',
            'fw_sys_group',
            'fw_sys_group_priv',
            'fw_sys_permissions',
            'fw_sys_user'
             */
        );

        foreach($tables as $v){
            $rsRead = $this->dbRead->Execute("select * from $v");
            $rsWrite = $this->dbWrite->Execute("select * from $v");

            while(!$rsRead->EOF){
                $sqlWrite = $this->dbWrite->GetInsertSQL($rsWrite, $rsRead->fields);
                //Io::writeFile(DB_DIR.'/db.sql', $sqlWrite.";\n", 'a');
                $this->dbWrite->Execute($sqlWrite);

                $rsRead->MoveNext();
            }
        }
    }
}

function dbFactory($conf){
    include_once(LIB_DIR.'/adodb/adodb.inc.php');
    include_once(LIB_DIR.'/adodb/adodb-errorhandler.inc.php');

    $ADODB_FETCH_MODE= ADODB_FETCH_ASSOC;

    $instance =  ADONewConnection($conf['driver']);
    if(!$instance->Connect($conf['host'], $conf['user'], $conf['pass'], $conf['dbname'])){
        trigger_error(_Error(0x000103, array(__FUNCTION__)));
    }

    return $instance;
}
?>
