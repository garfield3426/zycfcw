
var UlView = {
	init:function(tid){
		UlView.hl = '#f99';
		UlView.bg = '#dbeaf5';
		var tb = $(tid);
		var cbs = UlView.getCheckboxs(tb);
		var tbody = tb.getElementsByTagName('tbody')[0];
		if(cbs.length){
			var btnC = getElementsByClassName('btnCheck', tid)[0];
			var btnUc = getElementsByClassName('btnUncheck', tid)[0];
			btnC.cbs = btnUc.cbs = cbs;
			btnC.onclick = UlView.checkAll;
			btnUc.onclick = UlView.uncheckAll;
			for(var i=0; i < cbs.length; i++){
				if(cbs[i].checked)
					UlView.setBg(cbs[i],UlView.hl);
				else UlView.setBg(cbs[i],UlView.bg);
				cbs[i].onclick = UlView.cbEvent;
			}
		}
	},
	uncheckAll:function(){
		for(i=0; i<this.cbs.length; i++){
			UlView.setCheckboxs(this.cbs[i], 0);
		}
	},
	checkAll:function(){
		for(i=0; i<this.cbs.length; i++){
			UlView.setCheckboxs(this.cbs[i]);
		}
	},
	cbEvent:function(){
		if(this.checked){
			UlView.setBg(this,UlView.hl);
		}else{
			UlView.setBg(this,UlView.bg);
		}
	},
	setCheckboxs:function(o, val){
		v = null == val? o.checked : !val;
		if(v){
			UlView.setBg(o,UlView.bg);
			o.checked = 0;
		}else{
			UlView.setBg(o,UlView.hl);
			o.checked = 1;
		}
	},
	setBg:function(obj,color){
		var o = obj.parentNode.parentNode;
		o.style.backgroundColor=color;
		o.parentNode.parentNode.parentNode.style.borderColor=color
	},
	//查找所有的Checkbox
	getCheckboxs:function(tbody){
		var elms = tbody.getElementsByTagName('input');
		var cbs = new Array();
		for(var i = 0; i < elms.length; i++){
			if('checkbox' == elms[i].type){
				cbs.push(elms[i]);
			}
		}
		return cbs;
	}
}