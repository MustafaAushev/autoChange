<?php
session_start();
require_once("./db/db.php");
require_once("./lib/func.php");
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

if (!isAdmin()&&!isManagerDoc()) {
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
}
else {
echo "

<body>
<header>
    <div class='caption'>";
echo headerBtn();
        
echo "        <form class='form-selector' action='./newClient.php'>
        <button class='btn btn-default newClient'>Добавить нового клиента</button> 
        </form>";

$Sql="SELECT name,id FROM client ";
$result=mysql_query($Sql,$conn);
$arr=array(
    "name"=>array(),
    "id"=>array()
);
if (!$result) exit();
while ($row=mysql_fetch_array($result)) {
    array_push($arr["name"],$row["name"]);
    array_push($arr["id"],$row["id"]);
}
echo " <div class='flright'>
        <div class='btn seacrh' >
        <select class='form-selector btn btn-default flright'>
        <option class='clientFilter' client='all'>Все</option>";
foreach($arr["name"] as $key => $value)
    echo "<option class='clientFilter' client='".$arr["id"][$key]."'>$value</option>";
echo " </select>
        </div>
    </div>
    <div class='flright '>
        <form>
            <div class='clientType btn btn-default' client='fiz'  value=0>Физ. лицо</div>
            <div class='clientType btn btn-default' client='yur'  value=1>Юр. лицо </div>
        </form>
    </div>
</header>
<div class='body' id='body'>";
    if (true) {
        $flag=true;
        $Sql="SELECT * FROM client order by name";
        $result=mysql_query($Sql,$conn);
        $temp= "<table border='black' class='tabo'>
            <tr>
                <th>ФИО </th><th>Тип</th><th>Адрес</th><th>ТЕЛЕФОН</th>  
            </tr><tr>";
        while ($row=mysql_fetch_array($result)) {
            if ($row["typeClient"]) {
                $type = "Юр. лицо";
                $classType = 'yur';
            } else {
                $type = "Физ. лицо";
                $classType = 'fiz';
            }
            $temp.="<tr id='name".$row["id"]."' class='$classType cl'>
                    <td class='mar'><div client=".$row['id']." type='client' class='btn iconFull icon flleft' data-title='Подробнее'> 
                    <span  class='  glyphicon glyphicon-hand-right'></span></div>
                    ".$row["name"]."</td><td>$type</td>
                    <td>".$row["from"]."</td><td>".$row["phone"]."</td>";
        }
        $temp.="</table>";
        echo $temp;
       }
echo "
</div>
<footer>
</footer>
<script src='js/jQuery/jQuery.js'></script>
<script src='js/script.js'></script>
</body>
</html>";
}