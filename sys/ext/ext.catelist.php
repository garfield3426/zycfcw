<?php



function ext_catelist($id, $link){
    if(!class_exists('Category')){
        require_once LIB_DIR.'/category.class.php';
    }
    return Category::getList($id, $link);
}
?>
