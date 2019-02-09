<?php
session_start();
require_once("../db/db.php");
// function checkDoc($doc,$stadia)
// {
//     $Sql="SELECT * FROM stadia WHERE number=".$stadia;
//     $result=mysql_query($Sql,$conn);
//     while ($row=mysql_fetch_array($result))
//     {
//         $item=explode(",",$row["item"]);
//     }
    
//     foreach ($item as $value=>$key)
//     {
//         if ($value==$doc)
//         {
//         }
//     }
// }

function docOfStadia($stadia,&$item,&$rusitem,&$nameStadia)    //Достаем документы за указанную стадию
{
    global $conn;
    $Sql="SELECT * FROM stadia WHERE number=".$stadia;
    $result=mysql_query($Sql,$conn);
    while ($row=mysql_fetch_array($result))
    {
        $stringItem=$row["item"];
        $item=explode(",",$row["item"]);
        $rusitem=explode(",",$row["rusitem"]);
        $nameStadia=$row["nameStadia"];
        return true;
    }
    return false;
}

function sostOfDoc($item,$autoId)
{
    global $conn;
    $stringItem=implode(",",$item);
    $Sql="SELECT $stringItem FROM doc where autoId=".$autoId;
    $result=mysql_query($Sql,$conn);
    while ($row=mysql_fetch_array($result))
    {
        $arr=$row;
    }
    return $arr;
}

function goStadia($stadia,$autoId)
{
    global $conn;
    if ((($stadia<1)||($stadia>8))&&(($stadia!=100)&&($stadia!=101)&&($stadia!=102)))
    {
        echo  "<div class='info'>Ошибка в указанной стадии $stadia</div>";
        if ($_SESSION["login"]=="admin")
        {
            echo "<div class='info'>Выберите на какую стадию перевести авто</div>
                    <div><form id='form'><input class='input' name='stadia'></form></div> 
                    <div class='info btn' car='$autoId' id='adminStadia'>Перевести</div> ";
        }
        return;
    }
    if ( ($stadia==100) || ($stadia == 101) || ($stadia == 102) )
    {
        $Sql="SELECT * FROM auto where id=".$autoId;
        $result=mysql_query($Sql,$conn);
        while ($row=mysql_fetch_array($result))
        {
            echo "<div class='info bigtext'>Автомобиль ".$row["model"].". Гос.номер: ".$row["number"]." переведен в архив. Для возобновления работы с авто необходимы права администратора.</div>";
        }
        $Sql="SELECT * FROM stadia where number=$stadia";
        $result=mysql_query($Sql,$conn);
        while ($row=mysql_fetch_array($result))
        {
            echo "<div class='info' > Авто находится в разделе: ".$row["nameStadia"]."</div>";
        }
        if ($_SESSION["login"]=="admin")
        {
            echo "
                    <div><form id='form'><select class='form-selector btn btn-default' name='stadia'>
                    <option class='sel info' stadia=1>Получение ПЗ</option>
                    <option class='sel info' stadia=2>Первичный осмотр</option>
                    <option class='sel info' stadia=3>Разрешение ГАИ</option>
                    <option class='sel info' stadia=4>Получение ПР</option>
                    <option class='sel info' stadia=5>Вторичный осмотр</option>
                    <option class='sel info' stadia=6>Сдача документов</option>
                    <option class='sel info' stadia=7>Получение документов</option>
                </select> </form></div> 
                    <div class='info btn' car='$autoId' id='adminStadia'>Перевести</div> ";
        }
        return true;
    }
    $item;
    $rusitem;
    $nameStadia;
    if (!docOfStadia($stadia,$item,$rusitem,$nameStadia))
    {
        echo "<div class='info>Не удалось получить документы по указанной стадии</div>";
    }
    $arr=sostOfDoc($item,$autoId);
    $Sql="SELECT * FROM auto where id=".$autoId;
    $result=mysql_query($Sql,$conn);
    while ($row=mysql_fetch_array($result))
    {
        
        $temp="<div id='autoInfo' car='$autoId' stadia='$stadia' class='info bigtext'>Автомобиль ".$row["model"].". Гос.номер: ".$row["number"]." на ".$stadia." стадии ($nameStadia).</div>";
    }
    $flag=1;
    $not="";
    $action="comeOn";
    $success="(ГОТОВО)";
    if ($stadia>1)
    {
        $temp.="<div class='info btn backStadiabtn' id='back' car='$autoId' stadia='$stadia' >Вернуть на предыдущую стадию</div><br>";
    }
    if ($stadia==6)
    {
        $temp.="<div class='document btn info bigtext polovina  $action' stadia='$stadia' car='$autoId'  value='$value' >Дата сдачи - ".$arr[0]."</div><br>";
        $temp.="<div id='nextS' stadia='$stadia' car='$autoId' class='bigtext btn nextStadiabtn'> Перевести на следующую стадию</div>";
    }
    else 
    {
        $temp.="<h3> Необходимые действия:</h3>";
        foreach ($item as $key=>$value)
        {
            if (!$arr[$value])
            {
                $not="not";
                $action="comeOn";
                $success="";
                $flag=0;
            }
            else
            {
                $not="";
                $action="otkat";
                $success="(ГОТОВО)";
            }

            $temp.="<div class='document btn info bigtext polovina $not $action' stadia='$stadia' car='$autoId'  value='$value' >".$rusitem[$key]." $success</div><br>";
        }
        if ($flag!=0)
        {
            $temp.="<div id='nextS' stadia='$stadia' car='$autoId' class='bigtext btn nextStadiabtn'> Перевести на следующую стадию</div>";
        }
    }
    $temp.="<br><br>";
    $temp.="<div class='info btn' id='vozvrat' >Возврат авто</div>";
    $temp.="<div class='info btn' id='vozvratGai' >Возврат авто от ГАИ</div>";
    $temp.="<br><div class='info btn' id='print'>Распечатать</div>";
    if ($_SESSION["login"]=="admin")
    {
        $temp.="<br><div class='info not btn' id='arhiv' car='$autoId' stadia='$stadia'>Переместить в архив</div>";
    }
    echo $temp;
}

