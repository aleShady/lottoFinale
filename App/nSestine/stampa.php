<?php

        include '../../Classes/DBM.php';
  $db = new DBM();
         $model = json_decode($_REQUEST['model']);



  $con = $db->read ( "SELECT * FROM nSestine" );
if (count($con) == 0) {
  $queryResult['Errore'] = "Nessun dato presente in tabella";
}

$sql="SELECT anno, sestina, dir, pagina, nSestina, tripla, terno, quaterna FROM nSestine where anno = '$model->currentYear' and (terno != '' or quaterna!='') order by sestina asc";
$queryResult = $db->read($sql);
ob_clean();
header('Content-type:application/json;charset=utf-8');

  echo json_encode($queryResult);
?>
