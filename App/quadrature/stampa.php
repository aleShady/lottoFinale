
<?php

        include '../../Classes/DBM.php';
  $db = new DBM();
         $model = json_decode($_REQUEST['model']);



  $con = $db->read ( "SELECT * FROM sest$model->currentYear" );
if (count($con) == 0) {
  $queryResult['Errore'] = "Nessun dato presente in tabella";
}

$sql="SELECT anno, ordine, pagina, tripla FROM sest$model->currentYear";
if (!empty($sql)) {
    $queryResult = $db->read($sql);
} else {
    $queryResult = [];
}
ob_clean();
header('Content-type:application/json;charset=utf-8');

  echo json_encode($queryResult);
?>
