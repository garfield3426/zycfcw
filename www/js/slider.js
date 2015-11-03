$(document).ready(function(){

	$("div#slider").toggleClass("hasJs"); /* has js */
	var $effectFlag = 0; /* prevent queing, flags if okay to do effect */
	var $curstep = 1; //current step
   	
	
	// next button
	$("#nextbut").click(function(){
		if($effectFlag == 0 && $curstep != 5){
			$effectFlag = 1;
			$("#slideWrap").animate({left:'-=660'}, 1500, function(){
				$effectFlag = 0;
				setCurrent($curstep += 1);
			});
		}
    });
	
	//previous  button
	$("#prevbut").click(function(){
		if($effectFlag == 0 && $curstep != 1){
			$effectFlag = 1;
			$("#slideWrap").animate({left:'+=660'}, 1500, function(){
				$effectFlag = 0;
				setCurrent($curstep -=1);
			});
		}
    });
	
	// steps number buttons
	$("ul#steps a").click(function(){;		
		if($effectFlag == 0){
			$effectFlag = 1; // effect in progress, prevent queing
			
			var $currentPos = $("#slideWrap").css("left"); 
			var $mult = $(this).attr('rel') - 1; // get value to multiply width by
			var $targetPos = 660 * $mult;  //multiply width of area by step to go to 
			
			$currentPos = parseFloat($currentPos); //convert to number
			
			// see if target step is before or after  current position and create string accordingly
			if($currentPos > $targetPos){
				var $moveby = "+=" + ($currentPos + $targetPos);
			}else{
				var $moveby = "-=" + ($currentPos + $targetPos);
			}
			
			$("#slideWrap").animate({left:$moveby, height: $('#step'+($mult+1)).height()+30 }, 1500, function(){
				$effectFlag = 0;
                setCurrent($mult+1); // set current step
                //$("#slideWrap").height($('#step'+($mult+1)).height()+30);
                //alert( $('#step'+($mult+1)).height() );
			});
		}
		return false; //stop anchor link
	});

	function setCurrent($stepNum){
		$curstep = $stepNum;
		$("ul#steps a").removeClass("cur"); // reset all steps to not current
		$("#stepBut" + $stepNum).toggleClass("cur"); // set current
	}
	

});
