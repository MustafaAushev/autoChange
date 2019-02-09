<?php

session_start();

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

if (($_SESSION["login"]!="admin")&&($_SESSION["login"]!="manager1")&&($_SESSION["login"]!="manager2"))
{
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
}
else
{
echo "


<body>
    <header>
        <div class='caption'>
            <form class='form-selector' action='http://test1.ru'>
                <button class='btn btn-default'>На главную</button>
            </form>";
            if (($_SESSION["login"]=="manager1")||($_SESSION["login"]=="admin"))
            {
                echo "<form class='form-selector' action='http://test1.ru/client.php'>
                    <button class='btn btn-default'>Клиенты</button>
                    </form>";
            }
            if (($_SESSION["login"]=="manager2")||($_SESSION["login"]=="admin")||($_SESSION["login"]=="manager1"))
            {
                echo "<form  class='form-selector' action='http://test1.ru/auto.php'>
                    <button class='btn btn-default'>Автомобили</button>  
                </form>";
            }
             echo "
             <form action='./newAuto.php' class='form-selector'>
            <button class='btn btn-default newAuto' >Добавить новый автомобиль</button>
            </form>
            <select class='form-selector btn btn-default flright'>
                <option class='optionStadia' stadia='all'>Все стадии</option>
                <option class='optionStadia' stadia=1>Получение ПЗ</option>
                <option class='optionStadia' stadia=2>Первичный осмотр</option>
                <option class='optionStadia' stadia=3>Разрешение ГАИ</option>
                <option class='optionStadia' stadia=4>Получение ПР</option>
                <option class='optionStadia' stadia=5>Вторичный осмотр</option>
                <option class='optionStadia' stadia=6>Сдача документов</option>
                <option class='optionStadia' stadia=7>Получение документов</option>
                <option class='optionStadia' stadia=100>Завершенные</option>
                <option class='optionStadia'stadia=101>Возвращенные</option>
                <option class='optionStadia'stadia=102>Возвращенные от ГАИ</option>
            </select> 
        </div>
    </header>
    <div class='body' id='body'>";
                
                require_once("./db/db.php");
                global $conn;
                $Sql="SELECT *,auto.id,model,auto.number,stadia.item,stadia.rusitem 
                FROM auto LEFT JOIN client on client.id=auto.clientId
                LEFT JOIN stadia on auto.stadia=stadia.number
                LEFT JOIN doc on auto.id=doc.autoId
                ORDER BY client.name";
                $result=mysql_query($Sql,$conn);
                $temp="<table border='black' class='tabo'>
                        <tr>
                            <th>Модель</th><th>Гос. номер </th> <th>Владелец</th> <th>Стадия </th> 
                        </tr>";
                while ($row=mysql_fetch_array($result))
                {
                    $item=explode(",",$row["item"]);
                    $rusitem=explode(",",$row["rusitem"]);
                    if ($row["stadia"] == 100) 
                    {
                        $none = 'display:none;';
                        $class = 'noAll';
                    } else
                    {
                        $none = '';
                        $class = '';
                    }
                    $temp.="<tr class='$class disp stadia".$row["stadia"]."' style='$none'><td>".$row["model"]."<div class='btn btn-sm info flright' car='".$row["autoId"]."' stadia='".$row["stadia"]."' id='print'>P</div><td>".$row["number"]."</td><td>".$row["name"]."</td><td><button class='stadia btn whiteback' autoId='".$row["autoId"]."' stadia='".$row["stadia"]."' >".$row["nameStadia"]."</button></td></tr>";
                }
                $temp.="</table>";
                if (true)
                {
                    echo $temp;
                }
                
                
   echo" </div>
<script src='js/jQuery/jQuery.js'></script>
<script src='js/script.js'></script>
<footer></footer>
</body>
</html>";    // Изменения данных по авто и клиенту 
            }//Поле для коментария ,excel