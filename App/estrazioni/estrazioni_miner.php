<?php include "../../Classes/DBM.php";
$dbm = new DBM();
$anno = $_REQUEST["year"];
$sql = "SELECT data, estrazione FROM year$anno order by estrazione desc";
$output = $dbm->read($sql);
$dbm = NULL;
$res = array();
foreach ($output as $key)
    $res[] = sprintf("%02d", $key["estrazione"]) . " - " . $key["data"];
echo json_encode($res); ?>