/* Validation for any form with class val, check inputs with class req */
$(document).ready(function(){

    // add click event to submit button
    $("#main input.subbut").click(function(){
        $("div#errorMsg").remove();
        var errors=new Array() // create array for missed fields
        
        $("form.val input.req").each(function(){ // for each input with class 'req'
            if($(this).attr("value") == ""){
                var missed = $(this).prev("label").text();
                var missed = missed.toLowerCase(); // change to lower case
                var missed = missed.slice(0, -1); // take off star 
                errors.push(missed);
            }
        });
        
        $("form.val textarea.req").each(function(){ // for each input with class 'req'
            if($(this).attr("value") == ""){
                var missed = $(this).prev("label").text();
                var missed = missed.toLowerCase(); // change to lower case
                var missed = missed.slice(0, -1); // take off star 
                errors.push(missed);
            }
        });
        
        // check if array empty, if not, display error message 
        if(errors.length > 0){
            var errorMsg = "";
            errorMsg += "<div id=\"errorMsg\" class=\"hide\">";
            errorMsg += "<p>You have missed the following fields:</p><ul>";
            for(i=0; i< errors.length; i++){
                errorMsg += "<li>" + errors[i] + "</li>";
            }
            errorMsg += "</ul>";
            errorMsg += "</div>";
            $("div.nameadd").before(errorMsg);
            $("div#errorMsg").fadeIn("slow");
            return false;
        }
        return true;
    });
    
});