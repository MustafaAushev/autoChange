<?php
session_start();
require_once('./db/db.php');
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
if (($_SESSION['login']!="manager1")&&($_SESSION["login"]!="admin")&&($_SESSION["login"]!="manager2"))
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
    <div class='caption'>
        <form class='form-selector' action='./index.php'>
            <button class='btn btn-default'>На главную</button>
        </form>
        <form class='form-selector' action='./client.php'>
            <button class='btn btn-default'>Клиенты</button>
        </form>
        <form  class='form-selector' action='./auto.php'>
            <button class='btn btn-default'>Автомобили</button>  
        </form>
        </div>
        </header>
<div class='body' style='overflow:scroll;'>";
$Sql="SELECT name,id FROM client ";
$result=mysql_query($Sql,$conn);
$arr=array(
    "name"=>array(),
    "id"=>array()
);
while ($row=mysql_fetch_array($result))
{
    array_push($arr["name"],$row["name"]);
    array_push($arr["id"],$row["id"]);
}

echo "<form id='newAutoData'>
Выберите имя клиента:<br>
<select name='client' class=' btn btn-default'>";
foreach($arr["name"] as $key=>$value)
{
    echo "<option value='".$arr["id"][$key]."'>".$arr["id"][$key].". $value </option>";
}
echo "</select>";
echo "
    <div>Модель авто: <input id='model' class='input' name='model' type='text'> </div>
    <div>Тип, категория: <input id='typeauto' class='input' name='typeauto' type='text'> </div>
    <div>Гос. номер: <input id='number' class='input' name='number' type='text'> </div>
    <div>Год выпуска: <input class='input' name='year' type='text'> </div>
    <div>VIN авто: <input id='vin'  class='input' name='vin' type='text'> </div>
    <div>Номер кузов: <input class='input' name='kuzov' type='text'> </div>
    <div>Номер шасси: <input class='input' name='shassi' type='text'> </div>
    <div>Модель двигателя: <input class='input' name='dvig' type='text'> </div>
    <div>Мощность двигателя: <input class='input' name='pdvig' type='text'> </div>
    <div>Максимальная масса: <input class='input' name='maxmass' type='text'> </div>
    <div>Масса: <input class='input' name='massa' type='text'> </div>
    <div>ПТС: <input class='input' name='pts' type='text'> </div>
    <div>СТС: <input class='input' name='sts' type='text'> </div>
    <div>Вносимые изменения: <input class='input' name='change' type='text'> </div>
    <div>Дата сдачи: <input type='date' class='input' name='date' value='2018-01-01' min='2018-01-01' max='2018-12-31'></div>
    <div class='btn info' id='saveNewAuto'>Сохранить</div>
</form>
</div>
</body>
<footer>
</footer>
<script src='js/jQuery/jQuery.js'></script>
<script src='js/script.js'></script>
</html>";




?>