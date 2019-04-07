<?php
session_start();
require_once('./db/db.php');
require_once('./lib/func.php');
echo "<!Doctype-html>
<html>
    <head>
    <meta charset='UTF-8'>
    <title> Сайт</title>
    <link rel='stylesheet' href='style/bootstrap.css' type='text/css'/>
    <link rel='stylesheet' href='style/bootstrap.min.css' type='text/css'/>
    <link rel='stylesheet' href='style/bootstrap-theme.css' type='text/css'/>
    <link rel='stylesheet' href='style/bootstrap-theme.min.css' type='text/css'/>
    <link rel='stylesheet' href='style/style.css' type='text/css'/>
    <script src='js/script.js'></script>
</head>";
if (!isManagerDoc()&&!isAdmin()&&!isMangerAuto())
{
    echo "<body>
    <header>
        <div class='caption'>
            <form class='form-selector' action='http://test1.ru'>
                <button class='btn btn-default'>На главную</button>
            </form></div>
            </header>
            ERROR... У ВАС НЕТ ДОСТУПА
            </body>
            ";
            exit();
}
echo "

<body>
<header>
    <div class='caption'>";
        echo headerBtn();
        echo "</div></header>
<div class='body' style='overflow:scroll;'>";
$arr = clientNameId();

echo "<form id='newAutoData'>
Выберите имя клиента:<br>
<select name='client' class=' btn btn-default'>";
foreach($arr["name"] as $key=>$value)
{
    echo "<option value='".$arr["id"][$key]."'>".$arr["id"][$key].". $value </option>";
}
echo "</select>";
dataAuto("");
echo "<div class='btn info' id='saveNewAuto'>Сохранить</div>
</form>
</div>
</body>
<footer>
</footer>
<script src='js/jQuery/jQuery.js'></script>
<script src='js/script.js'></script>
</html>";




?>