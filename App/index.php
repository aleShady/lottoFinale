<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__DIR__) . '/cache/error.log');

require_once '../Classes/DBM.php';

// Verifica che l'estensione ZIP sia disponibile
if (!extension_loaded('zip')) {
    die('L\'estensione ZIP non è installata.');
}

class LottoFetcher {
    private $cacheDir;
    private $isAltervista;

    public function __construct($cacheDir = null) {
        $this->isAltervista = (strpos($_SERVER['HTTP_HOST'] ?? '', 'altervista.org') !== false);
        
        if ($cacheDir === null) {
            if ($this->isAltervista) {
                $this->cacheDir = dirname(__DIR__) . '/temp';
            } else {
                $this->cacheDir = dirname(__DIR__) . '/cache';
            }
        } else {
            $this->cacheDir = $cacheDir;
        }
        
        $this->ensureCacheDirectory();
    }

    private function ensureCacheDirectory() {
        if (!file_exists($this->cacheDir)) {
            if (!@mkdir($this->cacheDir, 0755, true)) {
                if (!@mkdir($this->cacheDir, 0777, true)) {
                    throw new Exception("Impossibile creare la directory: " . $this->cacheDir);
                }
            }
        }

        if (!is_writable($this->cacheDir)) {
            if ($this->isAltervista) {
                throw new Exception("Directory non scrivibile: " . $this->cacheDir);
            } else {
                if (!@chmod($this->cacheDir, 0777)) {
                    throw new Exception("Impossibile impostare i permessi: " . $this->cacheDir);
                }
            }
        }
    }

    public function fetchFromLottoItalia($year = null) {
        $baseUrl = "https://www.brightstarlottery.it/STORICO_ESTRAZIONI_LOTTO/storico.zip";
        $cacheFile = "{$this->cacheDir}/estrazioni_" . ($year ?? 'completo') . ".txt";
        
        if (file_exists($cacheFile)) {
            @unlink($cacheFile);
        }
        
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "User-Agent: Mozilla/5.0\r\n",
                'timeout' => 30
            ),
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false
            )
        );
        
        $context = stream_context_create($opts);
        
        $tmpZip = tempnam($this->cacheDir, 'zip_');
        if ($tmpZip === false) {
            throw new Exception("Impossibile creare file temporaneo ZIP");
        }
        
        if (!@copy($baseUrl, $tmpZip, $context)) {
            @unlink($tmpZip);
            throw new Exception("Impossibile scaricare il file ZIP");
        }
        
        $zip = new ZipArchive();
        if ($zip->open($tmpZip) !== true) {
            @unlink($tmpZip);
            throw new Exception("Impossibile aprire il file ZIP");
        }
        
        $content = $zip->getFromName('storico.txt');
        $zip->close();
        @unlink($tmpZip);
        
        if ($content === false) {
            throw new Exception("File storico.txt non trovato nel ZIP");
        }
        
        if (!mb_check_encoding($content, 'UTF-8')) {
            $content = mb_convert_encoding($content, 'UTF-8', 'ISO-8859-1');
        }
        
        $content = str_replace("\xEF\xBB\xBF", '', $content);
        
        if (file_put_contents($cacheFile, $content) === false) {
            throw new Exception("Impossibile salvare il file nella cache");
        }
        
        @chmod($cacheFile, 0644);
        return $cacheFile;
    }

    public function parseTXT($txtFile) {
        $rows = [];
        $lines = file($txtFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $currentData = null;
        $currentRow = [];

        foreach ($lines as $line) {
            $parts = preg_split('/\s+/', trim($line));
            
            if (count($parts) < 7) continue;
            
            $date = $parts[0];
            $city = $parts[1];
            $numbers = array_slice($parts, 2, 5);
            
            if ($currentData !== $date) {
                if ($currentData !== null) {
                    $rows[] = $currentRow;
                }
                $currentData = $date;
                $currentRow = ['DATE' => $date];
            }
            
            $cityMap = [
                'BA' => 'Bari',
                'CA' => 'Cagliari',
                'FI' => 'Firenze',
                'GE' => 'Genova',
                'MI' => 'Milano',
                'NA' => 'Napoli',
                'PA' => 'Palermo',
                'RM' => 'Roma',
                'TO' => 'Torino',
                'VE' => 'Venezia',
                'NZ' => 'Nazionale'
            ];
            
            $cityName = $cityMap[$city] ?? $city;
            
            for ($i = 0; $i < 5; $i++) {
                $currentRow[$cityName . ($i + 1)] = $numbers[$i];
            }
        }
        
        if ($currentRow) {
            $rows[] = $currentRow;
        }
        
        return $rows;
    }
}