function comeOn($doc,$autoId,$stadia,$change)
{
    global $conn;
    $Sql="UPDATE doc SET $doc=$change WHERE autoId=$autoId";
    $result=mysql_query($Sql,$conn);
    if (!$result)
    {
        echo "<div class='info'>Error... Не удалось внести изменения...</div>";
        return false;
    }
    goStadia($stadia,$autoId);

}

function nextS($autoId,$stadia)
{
    global $conn;
    if ( ($stadia == 101) || ($stadia == 102) )
    {
        $Sql="UPDATE auto SET stadia=$stadia WHERE id=$autoId";
        $result=mysql_query($Sql,$conn);
        if (!$result)
        {
            echo "<div class='info'>Не удалось изменить стадию ТС</div>";
            return false;
        }
        goStadia($stadia,$autoId);
        return true;
    }
    if (($stadia<1)||($stadia>8))
    {
        echo "Error...Некорректно выбрана стадия";
        return;
    }
    if (($stadia <8)&&($stadia!=5))
    {
        $stadia++;
        $item;
        $rusitem;
        $nameStadia;
        docOfStadia($stadia,$item,$rusitem,$nameStadia);
        $Sql="UPDATE doc SET";
        $counter=0;
        foreach ($item as $value)
        {
            if ($counter)
            {
                $Sql.=",";
            }
            $counter=1;
            $Sql.=" $value=0 ";
        }
        $Sql.=" WHERE autoId=$autoId";
        $result=mysql_query($Sql,$conn);
        if (!$result)
        {
            echo "<div class='info'>Не удалось изменить документы ТС</div>";
            return false;
        }
    }
    else
    if ($stadia==5) 
    {
        $stadia++;
    }else
    if ($stadia == 8)
    {
        $stadia=100;
    }
    $Sql="UPDATE auto SET stadia=$stadia WHERE id=$autoId";
    $result=mysql_query($Sql,$conn);
    if (!$result)
    {
        echo "<div class='info'>Не удалось изменить стадию ТС</div>";
        return false;
    }
    goStadia($stadia,$autoId);
    
}

