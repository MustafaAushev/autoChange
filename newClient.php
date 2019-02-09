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
    <script src='js/script.js'></script>
</head>";
if (($_SESSION['login']!="manager1")&&($_SESSION["login"]!="admin"))
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
<div class='body'>
<form id='newClientData'>
    <div>ФИО клиента: <input class='input' name='name' type='text'></div>
    <div><input class='input' name='tpe' type='radio' value='Физ. лицо'>Физ. лицо <input class='input' name='tpe' type='radio' value='Юр. лицо'>Юр. лицо </div>
    <div>Телефон: <input class='input' name='phone' type='text'> </div>
    <div>Серия номер паспорта и кем выдан/инн юр.лица: <input class='input' name='key' type='text'> </div>
    <div>Адрес регистрации:<input class='input' name='from' type='text'> </div>
    <div class='btn info' id='saveNewClient'>Сохранить</div>
</form>
</div>
<footer>
</footer>
<script src='js/jQuery/jQuery.js'></script>
<script src='js/script.js'></script>
</body>
</html>";




?>