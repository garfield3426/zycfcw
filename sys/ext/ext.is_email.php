<?php



function ext_is_email($email){
    return preg_match('/^[a-z0-9_\-\.]+@[a-zZ0-9_-]+\.[a-z0-9_-]+[a-z\.]+/', $email) ? true : false;
}
?>
