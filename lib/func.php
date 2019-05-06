<?php

function getAutoById($id) {
    global $conn;
    if (!$id) {
        echo "Отсутсвует id";
        exit;
    }
    $Sql = "SELECT * FROM auto WHERE id=$id";
    $result=mysql_query($Sql,$conn); 
    $row = mysql_fetch_assoc($result); 	
    return $row;
}

function getClientById($id) {
    global $conn;
    if (!$id) {
        echo "Отсутсвует id";
        exit;
    }
    $Sql = "SELECT * FROM client WHERE id=$id";
    $result=mysql_query($Sql,$conn); 
    $row = mysql_fetch_assoc($result); 	
    return $row;
}

function form()   //Напечатать форму для входа
{
    $temp=" <table class='center'><tr><td width='30%'></td><td><form name='form1' action='index.php'  method='POST'>
                <div class='input'> <input name='login' type='text' placeholder='Введите логин' ></div>
                <div class='input'> <input type='password' name='password' placeholder='Введите пароль' > </div>
                <div class='submit'><input type='submit' name='log_in' value='Войти'> </div>
                <div class='submit'> 
                    <form name='nologin' action='nologin.php' method='POST'>
                        <input type='submit' form='nologin' name='otprav' value='Забыли пароль?'> </div> 
                    </form>   
                </div>  
            </form></td><td width='30%'></td></tr></table>";
    echo $temp;
}

function out ()  //Выход
{
    unset($_SESSION["ID"]);
    unset($_SESSION["login"]);
    
}

function login () // Проверка входа
{
    if (isset($_SESSION["ID"]))
    {
        return true;
    }
    return false;
}

function enter () //Авторизация
{           
    global $conn;
    if (($_POST["login"])&&($_POST["password"]))
    {
        
        $login=$_POST["login"];
        $password=$_POST["password"];
        $Sql="SELECT * FROM users WHERE login='".$login."'";
        $result=mysql_query($Sql,$conn);
        if (mysql_num_rows($result) == 1) 
        { 			
            $row = mysql_fetch_assoc($result); 			
            if (hash('md5',$password) == $row['hash']) 
            { 
                //setcookie ("login", $row['login'], time() + 50000); 						
                //setcookie ("password",$row['password'], time() + 50000); 					
                $_SESSION['ID'] = $row['id'];	 
                if ($row["login"] == "admin")
                    $_SESSION["login"] = $_POST["login"];	
                else if ($row["login"] == "managerDoc")
                    $_SESSION["login"] = 1;
                else if ($row["login"] == "managerAuto")
                    $_SESSION["login"] = 2;
                $_SESSION["name"] = $row["login"];
                return true; 	
            }		
        }
        else 
        {
            return false;
        } 		
    }
    else 
    {
        return false;
    }
}


function clientNameId()   //Получить двумерный массив с Id и именами клиентов
{
    global $conn;
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
    return $arr;
}

function isAdmin()  //Проверка на админа
{
    if ($_SESSION["login"] == "admin")
    {
        return true;
    }
    return false;
}

function isManagerDoc()   //...
{
    if ($_SESSION["login"] === 1)
    {
        return true;
    }
    return false;
}

function isManagerAuto() //...
{
    if ($_SESSION["login"] === 2)
        return true;
    return false;
}

function ifUser() {
    if (!isManagerAuto() && !isManagerDoc() && !isAdmin())
        return false;
    return true;
}

function isManagerStadia($stadia)   //Проверка соответствия стадии менеджеру
{
    $stadia = (int) $stadia;
    if (isAdmin()) return true;
    if (isManagerDoc()) 
        if ($stadia === 1 || $stadia === 4 || $stadia === 100 || $stadia === 102) return true;
    if (isManagerAuto())
        if ($stadia === 2 || $stadia === 3 || $stadia === 5 || $stadia === 6 || $stadia === 7 || $stadia === 101) return true;
    return false;
}

function headerBtn()
{
    if (!ifUser())
        return false;
    $temp = "
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
    </form>";
    return $temp;
}

