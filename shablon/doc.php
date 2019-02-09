<?php
require_once("../db/db.php");

if ($_REQUEST["type"] == "print")
{
    if ($_REQUEST["autoId"] !== true){
        $car=$_REQUEST["autoId"];
    }
    $Sql="SELECT * FROM auto LEFT JOIN client on client.id=auto.clientId WHERE auto.id=$car";
    
    $result=mysql_query($Sql,$conn);
    if (mysql_num_rows($result) != 1)
    {
        echo 'Error...';
        return;
    }
    
    while ($row=mysql_fetch_array($result))
    {
        //$name=$row["name"];
        $model = $row["model"];
        $number = $row["number"];
    }
    $shablon = new ZipArchive();
    $zip = new ZipArchive();
    $res = $zip->open('C:/Users/Mustafa/Desktop/doc.docx', ZipArchive::CREATE);
    if ($shablon->open('./shablon.docx') === true) 
    {	
        $res = $shablon;
        $xml = $shablon->getFromName('word/document.xml');
        print_r($model);
        $xmlNew = str_replace('{model}', $model, $xml);
        print_r($xmlNew);
        $res->addFromString('word/document.xml', $xmlNew);	
        
        $shablon->close();
        //$res->close();
    }
    else
    {
        echo "Error... ";
    }
}
else
{
    print_r("err");
}

?>