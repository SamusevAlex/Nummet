<?php

require_once '../../../Source/SQL/SqlConnectNumMet.php';
require_once '../../../Source/Solvers/SecondTaskSolver.php';
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
$rezult = $connect->getTaskData("2", $connect->getUserId($login, "users"), "tasktable");
$rezult = unserialize($rezult);
$solverObj = new SecondTaskSolver($rezult["yData"]);
$solverObj->solver();
$func = $solverObj->getFuncDepend($solverObj->getEmpFormula(), $solverObj->getAlfa(), $solverObj->getBeta());
$func2 = $solverObj->getFuncDepend($solverObj->getEmpFormula2(), $solverObj->getAlfa2(), $solverObj->getBeta2());
for ($x = 1; $x <= 5.05001; $x += 0.05) {
    $calcFunc = preg_replace("/unk/", "$x", $func);
    $calcFunc2 = preg_replace("/unk/", "$x", $func2);
    eval("\$y = $calcFunc; \$yAr[] = \$y;");
    eval("\$y2 = $calcFunc2; \$yAr2[] = \$y2;");
    $data[] = array('', $x, $y, $y2, $rezult["yData"][$i]); //округление
}
$plot = new PHPlot(805, 530);
$plot->SetImageBorderType('plain');

$plot->SetPlotType('lines');
$plot->SetDataType('data-data');
$plot->SetDataValues($data);

# Main plot title:
$plot->SetTitle('');

# Make a legend for the 2 functions:
$plot->SetLegend(array(preg_replace("/unk/u", "x", $func), preg_replace("/unk/u", "x", $func2)));

# Select a plot area and force ticks to nice values:
$min1 = min($yAr);
$min2 = min($yAr2);
$max1 = max($yAr);
$max2 = max($yAr2);
$plot->SetPlotAreaWorld(1, min($min1, $min2), 5.7, max($max1, $max2));

# Even though the data labels are empty, with numeric formatting they
# will be output as zeros unless we turn them off:
$plot->SetXDataLabelPos('none');

$plot->SetXTickIncrement(1 / 4);
$plot->SetXLabelType('data');
$plot->SetPrecisionX(2);

$plot->SetYTickIncrement((max($yAr) - min($yAr)) / 10);
$plot->SetYLabelType('data');
$plot->SetPrecisionY(4);
$plot->SetXLabel("x");
$plot->SetYLabel("y");
$plot->SetLegendPosition(0, 0, 'title', 0, 0);
# Draw both grids:
$plot->SetDrawXGrid(True);
$plot->SetDrawYGrid(True);

$plot->DrawGraph();