function dataAuto($readonly, $arr = array()) {
    $row = array(
        "model" => "",
        "odvig" => "",
        "ndvig" => "",
        "color" => "",
        "typeAuto" => "",
        "number" => "",
        "year" => "",
        "vin" => "",
        "kuzov" => "",
        "shassi" => "",
        "dvig" => "",
        "pdvig" => "",
        "maxmass" => "",
        "massa" => "",
        "pts" => "",
        "sts" => "",
        "change" => "",
        "comment" => ""
    );
    if (count($arr) > 0)
        foreach ($row as $key=>$value) {
            $row[$key] = $arr[$key];
        }
    echo "<div>Модель авто: <input id='model' class='input' name='model' type='text' value='".$row['model']."' $readonly> </div>
    <div>Тип, категория: <input id='typeAuto' class='input' name='typeAuto' type='text' value='".$row['typeAuto']."' $readonly> </div>
    <div>Гос. номер: <input id='number' class='input' name='number' type='text' value='".$row['number']."' $readonly> </div>
    <div>Год выпуска: <input class='input' name='year' type='text' value='".$row['year']."' $readonly> </div>
    <div>VIN авто: <input id='vin'  class='input' name='vin' type='text' value='".$row['vin']."' $readonly> </div>
    <div>Объём двигателя: <input id='odvig'  class='input' name='odvig' type='text' value='".$row['odvig']."' $readonly> </div>
    <div>Номер двигателя: <input id='ndvig'  class='input' name='ndvig' type='text' value='".$row['ndvig']."' $readonly> </div>
    <div>Цвет авто: <input id='color'  class='input' name='color' type='text' value='".$row['color']."' $readonly> </div>
    <div>Номер кузов: <input class='input' name='kuzov' type='text' value='".$row['kuzov']."' $readonly> </div>
    <div>Номер шасси: <input class='input' name='shassi' type='text' value='".$row['shassi']."' $readonly> </div>
    <div>Модель двигателя: <input class='input' name='dvig' type='text' value='".$row['dvig']."' $readonly> </div>
    <div>Мощность двигателя: <input class='input' name='pdvig' type='text' value='".$row['pdvig']."' $readonly> </div>
    <div>Максимальная масса: <input class='input' name='maxmass' type='text' value='".$row['maxmass']."' $readonly> </div>
    <div>Масса: <input class='input' name='massa' type='text' value='".$row['massa']."' $readonly> </div>
    <div>ПТС: <input class='input' name='pts' type='text' value='".$row['pts']."' $readonly> </div>
    <div>СТС: <input class='input' name='sts' type='text' value='".$row['sts']."' $readonly> </div>
    <div>Вносимые изменения: <input class='input' name='change' type='text' value='".$row['change']."' $readonly> </div>
    <div>Коментарий: <input class='input' name='comment' type='text' value='".$row['comment']."' $readonly> </div>";
}

function dataClient($readonly, $arr = array()) {
    $row = array(
        "name" => "",
        "birth" => "",
        "typeClient" => "",
        "phone" => "",
        "seria" => "",
        "nomer" => "",
        "pasDate" => "",
        "from" => "",
        "vidan" => ""
    );
    if (count($arr) > 0)
        foreach ($row as $key=>$value) {
            $row[$key] = $arr[$key];
        }
    echo "<div>ФИО клиента: <input class='input' name='name' type='text' value='".$row['name']."' $readonly></div>
    <div>Дата рождения: <input class='input' name='birth' type='date' value='".$row['birth']."' $readonly></div> ";
    if ($row["type"]) echo "<div><input class='input' name='typeClient' type='radio' value=0  ".$row['type']."  $readonly>Физ. лицо <input class='input' name='typeClient' type='radio' value=1 ".$row['type']." $readonly checked>Юр. лицо </div>";
    else echo "<div><input class='input' name='typeClient' type='radio' value=0  ".$row['type']."   checked $readonly>Физ. лицо <input class='input' name='typeClient' type='radio' value=1  $readonly>Юр. лицо </div>";
    echo "<div>Телефон: <input class='input' name='phone' type='text'  value='".$row['phone']."' $readonly> </div>
    <div>Серия паспорта: <input class='input' name='seria' type='text' value='".$row['seria']."' $readonly> </div>
    <div>Номер паспорта: <input class='input' name='nomer' type='text' value='".$row['nomer']."' $readonly> </div>
    <div>Дата выдачи паспорта: <input class='input' name='pasDate' type='date' value='".$row['pasDate']."' $readonly> </div>
    <div>Выдан: <input class='input' name='vidan' type='text' value='".$row['vidan']."' $readonly> </div>
    <div>Адрес регистрации:<input class='input' name='from' type='text' value='".$row['from']."' $readonly> </div>";
}
?>