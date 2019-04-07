<?php
require_once("./func.php");

function headerBtn() {
    echo "rr";
    if (!ifUser())
        return false;
    $temp = "<div class='caption'>
    <form class='form-selector' action='http://test1.ru/index.php'>
        <button class='btn btn-default'>На главную</button>
    </form>";
    if (isManagerDoc() || isAdmin())
        $temp .= "
    <form class='form-selector' action='http://test1.ru/client.php'>
        <button class='btn btn-default'>Клиенты</button>
    </form>";
    $temp .= "
    <form  class='form-selector' action='http://test1.ru/auto.php'>
        <button class='btn btn-default'>Автомобили</button>  
    </form>
    </div>";
    return $temp;
}

?>