<?php
/***************************************************************
 * 树型结构操作类(如果可以写成存储过程最理想)
 *
 * @author yeahoo <yeahoo2000@163.com>
 ***************************************************************/

class Treenode {
	var $f_id = 'id';
	var $f_pid = 'pid';
	var $f_lft = 'lft';
	var $f_rgt = 'rgt';
	var $f_s = 'sequence';
	var $f_level = 'lev';
	var $f_child_num = 'child_num';
	var $table;
	var $db;
	/**
	 * 构造函数
	 * @param string $table 表名
	 * @param object $dbHanle adodb数据库操作句柄
	 */
	function Treenode($table, $dbHandle) {
		$this->db = $dbHandle;
		$this->table = $table;
		//$this->db->debug = true;
	}
	/**
	 * 增加子节点
	 * @param array $data 节点数据
	 * @return bool 
	 */
    function addChild($data){
        $pid = $data[$this->f_pid];
        $sql = "select max({$this->f_s}) from {$this->table} where {$this->f_pid}=$pid";
        $data[$this->f_s] = $this->db->GetOne($sql) + 1;//得到待插入节点的序号
        $sql = "select * from {$this->table} where {$this->f_id} = -1";
        $rs = $this->db->Execute($sql);
        $sql = $this->db->GetInsertSQL($rs, $data);
        $this->db->Execute($sql); //插入节点数据
        if(!$this->db->Affected_Rows()){
            return false;
        }

        $this->buildTree(1,1);	//重建节点左右值
        $this->updateLevel(1);	//生成节点级数值
       
        return true;
    }

   	/**
	 * 修改节点的数据
	 * @param int $id 节点ID号
	 * @param array $data 节点数据
	 * @return bool
	 */ 
    function updateNode($id, $data) {
    	
        $id = $this->_getId($id);
        unset($data[$this->f_id]); 
        unset($data[$this->f_lft]);
        unset($data[$this->f_rgt]);
        unset($data[$this->f_s]);
        unset($data[$this->f_level]);
        //<==防止直接修改节点之间的关系,因为可能会导致节点关系混乱
        $sql = "select * from {$this->table} where {$this->f_id} = {$id}";
        $rs = $this->db->Execute($sql);
        $sql = $this->db->GetUpdateSQL($rs, $data);
        if (!$this->db->Execute($sql)) {
            return false;
        }
        return true;
    }

    /**
	 * 删除节点
	 * @param int $id 节点ID号
	 */
    function delNode($id) {
        $childNode = $this->getChild($id);
        $sql = "delete from {$this->table} where {$this->f_id} in($childNode)";
        $rs = $this->db->Execute($sql); //把指定节点和其下的子节点一并删除,
        $this->buildTree(1,1);
    }

    /**
	 * 移动节点位置
	 * @param int $id 节点ID号
	 * @param string 'up'|string 'down' 移动方向,'up'或'down'
	 * @param int $step 移动的步长
	 */
    function moveNode($id,$direction,$step=1){
        $id = $this->_getId($id);
        $sql = "select {$this->f_s},{$this->f_pid} from {$this->table} where {$this->f_id}={$id}";
        $row = $this->db->GetRow($sql);
        $pos = $row[$this->f_s];  //原次序
        $pid = $this->_getId($row[$this->f_pid]);
        if('up' == $direction){
            //计算新的次序值,(上移)
            $pos_to = (($pos - $step)<1) ? 1 : $pos - $step;
        }
        else{
            //计算新的次序值,(下移)
            $sql = "select max({$this->f_s}) from {$this->table} where $this->f_pid = $pid";
            $sequence_max = $this->db->GetOne($sql);
            $pos_to = ($pos + $step)>$sequence_max ? $sequence_max : $pos + $step;
        }
        $sql = "update {$this->table} set {$this->f_s} = 0 where {$this->f_id} = {$id}";
        $rs = $this->db->Execute($sql);
        $sql = "update {$this->table} set {$this->f_s} = {$pos} where {$this->f_pid} = {$pid} and {$this->f_s}={$pos_to}";
        $rs = $this->db->Execute($sql);
        $sql = "update {$this->table} set {$this->f_s} = {$pos_to} where {$this->f_id} = '{$id}'";
        $rs = $this->db->Execute($sql);
        $this->buildTree(1,1);
    }

