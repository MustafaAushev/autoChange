<?php

$conn=mysql_connect("localhost","root","")
    or die("Нет соединения".mysql_error());

mysql_select_db("autoChange",$conn);




?>