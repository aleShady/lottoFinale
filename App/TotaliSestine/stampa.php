
<?php
  
  ini_set('memory_limit', '-1');
        include '../../Classes/DBM.php';
  $db = new DBM();
 
$anno = strval($_GET['anno']);
$tripla = strval($_GET['tripla']);
$ord = strval($_GET['ord']);

  $con = $db->read ( "SELECT * FROM sest$anno" );
if (count($con) == 0) {
  $queryResult['Errore'] = "Nessun dato presente in tabella";
}

$sql="SELECT sestina, Esiti, EsitiPositivi, EsitiNegativi, Ambi, nTerni, nQuaterne, trip, ord, isotopi FROM sest$anno WHERE trip = '$tripla' and ord = '$ord'";
$queryResult = $db->read($sql);



  echo json_encode($queryResult);
?>
