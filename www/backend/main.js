$import('js/jquery.js');
$import('js/jqext.js');
$(document).ready(function(){
    $('input[@type=button]').addClass('btn');
    $('input[@type=submit]').addClass('btn');
    $('input[@type=reset]').addClass('btn');
    $('#header').load(headerUrl);
    $('#sideBar').load(mainnavUrl, function(){
        $('#mainNav').menu(true);
    });
});
