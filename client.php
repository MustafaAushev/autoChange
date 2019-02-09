<?php
session_start();
require_once("./db/db.php");
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

if (($_SESSION["login"]!="admin")&&($_SESSION["login"]!="manager1"))
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
}
else {
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
        <form class='form-selector' action='./newClient.php'>
        <button class='btn btn-default newClient'>Добавить нового клиента</button> 
        </form>";

$Sql="SELECT name,id FROM client ";
$result=mysql_query($Sql,$conn);
$arr=array(
    "name"=>array(),
    "id"=>array()
);
if (!$result)
{
    exit();
}

while ($row=mysql_fetch_array($result))
{
    array_push($arr["name"],$row["name"]);
    array_push($arr["id"],$row["id"]);
}
echo " <div class='flright'>
        <div class='btn seacrh' >
        <select class='form-selector btn btn-default flright'>
        <option class='clientFilter' client='all'>Все</option>";
foreach($arr["name"] as $key => $value)
{
 echo "<option class='clientFilter' client='".$arr["id"][$key]."'>$value</option>";
}
 echo " </select>
        </div>
    </div>
    
</header>
<div class='body' id='body'>";
    if (true)
    {
        
        $flag=true;
        $Sql="SELECT stadia.nameStadia,client.id cid,name,client.type tip,phone,model,stadia,auto.id FROM client
                LEFT JOIN auto 
                on client.id=auto.clientId
                LEFT JOIN stadia
                on stadia.number=auto.stadia  order by client.name";
        $result=mysql_query($Sql,$conn);
        $temp= "<table border='black' class='tabo'>
            <tr>
                <th>ФИО </th><th>Тип</th><th>Адрес</th> <th>ТЕЛЕФОН</th> <th>АВТОМОБИЛИ</th> 
            </tr><tr>";
        while ($row=mysql_fetch_array($result))
        {
            // if ($local==$row["name"])
            // {
            //     continue;
            // }
            // $local=$row["name"];
            $temp.="
                    <tr class='name".$row["cid"]."'>
                        <td>".$row["name"]."</td><td>".$row["tip"]."</td>
                        <td>".$row["reg"]."</td><td>".$row["phone"]."</td><td>".$row["model"]."</td>
                        <td>";
                        if ($row["nameStadia"])
                        {
                            $temp.="<div class='stadia btn whiteback' autoId='".$row["id"]."' stadia='".$row["stadia"]."' >".$row["nameStadia"]."</div></td>
                            </tr>";
                        }
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