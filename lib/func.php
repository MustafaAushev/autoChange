<?php
function form()
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

function out ()
{
    unset($_SESSION["ID"]);
    unset($_SESSION["login"]);
    
}

function login ()
{
    if (isset($_SESSION["ID"]))
    {
        return true;
    }
    return false;
}

function enter ()
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
                $_SESSION["login"] = $_POST["login"];	
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
?>