<?php

class Track {
    public $pdo = null;
    public $stmt = null;
    public $error = "";
    function __construct () { try {
    $this->pdo = new PDO (
            "mysql:host=".DB_HOST.";dbaname=".DB_NAME.";charset=".DB_CHARSET, 
            DB_USER, DB_PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (Exception $ex) { exit($ex->getMessage());}}

    //close database connection
    function __destruct () {
        if ($this->stmt !== null) {$this->stmt = null; }
        if ($this->pdo !== null) {$this->pdo = null; }
    }

    function query ($ql, $data=null) {
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute($data);
    }

    //updating coordinates
    function update ($id, $lng, $lat) {
        $this->query (
        "REPLACE INTO 'tracking' ('IDRider','trackTime','longitude','latitude') VALUES (?,?,?,?)",
        [$id, date("Y-m-d H:i:s"), $lng,$lat]
        );
        return true;
    }

    //rider coordinates
    function get ($id=null) {
        $this->query (
            "SELECT * FROM 'tracking'" . ($id==null ? "" : " WHERE 'IDRider = ?'"),
            $id==null ? null : [id]
        );
        return $this->stmt->fetchAll();
    }

}

define ("DB_HOST", "localhost");
define ("DB_NAME", "test");
define ("DB_CHARSET", "utf8");
define ("DB_USER", "root");
define ("DB_PASSWORD", "");

$_TRACK = NEW Track();





?>