function getDistance($x, $y) {
    if($x > $y)
        $tmp = $x - $y;
    else
        $tmp = $y - $x;
        
    if($tmp > 45)
        $aux = 90 - intval($tmp);
    else
        $aux = $tmp;
        
    if($aux == 3)
        return "x";
    else
        return $aux;
}

function getTripla($val) {
    if(isset($val[1]))
        $value = $val[1] + $val[0];
    else
        $value = $val[0];
        
    if($value > 9)
        $value -= 9;
        
    if($value == 1 || $value == 4 || $value == 7)   return '1-4-7';
    if($value == 2 || $value == 5 || $value == 8)   return '2-5-8';
    if($value == 3 || $value == 6 || $value == 9)   return '3-6-9';
}

$currentYear = (int)date('Y');

try {
    $fetcher = new LottoFetcher();
    $dbm = new DBM();
    
    // Verifica e crea le tabelle se non esistono
    $tableCheck = $dbm->read("SHOW TABLES LIKE 'year$currentYear'");
    if (empty($tableCheck)) {
        $createTable = "CREATE TABLE IF NOT EXISTS year$currentYear (
            estrazione INT NOT NULL AUTO_INCREMENT,
            data VARCHAR(255) NOT NULL,
            valori TEXT NOT NULL,
            PRIMARY KEY (estrazione)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $dbm->write($createTable);
        
        $createQuadTable = "CREATE TABLE IF NOT EXISTS quad$currentYear (
            id INT NOT NULL AUTO_INCREMENT,
            data VARCHAR(255) NOT NULL,
            ruota VARCHAR(50) NOT NULL,
            estrazione INT NOT NULL,
            distanza VARCHAR(10) NOT NULL,
            tripla VARCHAR(20) NOT NULL,
            val1 INT NOT NULL,
            val2 INT NOT NULL,
            PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $dbm->write($createQuadTable);
    }

    $txtFile = $fetcher->fetchFromLottoItalia($currentYear);
    $estrazioni = $fetcher->parseTXT($txtFile);

    $mesi = [
        'January' => 'Gennaio', 'February' => 'Febbraio', 'March' => 'Marzo',
        'April' => 'Aprile', 'May' => 'Maggio', 'June' => 'Giugno',
        'July' => 'Luglio', 'August' => 'Agosto', 'September' => 'Settembre',
        'October' => 'Ottobre', 'November' => 'Novembre', 'December' => 'Dicembre'
    ];

    $ruote = [
        "Bari", "Cagliari", "Firenze", "Genova", "Milano",
        "Napoli", "Palermo", "Roma", "Torino", "Venezia", "Nazionale"
    ];

    // Processa le estrazioni
    oreach ($estrazioni as $estrazione) {
    $timestamp = strtotime($estrazione['DATE']);
    $mese_en = date('F', $timestamp);
    $mese_it = $mesi[$mese_en];
    $date = date('d', $timestamp) . ' ' . $mese_it . ' ' . date('Y', $timestamp);

    // Verifica se l'estrazione esiste già
    $check = $dbm->read("SELECT COUNT(*) as cnt FROM year$currentYear WHERE data = '" . addslashes($date) . "'");
    if (isset($check[0]['cnt']) && $check[0]['cnt'] > 0) {
        continue;
    }

    // Prepara i dati nel formato corretto
    $data = [];
    foreach ($ruote as $ruota) {
        $nums = [];
        for ($i = 1; $i <= 5; $i++) {
            $col = $ruota . $i;
            if (isset($estrazione[$col])) {
                $nums[] = str_pad($estrazione[$col], 2, '0', STR_PAD_LEFT);
            }
        }
        $data[$ruota] = implode('.', $nums);
    }

    $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
    
    // Inserisci nel database
    $sql = "INSERT INTO year$currentYear (data, valori) 
            VALUES ('" . addslashes($date) . "', '" . addslashes($jsonData) . "')";
    $dbm->write($sql);
}

    // Processa le quadrature
    $output = $dbm->read("SELECT * FROM year$currentYear ORDER BY estrazione ASC");
foreach ($output as $row) {
    $est = json_decode($row['valori'], true);
    $data = $row['data'];

    foreach ($est as $ruota => $valori) {
        $estrat = explode('.', $valori);
        
        for ($aux = 0; $aux < 5; $aux++) {
            for ($aux1 = $aux + 1; $aux1 < 5; $aux1++) {
                if (getDistance($estrat[$aux], $estrat[$aux1]) == 'x') {
                    $sql = "INSERT INTO quad$currentYear (data, ruota, estrazione, distanza, tripla, val1, val2) 
                           VALUES ('" . addslashes($data) . "', 
                                  '" . addslashes($ruota) . "', 
                                  {$row['estrazione']}, 
                                  '" . ($aux + 1) . '-' . ($aux1 + 1) . "', 
                                  '" . getTripla($estrat[$aux]) . "', 
                                  " . intval($estrat[$aux]) . ", 
                                  " . intval($estrat[$aux1]) . ")";
                    $dbm->write($sql);
                }
            }
        }
    }
}

    echo "<div style='color: green; padding: 20px;'>Elaborazione completata con successo</div>";

} catch (Exception $e) {
    echo "<div style='color: red; padding: 20px;'>";
    echo "<b>Errore:</b> " . htmlspecialchars($e->getMessage());
    echo "<br>File: " . htmlspecialchars($e->getFile());
    echo "<br>Linea: " . $e->getLine();
    echo "</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>LOTTO</title>
    <meta charset="UTF-8">
    <style type="text/css">
        @font-face { font-family: Lato; src: url(css/Lato/Lato-Regular.ttf); }
        @font-face { font-family: Lato; font-weight: bold; src: url(css/Lato/Lato-Bold.ttf); }
        body { font-family: Lato; background: #fff; margin: 0px; }
        .header { width: 100%; background: #ffffff; padding: 15px 0 15px 0; border-bottom: 2px solid #FF5C01; margin-bottom: 25px; }
        .item { display: inline-block; background: #ffffff; width: 200px; height: 150px; margin: 0 20px 0 20px; border-radius: 5px; box-shadow: 1px 1px 3px 1px rgba(0,0,0,.2); vertical-align: top; cursor: pointer; transition: opacity .4s ease-in-out; }
        .item:hover { opacity: 0.8; transition: opacity .4s ease-in-out; }
        .title { font-weight: bold; text-transform: uppercase; padding: 7px 0 7px 0; width: 90%; color: #555; border-bottom: 1px solid rgba(255,92,1,.3); text-align: center; margin: 0 auto; }
        .content { width: 90%; text-align: center; padding-top: 20px; color: #777; margin: 0 auto; }
    </style>
</head>
<body>
    <center>
        <div class="header">
            <img src="img/logo.png" width="30%"/>
        </div>
        <div class="item" url="estrazioni">
            <div class="title">estrazioni</div>
            <div class="content">
                Elenco di tutte le estrazioni dal 1871 ad oggi.
            </div>
        </div>
        <!-- ... altri div item ... -->
    </center>

    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.item').click(function(){
                var url = $(this).attr('url');
                if (url) {
                    window.location.href = url;
                }
            });
        });
    </script>
</body>
</html>