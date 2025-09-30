<?php include "../../Classes/DBM.php";
$dbm = new DBM();
$anno = $_REQUEST["year"];
$estr = $_REQUEST["estr"];
$result["estr"]["rows"] = array();
$result["dist"]["rows"] = array();
$output = json_decode($dbm->read("SELECT valori FROM year$anno where estrazione = $estr")[0]["valori"], true);
$dbm = NULL;
$i = 0;
while ($valori = current($output)) {
    $tmpEstr["id"] = ++$i;
    $tmpEstr["data"] = array();
    $tmpEstr["data"][] = key($output);
    $tmpDist["id"] = $i;
    $tmpDist["data"] = array();
    $tmpDist["data"][] = key($output);
    $tmp = explode(".", $valori);
    for ($aux = 0; $aux < 5; $aux++) {
        $tmpEstr["data"][] = $tmp[$aux];
        for ($aux1 = $aux + 1; $aux1 < 5; $aux1++)
            $tmpDist["data"][] = getDistance($tmp[$aux], $tmp[$aux1]);
    }
    $result["estr"]["rows"][] = $tmpEstr;
    $result["dist"]["rows"][] = $tmpDist;
    next($output);
}
echo json_encode($result);
function getDistance($x, $y)
{
    if ($x > $y)
        $tmp = $x - $y;
    else
        $tmp = $y - $x;
    if ($tmp > 45)
        $aux = 90 - intval($tmp);
    else
        $aux = $tmp;
    if ($aux == 3)
        return "<div style=\"background:#00b33c; text-shadow: 1px 1px #888;font-size:16px;color:white;\">3</div>";
    else
        return $aux;
} ?>