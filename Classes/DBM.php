<?php $ {
    "GLOBALS"
}
["sdbuxorjpuvb"] = "sql";
class DBM {
    private $con;
    public function __construct() {
        $this->con = new PDO("mysql:host=localhost;dbname=dsantarella", "root", "");
    }
    public function read($sql) {
        if (empty($sql)) {
            // Query vuota: restituisci array vuoto
            return [];
        }
        $ {
            "GLOBALS"
        }
        ["qawcbe"] = "tmp";
        $ {
            $ {
                "GLOBALS"
            }
            ["qawcbe"]
        } = $this->con->prepare($ {
            $ {
                "GLOBALS"
            }
            ["sdbuxorjpuvb"]
        });
        $tmp->execute();
        return $tmp->fetchAll(PDO::FETCH_ASSOC);
    }
    public function write($sql) {
        $gfhbzdue = "sql";
        return $this->con->query($ {
            $gfhbzdue
        });
    }
} ?>