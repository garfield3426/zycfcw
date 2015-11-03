


function getElementsByClassName(className, pElement) {
	var child = (pElement || document.body).getElementsByTagName('*');
	var elms = new Array();
	for (var i = 0; i < child.length; i++)
            if(child[i].className.match(new RegExp("(^|\\s)" + className + "(\\s|$)")))
                elms.push(child[i]);
	return elms;
}

var GridView = {
    init:function(tid){
        var colors = ['#f5f5f5','#ffffff'];
        GridView.hl = '#f0f0cc';
        
        var tb = document.getElementById(tid);
        var tbody = tb.getElementsByTagName('tbody')[0];

        var elms = tbody.getElementsByTagName('input');
        var cbs = new Array();
        var rows = new Array();
        for(var i = 0; i < elms.length; i++){
            if('checkbox' == elms[i].type){
                cbs.push(elms[i]);
                rows.push(elms[i].parentNode.parentNode);
            }
        }
        if(rows.length){
            var btnC = getElementsByClassName('btnCheck', tb)[0] || {};
            var btnUc = getElementsByClassName('btnUncheck', tb)[0] || {};
            btnC.cbs = btnUc.cbs = cbs;
            btnC.onclick = GridView.checkAll;
            btnUc.onclick = GridView.uncheckAll;
            for(var i=0; i < rows.length; i++){
                rows[i].bgColor = rows[i].oBg = rows[i].oldBg = GridView.cycle(colors);
                rows[i].onmouseover = GridView.highlight;
                rows[i].onmouseout = GridView.revert;
                if(cbs[i].checked){
                    rows[i].bgColor = rows[i].oBg = GridView.hl;
                }
                cbs[i].onclick = GridView.cbEvent;
            }
        }
    },
    checkAll:function(){
        for(i=0; i<this.cbs.length; i++){
            GridView.setCheckbox(this.cbs[i]);
        }
    },
    uncheckAll:function(){
        for(i=0; i<this.cbs.length; i++){
            GridView.setCheckbox(this.cbs[i], 0);
        }
    },
    setCheckbox:function(o, val){
        row = o.parentNode.parentNode;
        v = null == val? o.checked : !val;
        if(v){
            row.bgColor = row.oBg = row.oldBg;
            o.checked = 0;
            row.onmouseover = GridView.highlight;
            row.onmouseout = GridView.revert;
        }else{
            row.bgColor = row.oBg = GridView.hl;
            o.checked = 1;
            row.onmouseover = null;
            row.onmouseout = null;
        }
    },
    cbEvent:function(){
        if(this.checked){
            GridView.setCheckbox(this, true);
        }else{
            GridView.setCheckbox(this, false);
        }
    },
    highlight:function(){
        this.bgColor = '#dbeaf5';
    },
    revert:function(){
        this.bgColor = this.oBg;
    },
    cycle:function(values){
        if(GridView.vIndex == null){
            GridView.vIndex = 0;
        }
        var v = values[GridView.vIndex++];
        if(GridView.vIndex >= values.length){
            GridView.vIndex = 0;
        }
        return v;
    }
}
