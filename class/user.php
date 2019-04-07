<?php

class User {
    private $login = "";
    private $password = "";

    public function __construct($login, $password) {
        $this->login = $login;
        $this->password = $password;
    }

    public function auth() {
        global $conn;
        if ((!$this->login) || (!$this->password)) return false;
        $Sql="SELECT * FROM users WHERE login='".$this->login."'";
        $result=mysql_query($Sql,$conn);
        if (mysql_num_rows($result) !== 1) return false;
        $row = mysql_fetch_assoc($result); 			
        if (hash('md5',$this->password) !== $row['hash']) return false;			
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

    public function out() {
        unset($_SESSION["ID"]);
        unset($_SESSION["login"]);
    }
}

?>