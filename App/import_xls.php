<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Classes/DBM.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Percorso file XLS
$filePath = __DIR__ . '/../it-lotto-past-draws-archive.csv';
$filePath = __DIR__ . '/../it-lotto-past-draws-archive.csv'; // Percorso file CSV
// Carica il file XLS
$spreadsheet = IOFactory::load($filePath);
$reader = IOFactory::createReader('Csv');
$reader->setDelimiter(","); // Cambia in "\t" se il file è separato da tab
$spreadsheet = $reader->load($filePath);

// Ruote in ordine
$ruote = [
    "Bari", "Cagliari", "Firenze", "Genova", "Milano",
    "Napoli", "Palermo", "Roma", "Torino", "Venezia"
];

$dbm = new DBM();
$yearCol = 'A'; // Colonna data
$firstRow = 3; // Di solito la prima riga utile (dopo intestazione)
$progressivo = 1;

for ($row = $firstRow; ; $row++) {
    $date = $sheet->getCell('A' . $row)->getValue();
    if (!$date) break;
    $dateStr = $date;

    $data = [];
    $col = 'B';
    foreach ($ruote as $ruota) {
        $nums = [];
        for ($i = 0; $i < 5; $i++) {
            $val = $sheet->getCell($col . $row)->getValue();
            $nums[] = $val;
            $col++;
        }
        $data[$ruota] = implode('.', $nums);
    }
    $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
    $sql = "INSERT IGNORE INTO year2025 (estrazione, data, valori) VALUES ('$progressivo', '$dateStr', '$jsonData')";
    $dbm->write($sql);
    $progressivo++;
}

echo "Importazione completata.";
