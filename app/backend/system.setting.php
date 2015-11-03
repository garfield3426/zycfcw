<?php
/*-----------------------------------------------------+
 * 系统参数设置 
 *
 * @author yeahoo2000@gmail.com
 +-----------------------------------------------------*/
class Setting extends Page{
    
    function __construct(){
        parent::__construct();
    }

    
    function process(){
        //检查是否提交数据
        if(empty($this->input['submit'])){
            $item = $this->conf->conf;
            //处理Access设置
            if('ado_access' == $item['database']['driver']){
                $item['database'] = $this->getAccess($item['database']);
            }
            //输出页面
            $this->export($item);
            return;
        }

        //获取提交的数据
        $item = stripQuotes(trimArr($this->input['item']));
        //检查数据合法性
        if(count($emsg = $this->validate($item))){
            $this->export($item, $emsg);
            return;
        }
        //处理Access设置
        if('ado_access' == $item['database']['driver']){
            $item['database'] = $this->setAccess($item['database']);
        }
        //更新文件
        $this->conf->update($item);
        $this->conf->save();
        Core::raiseMsg(
            $this->lang->get('p_system_settingComplete'),
            array($this->lang->get('p_system_goBack')=>Core::getUrl())
        );
    }

    
    function setAccess($database){
        $database['host'] = "PROVIDER=Microsoft.Jet.OLEDB.4.0;DATA SOURCE=".ROOT."{$database['host']};USER ID={$database['user']};PASSWORD={$database['pass']};";
        $database['user'] = '';
        $database['pass'] = '';
        $database['dbname'] = '';
        $database['encode'] = '';
        return $database;
    }

    
    function getAccess($database){
        //host
        $host = str_replace("PROVIDER=Microsoft.Jet.OLEDB.4.0;DATA SOURCE=".ROOT, '', $database['host']);
        $data = strstr($host, ';');
        $host = str_replace($data, '', $host);
        //user
        $pass = strstr($data, 'PASSWORD=');
        $user = str_replace(';', '', str_replace('USER ID=', '', str_replace($pass, '', $data)));
        //pass
        $pass = str_replace(';', '', str_replace('PASSWORD=', '', $pass));
        //
        $database['host'] = $host;
        $database['user'] = $user;
        $database['pass'] = $pass;
        $database['dbname'] = '';
        $database['encode'] = '';
        return $database;
    }

    
    function export($item=null, $emsg=null){
        $this->assign('jsonData', $this->getJsonData($item, $emsg));
        $this->assign('item', $item);
        $this->assign('title', $this->lang->get('p_system_settingTitle'));
        $this->display();
    }

    
    function getJsonData($item, $emsg=null){
        $langList = array();
        foreach($this->conf->get('language_support') as $v){
            $langList[$v] = $v;
        }
        $driverList = array();
        foreach($this->conf->get('database','driver_support') as $v){
            $driverList[$v] = $v;
        }
        return array(
            'emsg' => $emsg,
            /*
            'item[core_sef_query_string]' => array(
                'value' => $item['core_sef_query_string'],
                'list' => array(
                    '1' => $this->lang->get('global_boolY'),
                    '0' => $this->lang->get('global_boolN')
                )
            ),
             */
            'item[alipay][logistics_payment]' => array(
                'value' => $item['alipay']['logistics_payment'],
                'list' => $this->lang->get('alipay','logistics_payment'),
            ),
            'item[alipay][logistics_type]' => array(
                'value' => $item['alipay']['logistics_type'],
                'list' => $this->lang->get('alipay','logistics_type'),
            ),
            'item[ob_gzhandler]' => array(
                'value' => $item['ob_gzhandler'],
                'list' => array(
                    '1' => $this->lang->get('global_boolY'),
                    '0' => $this->lang->get('global_boolN')
                )
            ),
            'item[language_default]' => array(
                'value' => $item['language_default'],
                'list' => $langList
            ),
            'item[database][driver]' => array(
                'value' => $item['database']['driver'],
                'list' => $driverList,
            ),
            'goBackLink' =>array('url' => Core::getUrl('index','default','',true)),
        );
    }

    
    function validate($i){
        //将错误信息的key转换为表单的name
        $emsg = array();
        //foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
