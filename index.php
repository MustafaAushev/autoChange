<?php
session_start();
require_once("lib/func.php");
require_once("db/db.php");
$flag = enter();
if(isset($_GET['action'] ) )
{
    out();
}
?>
<!Doctype-html>
<html>
    <head>
    <meta charset="UTF-8">
    <title> Главная</title>
    <link rel="stylesheet" href="style/bootstrap.css" type="text/css"/>
    <link rel="stylesheet" href="style/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="style/bootstrap-theme.css" type="text/css"/>
    <link rel="stylesheet" href="style/bootstrap-theme.min.css" type="text/css"/>
    <link rel="stylesheet" href="style/style.css" type="text/css"/>
    <script src="js/script.js"></script>
</head>
<body>
    <header>
        <table  width='100%'>
        <tr>
        <td width='20%'>
            <form class='form-selector' action='http://test1.ru'>
                <button class='btn btn-default'>На главную</button>
            </form>
        </td>
        <td width='40%'>
            <?php
            if (($_SESSION["login"]=="manager1")||($_SESSION["login"]=="admin"))
            {
                echo "<form class='form-selector' action='http://test1.ru/client.php'>
                    <button class='btn btn-default'>Клиенты</button>
                </form>";
            }
            if (($_SESSION["login"]=="manager1")||($_SESSION["login"]=="admin") || ($_SESSION["login"] == "manager2"))
            {
                echo "<form  class='form-selector' action='http://test1.ru/auto.php'>
                    <button class='btn btn-default'>Автомобили</button>  
                </form>";
            }
            ?>
        </td>
        </tr>
        </table>
        
    </header>
    <div class='body' id='body'>
        <?php
        $ret="<table class='center'><tr><td ><div class='bigtext centertext'>".
        $_SESSION['login'].", Вы авторизованы!<div><form  class='form-selector' action='index.php' method='GET'>
        <div><input type='submit' name='action' value='Выход'> </div> 
        </form>
        </div>
        </div></td></tr></table>";
        if(isset($_GET['action'] ) )
        {
            echo form();
        }
        else
        if (login()) 
        {
            echo $ret;
        }
        else 
        {
            if(isset($_POST['login'])) 
            {
                if ($flag) 
                {
                    echo "<table class='center'><tr><td ><div class='bigtext centertext'>".
                    $_SESSION['login'].", Вы авторизованы!<div><form  class='form-selector' action='index.php' method='GET'>
                    <div><input type='submit' name='action' value='Выход'> </div> 
                    </form>
                    </div>
                    </div></td></tr></table>";
                }
                else 
                {
                    echo "<div class='info'>Логин или пароль не верны</div>";
                    echo form();
                }
            }
            else 
            {
                echo form();
            }
        }
        ?>
    </div>
<script src="js/jQuery/jQuery.js"></script>
<script src="js/script.js"></script>
<footer></footer>
</body>
</html>