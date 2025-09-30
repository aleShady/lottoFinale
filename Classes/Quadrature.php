<?php

class Quadrature
{
    private $year;
    private $distanza;
    private $tripla;
    private $isotopi;
    private $query;

    /**
     * Quadrature constructor.
     * @param int|string $year
     * @param array|string $distanza
     * @param string $tripla
     * @param bool $isotopi
     */
    public function __construct($year, $distanza, $tripla, $isotopi = false)
    {
        $this->year = $year;
        $this->distanza = $distanza;
        $this->tripla = $tripla;
        $this->isotopi = $isotopi;
        $this->generateQuery();
    }

    public function getQuadrature()
    {
        $values = $this->getValues();
        $max = count($values) - 1;
        $res = [
            "destroso" => [],
            "sinistroso" => []
        ];
        for ($i = 0; $i < $max; $i++) {
            for ($j = $i + 1; $j <= $max; $j++) {
                if ($this->isotopi && ($values[$i]["distanza"] != $values[$j]["distanza"])) {
                    continue;
                }
                // Destroso
                if ($values[$i]["val1"] < $values[$i]["val2"] && $values[$j]["val1"] > $values[$j]["val2"] && $values[$j]["val2"] > $values[$i]["val2"]) {
                    $this->addValue($res["destroso"], $values[$i], $values[$j]);
                }
                if ($values[$i]["val2"] < $values[$j]["val2"] && $values[$i]["val1"] > $values[$j]["val1"] && $values[$j]["val1"] > $values[$j]["val2"]) {
                    $this->addValue($res["destroso"], $values[$i], $values[$j]);
                }
                if ($values[$j]["val2"] < $values[$j]["val1"] && $values[$i]["val2"] > $values[$i]["val1"] && $values[$i]["val1"] > $values[$j]["val2"]) {
                    $this->addValue($res["destroso"], $values[$i], $values[$j]);
                }
                if ($values[$j]["val1"] < $values[$i]["val1"] && $values[$j]["val2"] > $values[$i]["val2"] && $values[$i]["val2"] > $values[$i]["val1"]) {
                    $this->addValue($res["destroso"], $values[$i], $values[$j]);
                }
                // Sinistroso
                if ($values[$i]["val1"] > $values[$i]["val2"] && $values[$j]["val1"] < $values[$j]["val2"] && $values[$i]["val1"] < $values[$j]["val1"]) {
                    $this->addValue($res["sinistroso"], $values[$i], $values[$j]);
                }
                if ($values[$i]["val2"] > $values[$j]["val2"] && $values[$i]["val1"] < $values[$j]["val1"] && $values[$i]["val2"] < $values[$i]["val1"]) {
                    $this->addValue($res["sinistroso"], $values[$i], $values[$j]);
                }
                if ($values[$j]["val2"] > $values[$j]["val1"] && $values[$i]["val2"] < $values[$i]["val1"] && $values[$j]["val2"] < $values[$i]["val2"]) {
                    $this->addValue($res["sinistroso"], $values[$i], $values[$j]);
                }
                if ($values[$j]["val1"] > $values[$i]["val1"] && $values[$j]["val2"] < $values[$i]["val2"] && $values[$j]["val1"] < $values[$j]["val2"]) {
                    $this->addValue($res["sinistroso"], $values[$i], $values[$j]);
                }
            }
        }
        return $res;
    }

    public function getQuadratureSinistroso()
    {
        $values = $this->getValues();
        $max = count($values) - 1;
        $res = [];
        for ($i = 0; $i < $max; $i++) {
            for ($j = $i + 1; $j <= $max; $j++) {
                if ($this->isotopi && ($values[$i]["distanza"] != $values[$j]["distanza"])) {
                    continue;
                }
                if ($values[$i]["val1"] > $values[$i]["val2"] && $values[$j]["val1"] < $values[$j]["val2"] && $values[$i]["val1"] < $values[$j]["val1"]) {
                    $this->addValue($res, $values[$i], $values[$j]);
                }
                if ($values[$i]["val2"] > $values[$j]["val2"] && $values[$i]["val1"] < $values[$j]["val1"] && $values[$i]["val2"] < $values[$i]["val1"]) {
                    $this->addValue($res, $values[$i], $values[$j]);
                }
                if ($values[$j]["val2"] > $values[$j]["val1"] && $values[$i]["val2"] < $values[$i]["val1"] && $values[$j]["val2"] < $values[$i]["val2"]) {
                    $this->addValue($res, $values[$i], $values[$j]);
                }
                if ($values[$j]["val1"] > $values[$i]["val1"] && $values[$j]["val2"] < $values[$i]["val2"] && $values[$j]["val1"] < $values[$j]["val2"]) {
                    $this->addValue($res, $values[$i], $values[$j]);
                }
            }
        }
        return $res;
    }

