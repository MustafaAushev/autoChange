<?php

session_start();
require_once("./db/db.php");
require_once('./lib/func.php');

global $conn;

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
    <script src='./js/script.js'></script>
</head>";

if (isAdmin()&&isManagerDoc()&&isManagerAuto())
    echo "<body>
    <header>
        <div class='caption'>
            <form class='form-selector' action='http://test1.ru'>
                <button class='btn btn-default'>На главную</button>
            </form>
            </div>
        </header>
        ERROR... У ВАС НЕТ ДОСТУПА
        </body>"
        ;
else
{
echo "


<body>
    <header>
        <div class='caption'>";
            echo headerBtn();
             echo "
             <form action='./newAuto.php' class='form-selector'>
            <button class='btn btn-default newAuto' >Добавить новый автомобиль</button>
            </form>
            <select class='form-selector btn btn-default flright'>
            <option class='optionStadia' stadia='all'>Все стадии</option>";
            if (isManagerDoc() || isAdmin())
                echo "  <option class='optionStadia' stadia=1>Получение ПЗ</option>
                        <option class='optionStadia' stadia=4>Получение ПР</option>  
                        <option class='optionStadia' stadia=100>Завершенные</option>
                        <option class='optionStadia'stadia=102>Возвращенные от ГАИ</option>";
            if (isManagerAuto() || isAdmin()) 
                echo "
                <option class='optionStadia' stadia=2>Первичный осмотр</option>
                <option class='optionStadia' stadia=3>Разрешение ГАИ</option>
                <option class='optionStadia' stadia=5>Вторичный осмотр</option>
                <option class='optionStadia' stadia=6>Сдача документов</option>
                <option class='optionStadia' stadia=7>Получение документов</option>
                <option class='optionStadia'stadia=101>Возвращенные</option>";
                echo "
            </select> 
        </div>
    </header>
    <div class='body' id='body'>";
                $Sql="SELECT *,auto.id,model,auto.number,stadia.item,stadia.rusitem 
                FROM auto LEFT JOIN client on client.id=auto.clientId
                LEFT JOIN stadia on auto.stadia=stadia.number
                LEFT JOIN doc on auto.id=doc.autoId
                ORDER BY client.name";
                $result=mysql_query($Sql,$conn);
                $temp="<table border='black' class='tabo'>
                        <tr>
                            <th>Модель</th><th>Гос. номер </th> <th>Владелец</th> <th>Стадия </th> <th></th>
                        </tr>";
                        
                while ($row=mysql_fetch_array($result))
                {
                    $item=explode(",",$row["item"]);
                    $rusitem=explode(",",$row["rusitem"]);
                    if ($row["stadia"] == 100) {
                        $none = 'display:none;';
                        $class = 'noAll';
                    } else {
                        $none = '';
                        $class = '';
                    }
                    if (isAdmin() || isManagerDoc())
                        $more = "<div auto=".$row['id']." type='auto' class='iconFull btn icon flleft' data-title='Подробнее'> 
                        <span class=' glyphicon glyphicon-hand-right'data-title='Подробнее'></span></div>";
                    else $more="";
                    if (isManagerStadia($row["stadia"]))
                        $temp.="<tr class='$class disp stadia".$row["stadia"]."' style='$none'>
                    <td>$more ".$row["model"]."<td>".$row["number"]."</td><td>".$row["name"]."</td><td><button class='stadia btn whiteback' autoId='".$row["autoId"]."' stadia='".$row["stadia"]."' >".$row["nameStadia"]."</button></td>
                    <td><form action='./xlsx/index.php' method='POST'><button class='xlsx btn whiteback' auto='".$row["id"]."' >
                    <input name='auto' value='".$row['id']."' style='display:none;'>P
                    </button></form></td>
                    </tr>";
                }
                $temp.="</table>";
                echo $temp;
                
   echo" </div>
<script src='js/jQuery/jQuery.js'></script>
<script src='js/script.js'></script>
<footer></footer>
</body>
</html>";    
}