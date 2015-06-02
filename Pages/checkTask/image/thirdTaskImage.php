<?php

require_once '../../../Source/SQL/SqlConnectNumMet.php';
require_once 'phplot.php';
require_once '../../../Source/Solvers/ThirdTaskSolver.php';
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
$datatable = $connect->getTaskData("3", $connect->getUserId($login, "users"), "tasktable");
$datatable = unserialize($datatable);
$data["yData"] = $datatable['yData'];
$data["h"] = $datatable['h'];
$data["xStart"] = $datatable['xStart'];
$data["xEnd"] = $datatable['xEnd'];
$data["GoalPoints"] = array();
for ($index = $data["xStart"] + $data["h"] / 10; $index < $data["xEnd"]; $index+=$data["h"] / 10) {
    $data["GoalPoints"][] = $index;
}
$serData = serialize($data);
$solver = new ThirdTaskSolver($data["h"], $data["xStart"], $data["xEnd"], $data["yData"], $data["GoalPoints"]);
$solver->solver();
$solve = $solver->getRezult();
$data = array();
foreach ($solve as $x => $y) {
    $data[] = array('', $x, $y);
}
$plot = new PHPlot(805, 530);
$plot->SetImageBorderType('plain');

$plot->SetPlotType('lines');
$plot->SetDataType('data-data');
$plot->SetDataValues($data);
$plot->SetLegend('function by splines');
$plot->SetTitle('');
$plot->SetXLabel("x");
$plot->SetYLabel("y");
$plot->SetLegendPosition(0, 0, 'title', 0, 0);
$plot->SetXTickIncrement(0.01);
$plot->SetPlotAreaWorld($datatable['xStart'], min($solve), $datatable['xEnd'], max($solve));

$plot->DrawGraph();

