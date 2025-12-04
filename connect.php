<?php
$konek=new mysqli('localhost','root','','spksaww');
if ($konek->connect_errno){
    "Database Error".$konek->connect_error;
}
?>