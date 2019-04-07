<?php
session_start();
require_once("lib/func.php");
require_once("db/db.php");
require_once("class/user.php");
if(isset($_GET['action'])) out();
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
            <?php echo headerBtn(); ?>
    </header>
    <div class='body' id='body'>
        <?php
        $user = new User($_POST["login"], $_POST["password"]);
        $ret="<table class='center'><tr><td ><div class='bigtext centertext'>".
        $_SESSION['name'].", Вы авторизованы!<div><form  class='form-selector' action='index.php' method='GET'>
        <div><input type='submit' name='action' value='Выход'> </div> 
        </form>
        </div>
        </div></td></tr></table>";
        if(isset($_GET['action'])) echo form();
        else if (login()) echo $ret;
        else if(isset($_POST['login'])) {
                if ($user->auth()) 
                    echo "<table class='center'><tr><td ><div class='bigtext centertext'>".
                    $_SESSION['login'].", Вы авторизованы!<div><form  class='form-selector' action='index.php' method='GET'>
                    <div><input type='submit' name='action' value='Выход'> </div> 
                    </form>
                    </div>
                    </div></td></tr></table>";
                else {
                    echo "<div class='info'>Логин или пароль не верны</div>";
                    echo form();
                }
            } else  echo form();
        ?>
    </div>
<script src="js/jQuery/jQuery.js"></script>
<script src="js/script.js"></script>
<footer></footer>
</body>
</html>