    /**
	 * 重建指定节点的子节点的lft,rgt值
	 * @param int $id 节点的ID号
	 * @param int $left 节点left值
	 */
    function buildTree($id, $left) {
        $id = $this->_getId($id);
        $right = $left +1;
        $sql = "SELECT {$this->f_id} FROM {$this->table} WHERE {$this->f_pid} = {$id} order by {$this->f_s} asc";
        $rs = $this->db->Execute($sql);
        
        while (!$rs->EOF) {
            $right = $this->buildTree($rs->fields[$this->f_id], $right);
            $rs->MoveNext();
        }
        $sql  = "UPDATE {$this->table} SET {$this->f_lft} = {$left}, {$this->f_rgt} = {$right}, {$this->f_child_num} = ({$this->f_rgt}-{$this->f_lft}-1)/2 WHERE {$this->f_id} = {$id}";
        
        $this->db->Execute($sql);
        return $right +1;
    }

    /**
	 * 重新生成指定节点和其下的所有节点级数值 
	 */
    function updateLevel($id){
        $nodes = $this->getChild($id,false);
        $nodes[] = $id;
        foreach($nodes as $v){
            $id = $this->_getId($v);
            $level = $this->getLevel($v);
            $sql = "update {$this->table} set {$this->f_level}={$level} where {$this->f_id}={$id}";
            $this->db->Execute($sql);
        }
    }

    /**
	 * 重新挂接节点
	 * @param int $id 节点ID号
	 * @param int $to 要挂接到的节点ID号
	 */
    function remountNode($id,$to){
        $id = $this->_getId($id);
        $child = $this->getChild($id,false);
        if(!$this->db->GetOne("select count(*) from {$this->table} where {$this->f_id}={$to}")){
            die("错误!将要挂接到的节点'{$to}'不存在!");
        }
        if(in_array($to,$child)){
            die("错误!挂接到的节点'{$to}'是待挂接节点'{$id}'的一个子节点!");
        }
        $sql = "select max($this->f_s) from {$this->table} where pid = {$to}";
        $sequence = $this->db->getOne($sql) + 1;
        $sql = "update {$this->table} set {$this->f_pid} = $to, {$this->f_s}=$sequence where {$this->f_id}={$id}";
        $this->db->Execute($sql);
        $this->buildTree(1,1);
        $this->updateLevel(1);
    }

    /**
	 * 得到指定节点的级数
	 * @param int $id 节点ID号
	 */
    function getLevel($id){
        $id = $this->_getId($id);
        $sql = "select count(*) from {$this->table} as c1,{$this->table} c2 where c1.{$this->f_id} = {$id} and c1.{$this->f_lft} between c2.{$this->f_lft} and c2.{$this->f_rgt}";
        return $this->db->GetOne($sql);
    }

    /**
	 * 判断ID号是否已存在
	 */
    function isExist($id, $me = NULL){
        $id = $this->_getId($id);
        $sql = "select {$this->f_id} from {$this->table} where {$this->f_id}={$id}";
        $another = $this->db->GetOne($sql);
        if (!strlen($another)) return false;
        else if ($another == $me) return false;
        else return true;
    }

    /**
	 * 得到指定节点的所有子节点
	 * @param int $id 节点ID号
	 * @param string $mode 返回的数据类型
	 * 'str'为用','号连接的字串其它为数组 
	 */
    function getChild($id,$strMode = true){
        $id = $this->_getId($id);
        $sql = "select n1.{$this->f_id} as id from {$this->table} n1,{$this->table} n2 where n2.{$this->f_id}={$id} and n1.{$this->f_lft} between n2.{$this->f_lft} and n2.{$this->f_rgt}";
        $rs = $this->db->Execute($sql);
        while(!$rs->EOF){
            if($strMode)
                $node .= $rs->fields[$this->f_id].',';
            else $node[] = $rs->fields[$this->f_id];
            $rs->MoveNext();
        }
        if($strMode){
            $node = substr($node,0,-1);
            if(strlen($node)){
                return $node;
            }
            else return '0';
        }
        else return $node;
    }

    
    function _getId($id){
        $sql = "select * from {$this->table} where id=-1";
        $this->db->Execute($sql);
        $columns = $this->db->MetaColumns($this->table);
        $type = $columns[strtoupper($this->f_id)]->type;
        if($type == 'CHAR' || $type == 'VARCHAR'){
            return strpos($id, "'") ? "'{$id}'" : $id;
        }
        return $id;
    }
}
?>
