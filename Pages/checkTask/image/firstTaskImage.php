<?php

require_once '../../../Source/SQL/SqlConnectNumMet.php';
require_once 'phplot.php';
mb_internal_encoding("UTF-8");
define(HOST, "localhost");
define(USER, "root");
define(PASSWORD, "");
define(DB, "NumMet");
$connect = new SqlConnectNumMet(HOST, USER, PASSWORD, DB);
if ($connect->checkUserLogin($_COOKIE['login'], $_COOKIE['password'], "users")) {
    $isLogin = true;
} else {
    $isLogin = false;
}
$login = $_COOKIE["login"];
if (!$isLogin) {
    header("Location:../login.php");
}
$rezult = $connect->getTaskRezult("1", $connect->getUserId($login, "users"), "taskdone");
$rezult = unserialize($rezult);
$rezult = $rezult[2];
$datatable = $connect->getTaskData("1", $connect->getUserId($login, "users"), "tasktable");
$datatable = unserialize($datatable);
$datatable = $datatable['xyData'];
$xData = array_keys($datatable);
$xDataCalc = array_keys($rezult);
$yData = array_values($datatable);
$yDataCalc = array_values($rezult);
for ($index = 0; $index < count($datatable); $index++) {
    $data[] = array('', $xData[$index], $yData[$index]);
}
//var_dump($data);
$plot = new PHPlot(805, 530);
$plot->SetImageBorderType('plain');

$plot->SetPlotType('lines');
$plot->SetDataType('data-data');
$plot->SetDataValues($data);
$plot->SetLegend('function by point');
# Main plot title:
$plot->SetTitle('');
$plot->SetXLabel("x");
$plot->SetYLabel("y");
$plot->SetLegendPosition(0, 0, 'title', 0, 0);
# Make sure Y axis starts at 0:
$min1 = min($xData);
$min2 = min($xDataCalc);
$min3 = min($yData);
$min4 = min($yDataCalc);
$max1 = max($xData);
$max2 = max($xDataCalc);
$max3 = max($yData);
$max4 = max($yDataCalc);
$plot->SetPlotAreaWorld(min($min1, $min2) - min($min1, $min2) / 100, min($min3, $min4) - min($min3, $min4) / 100, max($max1, $max2) + max($max1, $max2) / 50, max($max3, $max4) + max($max3, $max4) / 50);

$plot->DrawGraph();
