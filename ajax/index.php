<?php
session_start();
require_once("../db/db.php");
require_once("../lib/func.php");
require_once("../xlsx/index.php");
require_once("../class/PHPExcel.php");

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

function sostOfDoc($item,$autoId) {
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

function goStadia($stadia,$autoId)  //В раздел управления стадиями
{
    if (!isManagerStadia($stadia)) 
    {
        echo "<div class='info'>Автомобиль переведен в следущую стадию</div>";
        return;
    } 
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
        if ($_SESSION["login"] == "admin")
        {
            echo "
                    <div><form id='form'><select class='form-selector btn btn-default' name='stadia'>
                    <option class='sel info act' stadia=1>Получение ПЗ</option>
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
        $Sql = "UPDATE doc DET poldoc=0 where autoId=$autoId";
        if (!$arr["date"])
        {
            $Sql="UPDATE doc SET datesdacha='' WHERE autoId=$autoId";
            $result=mysql_query($Sql,$conn);
            if (!$result)
            {
                echo "<div class='info'>Error... Не удалось внести изменения...</div>";
                return false;
            }
            $not="not";
            $action="comeOn";
            $success="";
            $flag=0;
            $temp .= "<form id='dateForm'>
            <input id='inputDate' name='date' class='input' type='date'><div class='info' car='$autoId' id='newDate'> OK</div>
            </form>";

        }
        else
        {
            $flag = 1;
            $not="";
            $action="otkat";
            $success="(ГОТОВО)";
            $temp.="<div id='date' class='document btn info bigtext polovina $not $action' stadia='$stadia' car='$autoId'  value='date' >Дата сдачи ".$arr['datesdacha']."</div><br>";
        }
        if ($flag != 0) $temp.="<div id='poldoc' stadia='$stadia' car='$autoId' class='bigtext btn nextStadiabtn'> Перевести на следующую стадию</div>";
    } else if($stadia == 7)
    {
        if (!$arr["poldoc"])
        {
            $not="not";
            $action="comeOn";
            $success="";
            $flag=0;
        }
        else
        {
            $flag = 1;
            $not="";
            $action="otkat";
            $success="(ГОТОВО)";
        }
        $date = date("d.m.Y", strtotime(date($arr['datesdacha'])) +604800);
        $temp.="<div class='document btn info bigtext polovina $not $action' stadia='$stadia' car='$autoId'  value='poldoc' >Дата получения $date</div><br>";
        if ($flag != 0) $temp.="<div id='nextS' stadia='$stadia' car='$autoId' class='bigtext btn nextStadiabtn'> Перевести на следующую стадию</div>";
    
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
    $temp.="<div class='info btn' id='vozvrat' >Возврат</div>";
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

function nextS($autoId,$stadia)   //На след стадию
{
    global $conn;
    if ( ($stadia == 101) || ($stadia == 102) || ($stadia == 6))
    {
        if ($stadia == 6) $stadia++;
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
    if (($stadia<0)||($stadia>8))
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

function saveClient()  //Сохранить клиента
{
    global $conn;
    $Sql="SELECT * from client where phone='".$_POST["phone"]."'";
    $result=mysql_query($Sql,$conn);
    if (mysql_num_rows($result) > 0) {
        echo "<div class='info'>Этот клиент существует в базе</div>";
        return false;
    }
    $Sql="INSERT into client (`name`, `birth`, `seria`, `pasDate`, `phone`, `typeClient`, `nomer`,`from`, `vidan`) 
    values('".$_POST['name']."', '".$_POST['birth']."','".$_POST['seria']."','".$_POST['pasDate']."','".$_POST['phone']."','".$_POST['typeClient']."','".$_POST['nomer']."','".$_POST['from']."', '".$_POST['vidan']."')";
    $result=mysql_query($Sql,$conn);
    if (!$result) {
        echo "<div class='info'>Не удалось создать пользователя</div>";
        return false;
    }
    echo "<div class='info'>Клиент - ".$_POST["name"]." сохранен в базу данных</div>";
}

function saveAuto()   //Сохранить авто
{
    global $conn;
    $Sql="SELECT id FROM auto where vin='".$_POST["vin"]."'";
    $result=mysql_query($Sql,$conn);
    if (mysql_num_rows($result) > 0) {
        echo "<div class='info'>Авто с таким vin существует в базе</div>";
        return false;
    }
    $keys = array('model', 'typeAuto', 'odvig', 'ndvig', 'color', 'number', 'year', 'vin', 'kuzov', 'shassi', 'dvig', 'pdvig', 'maxmass', 'massa', 'pts', 'sts', 'change', 'clientId', 'stadia');
    $Sql = "INSERT into auto (";
    foreach ($keys as $value) {
        $Sql .= "`$value`";
        if ($value === "stadia") $Sql .= ") ";
        else $Sql .= ",";
    }
    $Sql .= "values(";
    foreach ($keys as $value) {
        if ($value === "clientId") $Sql .= "'".(int) $_POST["client"]."'";
        else if ($value === "stadia") $Sql .= 1;
        else $Sql .= "'$_POST[$value]'";
        if ($value === "stadia") $Sql .= ")";
        else $Sql .= ",";
    }
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
    echo "<div class='info' >Данные о документах авто успешно созданы</div>";
}

function full($id, $type)   //Полная инфа по авто или клиенту
{
    global $conn;
    $Sql = "SELECT * FROM $type WHERE id=$id";
    $result=mysql_query($Sql,$conn);
    if (!$result)
    {
        echo "<div class='info'>Не найден клиент/авто</div>";
        return false;
    }
    echo "<form id='data'>";
    $row=mysql_fetch_array($result);
    if ($type == "auto") {
        $arr = clientNameId();
        echo "Выберите имя клиента:<br>
        <select name='clientId' class=' btn btn-default' disabled>";
        foreach($arr["name"] as $key=>$value)
        {
            if ($row["clientId"] == $arr["id"][$key])
            {
                $selected="selected";
            } else $selected="";
            echo "<option value='".$arr["id"][$key]."' $selected>".$arr["id"][$key].". $value </option>";
        }
        echo "</select>";
        echo "<div typeChange='auto' id='what' style='display:none;'><input name='id' value='".$row['id']."'></div>";
        dataAuto("readonly", $row);
    } else if ($type == "client") {
        echo "
        <div typeChange='client' id='what' style='display:none;'><input name='id' value='".$row['id']."'></div>";
        dataClient("readonly", $row);
        echo "</form>";
    }
    
    echo "<div class='btn info flleft' id='changeData'>Изменить</div>";
}

function changeData() //Изменение данных по авто или по клиенту
{
    if (($_POST["id"] === true) || (($_POST["typeChange"] != "client") && ($_POST["typeChange"] != "auto")))
    {
        return;
    }
    global $conn;
    $arr = array();
    foreach($_POST as $key => $value)
    {
        if (($key != "type") && ($key != "typeChange") && ($key != "id"))
        {
            $arr[$key]=$value;
        }
    }
    $Sql = "UPDATE ".$_POST['typeChange']."  SET ";
    foreach($arr as $key => $value)
    {
        $Sql .= "`$key`='$value', ";
    }
    $Sql .= "`id`='".$_POST['id']."' WHERE id=".$_POST['id'];
    $result=mysql_query($Sql,$conn);
    if (!$result)
    {
        echo "<div class='info'>Не удалось изменить данные</div>";
        return;
    }
    echo "<div class='info'>Данные изменены</div>";
}

function vozvrat($autoId, $stadia)   //Возврат авто в зависимости с какой стадии они пришли
{
    $arrDoc = array(1,4);
    $arrAuto = array(2,3,5,6,7);
    if (in_array($stadia, $arrDoc))
    {
        nextS($autoId, 102);
        return;
    }
    if (in_array($stadia, $arrAuto))
    {
        nextS($autoId, 101);
        return;
    }
}

function saveDate()
{
    if ($_REQUEST["autoId"] !== true)
        $autoId = $_REQUEST["autoId"];
    $date = $_REQUEST["date"];
    global $conn;
    $Sql="UPDATE doc SET datesdacha='$date', date=1 WHERE autoId=$autoId";
    $result=mysql_query($Sql,$conn);
    if (!$result)
    {
        echo "<div class='info'>Error... Не удалось внести изменения при сохранении даты...</div>";
        return false;
    }
    goStadia(6, $autoId);
}

function xlsx($id) {
    $auto = getAutoById($id);
    $client = getClientById($auto["clientId"]);
    excelExport($auto, $client);
    return true;
}
switch ($_REQUEST["type"])
{
    case "goStadia": goStadia($_REQUEST["stadia"],$_REQUEST["autoId"]);break;
    case "comeOn": comeOn($_REQUEST["doc"],$_REQUEST["autoId"],$_REQUEST["stadia"],1); break;
    case "otkat": comeOn($_REQUEST["doc"],$_REQUEST["autoId"],$_REQUEST["stadia"],0); break;
    case "nextS": nextS($_REQUEST["autoId"],$_REQUEST["stadia"]); break;
    case "back": nextS($_REQUEST["autoId"],$_REQUEST["stadia"]-2); break;
    case "arhiv": nextS($_REQUEST["autoId"],8); break;
    case "saveNewClient": saveClient();break;
    case "saveNewAuto": saveAuto(); break;
    case "adminStadia": nextS($_REQUEST["autoId"],$_REQUEST["stadia"]-1); break;
    case "vozvrat": vozvrat($_REQUEST["autoId"],$_REQUEST["stadia"]); break;
    case "full": full($_REQUEST["id"], $_REQUEST["typeChange"]); break;
    case "changeData": changeData(); break;
    case "dateSave": saveDate(); break;
    case "xlsx": xlsx($_POST["auto"]); break;
}

?>