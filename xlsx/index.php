<?php
require_once("../class/PHPExcel.php");
require_once("../db/db.php");
require_once("../lib/func.php");



function excelExport($auto, $client) {
    $excelObj = PHPExcel_IOFactory::load('./shablon.xlsx');
    $excelObj->setActiveSheetIndex(0);
    $excelList = $excelObj->getActiveSheet();
    $index = array(
        "B4" => $auto["number"],
        "B5" => $auto["vin"],
        "B6" => $auto["model"],
        "B9" => $auto["year"],
        "B10" => $auto["shassi"],
        "B11" => $auto["kuzov"],
        "B12" => $auto["color"],
        "B13" => $auto["pdvig"],
        "B16" => $auto["pts"],
        "B17" => $auto["maxmass"],
        "B18" => $auto["massa"],
        "B19" => $client["name"],
        "B20" => $client["from"],
        "B21" => $auto["sts"],
        "B24" => $auto["dvig"],
        "B25" => $auto["ndvig"],
        "B26" => $auto["odvig"],
        "B34" => $client["birth"],
        "B35" => $auto["seria"],
        "B36" => $auto["nomer"],
        "B37" => $auto["pasDate"],
        "B38" => $auto["vidan"]
    );
    foreach ($index as $key => $value)
        $excelList->setCellValue($key, $value);
    header('Content-Type: application/vnd.ms-excel');
    $filename = "Reports".date("d-m-Y-His").".xlsx";
    header('Content-Disposition: attachment;filename='.$filename .' ');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($excelObj, 'Excel2007');
    $objWriter->save('php://output');
}

$auto = getAutoById($_POST["auto"]);
$client = getClientById($auto["clientId"]);
excelExport($auto, $client);

?>