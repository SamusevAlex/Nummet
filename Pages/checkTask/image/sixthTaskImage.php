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
$dataStudent = $connect->getTaskRezult(6, $connect->getUserId($login, "users"), "taskdone");
$dataStudent = unserialize($dataStudent);
$yRK = $dataStudent["yRK"];
$Miln = $dataStudent["Miln"];
$data = $connect->getTaskData(6, $connect->getUserId($login, "users"), "tasktable");
$data = unserialize($data);
$y0 = $data["y0"];
$h = $data["h"];
$x0 = $data["x0"];
$endValue = $data["EndValue"];
$data = array();
$data[] = array('', $x0, $y0);
for ($index = $x0 + $h, $j = 0; $index < $endValue + $h / 1000; $index+=$h, $j++) {
    $data[] = array('', $index, $Miln[$j]);
}
//var_dump($data);
$plot = new PHPlot(805, 530);
$plot->SetImageBorderType('plain');

$plot->SetPlotType('lines');
$plot->SetDataType('data-data');
$plot->SetDataValues($data);
$yRK[] = $y0;
# Main plot title:
$plot->SetTitle('');
$plot->SetXLabel("x");
$plot->SetYLabel("y");
# Make a legend for the 2 functions:
# Select a plot area and force ticks to nice values:
$plot->SetPlotAreaWorld($x0, min($yRK), $endValue + $h, max($yRK));

# Even though the data labels are empty, with numeric formatting they
# will be output as zeros unless we turn them off:
$plot->SetXDataLabelPos('none');
$plot->SetLegend("Miln method");
$plot->SetXTickIncrement($h);
$plot->SetXLabelType('data');
$plot->SetPrecisionX(2);

$plot->SetYTickIncrement((max($yRK)-  min($yRK))*$h/3);
$plot->SetYLabelType('data');
$plot->SetPrecisionY(4);
$plot->SetLegendPosition(0, 0, 'title', 0, 0);
# Draw both grids:
$plot->SetDrawXGrid(True);
$plot->SetDrawYGrid(True);

$plot->DrawGraph();
