
<?php

        include '../../Classes/DBM.php';
  $db = new DBM();
         $model = json_decode($_REQUEST['model']);



  $con = $db->read ( "SELECT * FROM sest$model->currentYear" );
if (count($con) == 0) {
  $queryResult['Errore'] = "Nessun dato presente in tabella";
}

$sql="SELECT anno, ordine, pagina, tripla FROM sest$model->currentYear";
$queryResult = $db->read($sql);
ob_clean();
header('Content-type:application/json;charset=utf-8');

  echo json_encode($queryResult);
?>