    public function getQuadratureDestroso()
    {
        $values = $this->getValues();
        $max = count($values) - 1;
        $res = [];
        for ($i = 0; $i < $max; $i++) {
            for ($j = $i + 1; $j <= $max; $j++) {
                if ($this->isotopi && ($values[$i]["distanza"] != $values[$j]["distanza"])) {
                    continue;
                }
                if ($values[$i]["val1"] < $values[$i]["val2"] && $values[$j]["val1"] > $values[$j]["val2"] && $values[$j]["val2"] > $values[$i]["val2"]) {
                    $this->addValue($res, $values[$i], $values[$j]);
                }
                if ($values[$i]["val2"] < $values[$j]["val2"] && $values[$i]["val1"] > $values[$j]["val1"] && $values[$j]["val1"] > $values[$j]["val2"]) {
                    $this->addValue($res, $values[$i], $values[$j]);
                }
                if ($values[$j]["val2"] < $values[$j]["val1"] && $values[$i]["val2"] > $values[$i]["val1"] && $values[$i]["val1"] > $values[$j]["val2"]) {
                    $this->addValue($res, $values[$i], $values[$j]);
                }
                if ($values[$j]["val1"] < $values[$i]["val1"] && $values[$j]["val2"] > $values[$i]["val2"] && $values[$i]["val2"] > $values[$i]["val1"]) {
                    $this->addValue($res, $values[$i], $values[$j]);
                }
            }
        }
        return $res;
    }

    private function addValue(&$var, $tmp1, $tmp2)
    {
        $tmpLG = [$tmp1["val1"], $tmp1["val2"], $tmp2["val1"], $tmp2["val2"]];
        $lower = $this->getLower($tmpLG);
        $greater = $this->getLower($tmpLG);
        $var[] = [
            "ruota_1" => $tmp1["ruota"],
            "distanza_1" => $tmp1["distanza"],
            "val1_1" => $tmp1["val1"],
            "val2_1" => $tmp1["val2"],
            "trip_1" => $this->getTripla($tmp1["val1"]) . "-" . $this->getTripla($tmp1["val2"]),
            "somma_1" => $this->getRaddoppio($tmp1["val1"] + $tmp1["val2"]),
            "estrazione_1" => $tmp1["estrazione"],
            "data_1" => $tmp1["data"],
            "ruota_2" => $tmp2["ruota"],
            "distanza_2" => $tmp2["distanza"],
            "val1_2" => $tmp2["val1"],
            "val2_2" => $tmp2["val2"],
            "trip_2" => $this->getTripla($tmp2["val1"]) . "-" . $this->getTripla($tmp2["val2"]),
            "somma_2" => $this->getRaddoppio($tmp2["val1"] + $tmp2["val2"]),
            "estrazione_2" => $tmp2["estrazione"],
            "data_2" => $tmp2["data"],
            "somma_comune" => $this->getRaddoppio($tmp2["val1"] + $tmp1["val1"]),
            "somma_diag_1" => $this->getRaddoppio($tmp1["val2"] + $tmp2["val1"]),
            "somma_diag_2" => $this->getRaddoppio($tmp1["val1"] + $tmp2["val2"]),
            "raddoppio_somma_comune" => $this->getRaddoppio($this->getRaddoppio($tmp2["val1"] + $tmp1["val1"]) * 2),
            "diagonale" => $this->getDiagonale($tmp2["val2"], $tmp1["val1"]),
            "sopra" => $this->calolaLati($tmp1["val1"], $tmp1["val2"], $lower, $greater),
            "destra" => $this->calolaLati($tmp1["val2"], $tmp2["val2"], $lower, $greater),
            "sotto" => $this->calolaLati($tmp2["val1"], $tmp2["val2"], $lower, $greater),
            "sinistra" => $this->calolaLati($tmp1["val1"], $tmp2["val1"], $lower, $greater)
        ];
    }

    private function calolaLati($v1, $v2, $p, $g)
    {
        if ($v1 > $v2) {
            if ($v1 == $g && $v2 == $p)
                return ($v2 + 90) - $v1;
            else
                return $v1 - $v2;
        } else {
            if ($v2 == $g && $v1 == $p)
                return ($v1 + 90) - $v2;
            else
                return $v2 - $v1;
        }
    }

    private function getDiagonale($val1, $val2)
    {
        $res = abs($val1 - $val2);
        return ($res > 45) ? 90 - $res : $res;
    }

    private function getRaddoppio($val)
    {
        return ($val > 90) ? $val - 90 : $val;
    }

    private function getTripla($val)
    {
        $val = (string)$val;
        $value = (strlen($val) > 1) ? ((int)$val[1] + (int)$val[0]) : (int)$val[0];
        if ($value > 9) $value -= 9;
        return $value;
    }

    private function getLower($tmp)
    {
        $res = $tmp[0];
        for ($i = 1; $i < 4; $i++) {
            if ($tmp[$i] < $res)
                $res = $tmp[$i];
        }
        return $res;
    }

    private function getGreater($tmp)
    {
        $res = $tmp[0];
        for ($i = 1; $i < 4; $i++) {
            if ($tmp[$i] > $res)
                $res = $tmp[$i];
        }
        return $res;
    }

    private function getValues()
    {
        $dbm = new DBM();
        return $dbm->read($this->query);
    }

    private function generateQuery()
    {
        $sql = "SELECT * FROM quad$this->year";
        $where = [];
        if ($this->distanza != "*") {
            $distanze = array_map(function($k) {
                return "'$k'";
            }, (array)$this->distanza);
            $where[] = "distanza IN (" . implode(',', $distanze) . ")";
        }
        if ($this->tripla != "*") {
            $where[] = "tripla = '$this->tripla'";
        }
        if (count($where) > 0) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        $this->query = $sql;
    }
}
?>