// function arhiv ($autoId)
// {
//     global $conn;
//     $Sql="UPDATE auto SET stadia=100 where id=$autoId";
//     $result=mysql_query($Sql,$conn);
//     if (!$result)
//     {
//         echo "<div class='info'>Не удалось изменить стадию ТС</div>";
//         return false;
//     }
//     echo "<div class='info'>Автомобиль переведен в архив</div>";
// }

function saveClient()
{
    global $conn;
    $Sql="SELECT * from client where phone='".$_POST["phone"]."'";
    $result=mysql_query($Sql,$conn);
    if (mysql_num_rows($result) > 0)
    {
        echo "<div class='info'>Этот клиент существует в базе</div>";
        return false;
    }
    if ($_POST["tpe"]=="")
    {
        $_POST["tpe"]="Физ. лицо";
    }
    $Sql="INSERT into client (`name`,`phone`,`type`,`key`,`from`) values('".$_POST['name']."','".$_POST['phone']."','".$_POST['tpe']."','".$_POST['key']."','".$_POST['from']."')";
    $result=mysql_query($Sql,$conn);
    if (!$result)
    {
        echo "<div class='info'>Не удалось создать пользователя</div>";
        return false;
    }
    echo "<div class='info'>Клиент - ".$_POST["name"]." сохранен в базу данных</div>";
}

function saveAuto()
{
    global $conn;
    $Sql="SELECT id FROM auto where vin='".$_POST["vin"]."'";
    $result=mysql_query($Sql,$conn);
    while (mysql_num_rows($result) > 0)
    {
        echo "<div class='info'>Авто с таким vin существует в базе</div>";
        return false;
    }
    $int = (int) $_POST["client"];
    $Sql="INSERT into auto (`model`,`type`,`number`,`year`,`vin`,`kuzov`,`shassi`,`dvig`,`pdvig`,`maxmass`,`massa`,`pts`,`sts`,`change`,`clientId`,`stadia`) values('".$_POST['model']."','".$_POST['typeauto']."','".$_POST['number']."','".$_POST['year']."','".$_POST['vin']."','".$_POST['kuzov']."','".$_POST['shassi']."','".$_POST['dvig']."','".$_POST['pdvig']."','".$_POST['maxmass']."','".$_POST['massa']."','".$_POST['pts']."','".$_POST['sts']."','".$_POST['change']."','$int',1)";
    $result=mysql_query($Sql,$conn);
    if (!$result)
    {
        echo "<div class='info'>Не удалось создать авто</div>";
        return false;
    }
    echo "<div class='info'>Авто - ".$_POST["model"]." сохранено в базу данных</div>";
    $Sql="SELECT id FROM auto where vin='"
    .$_POST['vin']."'";
    $result=mysql_query($Sql,$conn);
    while ($row=mysql_fetch_array($result))
    {
        $id = $row["id"];
    }
    $Sql="INSERT into doc (`autoId`,`datesdacha`) values('"
    .$id."','".$_POST['date']."')";
    $result=mysql_query($Sql,$conn);
    if (!$result)
    {
        echo "Error";
        return false;
    }
    echo "<div class='info' >SUCCESS</div>";
}
switch ($_REQUEST["type"])
{
    //case "checkDoc": checkDoc($_REQUEST["doc"],$_REQUEST["stadia"]); break;
    case "goStadia": goStadia($_REQUEST["stadia"],$_REQUEST["autoId"]);break;
    case "comeOn": comeOn($_REQUEST["doc"],$_REQUEST["autoId"],$_REQUEST["stadia"],1); break;
    case "otkat": comeOn($_REQUEST["doc"],$_REQUEST["autoId"],$_REQUEST["stadia"],0); break;
    case "nextS": nextS($_REQUEST["autoId"],$_REQUEST["stadia"]); break;
    case "back": nextS($_REQUEST["autoId"],$_REQUEST["stadia"]-2); break;
    case "arhiv": nextS($_REQUEST["autoId"],8); break;
    case "saveNewClient": saveClient();break;
    case "saveNewAuto": saveAuto(); break;
    case "adminStadia": nextS($_REQUEST["autoId"],$_REQUEST["stadia"]-1); break;
    case "vozvrat": nextS($_REQUEST["autoId"],101); break;
    case "vozvratGai": nextS($_REQUEST["autoId"],102); break;
}

?>