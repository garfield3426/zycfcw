<?php
/***************************************************************
 * 表单元素生成器
 * 
 * @author yeahoo2000@163.com 
 ***************************************************************/
class Form{
	
	/**
	 * 生成"select"表单元素
	 * @param string $name Select的名称和ID号
	 * @param array $data 选项内容数据
	 * @param string|int $def 默认的选中项
	 * @return string   HTML字串
	 */
	function select($name, $data, $def=null, $disable=false, $js=""){
		$options = '';
		foreach($data as $k=>$v){
			$s = strlen($def) && ($k == $def)?" selected='selected'":'';
			$options .= "<option value='$k'$s>$v</option>";
		}
		$disable = $disable? " disabled='disabled'" : '';
		return "<select id='$name' name='$name'$disable $js>$options</select>";
	}

	/**
	 * 生成"checkbox"表单元素
	 * @param string $name checkbox的名称和ID号
	 * @param array $data 选项内容数据
	 * @param string|int $def 默认的选中项]
	 * @param string $addon 附加字串
	 * @return string   HTML字串
	 */
	function checkbox($name, $data, $def=null, $addon = null){
		$items = '';
		foreach($data as $k=>$v){
			$c = strlen($def) && ($k == $def)?" checked='checked'":'';
			$items .= "<input id='$name' name='$name' type='checkbox' value='$k'$c $addon/>$v ";
		}
		return $items;
	}
	
	/**
	 * 生成"radio"表单元素
	 * @param string $name radio的名称和ID号
	 * @param array $data 选项内容数据
	 * @param string|int $def 默认的选中项
	 * @return string   HTML字串
	 */
	function radio($name,$data,$def=null){
		$items = '';
		foreach($data as $k=>$v){
			$c = strlen($def) && ($k == $def)?" checked='checked'":'';
			$items .= "<input id='$name' name='$name' type='radio' value='$k'$c />$v ";
		}
		return $items;
	}
	
	function yn($name,$def=null){
		$data = array(
				'' => '',
				'1' => '是',
				'0' => '否'
				);
		return Form::select($name,$data,$def);
	}
	function sex($name,$def=null){
		$data = array(
				'0' => '男',
				'1' => '女'
				);
		return Form::radio($name,$data,$def);
	}
}
?>
