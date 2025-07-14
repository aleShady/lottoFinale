<?php $ {
    "GLOBALS"
}
["sdbuxorjpuvb"] = "sql";
class DBM {
    private $con;
    public function DBM() {
        $this->con = new PDO("mysql:host=localhost;dbname=my_dsantarella", "root", "");
    }
    public function read($sql) {
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