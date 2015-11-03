<?php
/*-----------------------------------------------------+
 * 发布友情链接
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
include_once(LIB_DIR.'/category.class.php');
class Create extends Page{
    var $db;
    var $tab = 'video';

    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
    }

    
    function process(){
        //检查是否提交数据
        if(!empty($this->input['submit'])){
            $this->submit();
            return;
        }
        //输出页面
        $this->export();
    }

    
    function submit(){
        //获取提交的数据
        $item = stripQuotes(trimArr($this->input['item']));
        //检查数据合法性
        $emsg = $this->validate($item);
        //如果数据不合法则输出
        if(count($emsg)){
            $this->export($item, $emsg);
            return;
        }
        //数据合法则写入数据
        if(!$this->insertDb($item)){
            //写入失败则输出信息
            Core::raiseMsg($this->lang->get('e_article_invalidationManipulation'));
        }
        Core::redirect(Core::getUrl('showlist'));
    }

    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_video_createTitle'),
            'formAct' => Core::getUrl(),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->addTplFile('form');
        $this->display();
    }

    
    function getJsonData($item, $emsg=null){
        //如果只支持一种语言,则给lang加上默认值.
        if(count($this->conf->get('language_support')) < 2){
            $item['lang'] = strlen($item['lang']) ? $item['lang'] : $this->conf->get('language_default');
        }
        return array(
            'emsg' => $emsg,
            'item[state]' => array(
                'display' => true,
                'value' => $item['state'],
                'list' => array(
                    '0' => $this->lang->get('global_stateDisabled'),
                    '1' => $this->lang->get('global_stateEnabled'),
                    //'2' => $this->lang->get('global_stateDelete'),
                ),
            ),
            'item[lang]' => array(
                'value' => $item['lang'],
                'list' => $this->lang->getSelectList(),
            ),
            //类别选择器
            'item[cate_id]' => Category::getSelList(
                $item['cate_id'],
                $this->conf->get('videoCateId'),
                false,
                '',
                false,
                $this->lang->get('j_global_cateSelDisableMsg')
            ),
            'goBackLink' =>array('url' => Core::getUrl('showlist','','',true)),
        );
    }

    
    function insertDb($item){
        unset($item['id']);//防止ID号被修改
        if($_FILES['file']['name']){
        	$item['name'] = $this->getVideo('file');
        }
        $item['editor'] = $this->sess->getUserId();
        $item['put_time'] = time();
        $sql = "select * from {$this->tab} where id=-1";
        $sql = $this->db->GetInsertSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }
    
    function getVideo($fileVideo){
    	$path = WEB_DIR.'/swf/';							// 保存图片的路径
    	$tp = array("application/octet-stream");	// 允许上传的文件格式
		if( !file_exists($path) ){											// 检查保存图片的路径是否存在
			mkdir( $path, 0700);											// 如果路径不存在就创建，并给予最高权限
		    chmod( $path, 0777 );
		}//END IF
		chmod( $path, 0777 );
		if( !in_array($_FILES["$fileVideo"]["type"], $tp) ){					// 检查上传文件是否在允许上传的类型
			PhpBox('格式不对，必须上传格式为FLV的文件！');
			GotoPage(h);
		    exit;
		}//END IF
		$max = 20480000;
		if($_FILES["$fileVideo"]["size"]>$max){
			PhpBox('文件过大,请上传小于20M的文件！');
		    GotoPage(h);
		    exit;
		}
		$aFile1 = explode('.', $_FILES["$fileVideo"]["name"]);
        $newName = time();
        $file3 = $path.$newName.".".$aFile1[count($aFile1)-1];
        $file2 = $newName.".".$aFile1[count($aFile1)-1];
     
		// 将上传的文件移动到指定的位置存放
		$result = move_uploaded_file($_FILES["$fileVideo"]["tmp_name"], $file3);//特别注意这里传递给move_uploaded_file的第一个参数为上传到服务器上的临时文件
    	return $file2;
    }

    
    function isExist($title){
        $sql = "select count(*) from {$this->tab} where sign='{$title}'";
        return $this->db->GetOne($sql) ? true : false;
    }

    
    function validate($i){
        $e = array();
        if(!$i['title']){
            $e['title'] = $this->lang->get('e_video_titleIsEmpty');
        }
        if(!$i['logo']){
            $e['logo'] = $this->lang->get('e_video_logoIsEmpty');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
