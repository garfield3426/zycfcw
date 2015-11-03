function custom_select(a){
		    var $conbox= $(a);
		    var $selectdrop = $conbox.find(".select-drop");
		    var $value = $conbox.find(".select-value");
		    var $inp = $conbox.find(".select-inp");
		    var $selecttri = $conbox.find(".select-tri");
			var length = $selectdrop.find("li").length;
			var result = null;
			for(var i=0; i<length ;i++)
			{
		        if($selectdrop.find("li").eq(i).find("b").attr("class") == null)
		        {
                    result = false;
		        }
                else
                {
		        	result = true;
					break;
			    }
			}
			if(result)
			{
			   $s=$selectdrop.find("li b.selected").next("span").text();
			   $inp.text($s);
			}
            $selecttri.click(function(){
	            if($selectdrop.css("display")=="none")
					{
						$selectdrop.css({'display':'block'});
					}
				else
					{
						$selectdrop.css({'display':'none'});
					}
			});
			$inp.click(function(){
	            if($selectdrop.css("display")=="none")
					{
						$selectdrop.css({'display':'block'});
					}
				else
					{
						$selectdrop.css({'display':'none'});
					}
			});
			$selectdrop.find("li").click(function(){
			        $inp.text($selectdrop.find("li").eq($(this).index()).find("span").text());
					$value.val($selectdrop.find("li").eq($(this).index()).find("b").text());
					$selectdrop.css({'display':'none'});
		    });
}