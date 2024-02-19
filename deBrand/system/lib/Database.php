<?php

namespace system\lib;

use PDOException;
use system\Env;

class Database
{
    protected string $host = "";
    protected string $port = "";
    protected string $database = "";
    protected string $username = "";
    protected string $password = "";
    protected string $tabelPrefix = "";
    protected string $charset;

    protected string $table = "";
    protected string $JoinQuery = "";
    protected string $selectfields = "";
    protected string $WhereQueryString = "";
    protected array $executeParams = [];

    public function __construct()
    {
        $dbgroup = $this->dbgroup();
        $this->host = Env::get("db." . $dbgroup . ".host");
        $this->port = Env::get("db." . $dbgroup . ".port");
        $this->username = Env::get("db." . $dbgroup . ".username");
        $this->password = Env::get("db." . $dbgroup . ".password");
        $this->database = Env::get("db." . $dbgroup . ".database");
        $this->tabelPrefix = Env::get("db." . $dbgroup . ".tabelPrefix");
        $this->charset = Env::get("db." . $dbgroup . ".charset");
    }

    /**
     * dbgroup
     * Deze functie stelt vast welke database groep gebruikt wordt. In het .env bestand
     * zijn 2 database groepen gedefineerd. Dit zijn default en test. Standaard staat 
     * de groep op default. Staat de applicatie in devolopment modus (zie app.environment) 
     * dan zal dbgroup de waarde test krijgen en wordt dus het test database gebruikt.  
     * @return string 
     */
    protected function dbgroup()
    {
        $dbgroup = "default";
        if (Env::get("app.environment") === "development") {
            $dbgroup = "test";
        }
        return $dbgroup;
    }

    /**
     * connect
     * Hier wordt de verbinding met de database tot stand gebracht.
     * @return object
     */
    public function connect()
    {
        $connectionstring = "mysql:dbname={$this->database};host={$this->host};port={$this->port};charset={$this->charset}";
        return new \PDO($connectionstring, $this->username, $this->password);
    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    /**
     * select
     * Hier kan aangegeven worden welke velden er bij SELECT gebruikt moeten worden. Standaard is dit *
     * @param  string $fields - colomn velden van betreffende datatable
     * @return object 
     */
    public function select($fields = "*")
    {
        $this->selectfields = $fields;
        return $this;
    }

    /**
     * from
     * Hier kan aangegeven worden welk datatable gebruikt moet worden. 
     * @param  string $tablename
     * @return object
     */
    public function from($tablename)
    {
        $this->table = $tablename;
        return $this;
    }

    /**
     * table
     * Hier kan aangegeven worden welk datatable gebruikt moet worden. Deze functie doet hetzelfde als
     * de functie from.
     * @param  string $tablename
     * @return object
     */
    public function table($tablename)
    {
        $this->table = $tablename;
        return $this;
    }

    /**
     * where
     * Hier kunnen de voorwaardes meegegeven worden die gebruikt worden bij het uitvoeren van de query. 
     * Iedere Where call bevat 1 voorwaarde bestaand uit columnnaam=waarde. 
     * De tweede parameter bevat de operator die tussen twee voorwaardes in staat. Standaard is dit AND.
     * 
     * @param  string $where - bevat de voorwaarden waarop gefilterd moet worden.
     * @param string $operator - Deze operator wordt voor de voorwaarde in de query string geplaatst.
     * @return object
     */
    public function where($where, $operator = "AND")
    {
        $ps = preg_split("/(>=)|(<=)|(>)|(<)|(=)/i", $where, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        $this->fillWhere($ps[0] . $ps[1], $operator);
        $this->executeParams[] = $ps[2];

        return $this;
    }

    /**
     * fillWhere
     * Deze functie zorgt er voor dat de $this->WhereQueryString gevult wordt. 
     * @param string $wheredata - bevat de voorwaarden waarop gefilterd moet worden.
     * @param string $operator - Deze operator wordt voor de voorwaarde in de query string geplaatst.
     * @return void
     */
    protected function fillWhere($wheredata, $operator)
    {
        if ($this->WhereQueryString != null) {
            $operator = ($operator == null) ? "AND" : $operator;
            $this->WhereQueryString .= " {$operator} {$wheredata}?";
        } else {
            $this->WhereQueryString .= "{$wheredata}?";
        }
    }

    /**
     * join
     * Middels deze functie kan er data uit meerdere datatables gehaald worden.
     * @param  string $table - De Datatable die toegevoegd wordt aan de query string
     * @param  mixed $relation - De relatie tussen twee datatables
     * @param  mixed $jointype - De methode van samenvoegen. Standaard staat deze op INNER
     * @return object
     */
    public function join($table, $relation, $jointype = "INNER")
    {
        $this->JoinQuery .= "{$jointype} JOIN {$table} ON {$relation} ";
        return $this;
    }

    /**
     * get
     * Deze functie voert de database SELECT actie uit.
     * Deze functie stuurd de opgehaalde data retour.
     * @return array
     */
    public function get()
    {
        try {

            $conn = $this->connect();
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $string = "SELECT {$this->selectfields} FROM {$this->table}";
            if ($this->JoinQuery != null) {
                $string .= " {$this->JoinQuery}";
            }
            if ($this->WhereQueryString != null) {
                $string .= " WHERE {$this->WhereQueryString}";
            }


            $result = $conn->prepare($string);



            return ($result->execute($this->executeParams)) ? $result->fetchAll(\PDO::FETCH_ASSOC) : null;
            $string = "";
            $this->clearQueryStrings();
        } catch (PDOException $e) {
            echo $e->getMessage();

            return 0;
        }
    }

    /**
     * update
     * Deze functie voert de database SELECT actie uit.
     * Deze functie stuurd de opgehaalde data retour.
     * @param  array $data - dit betreft de data die bijgewerkt wordt in de datatable.
     * @return void return 1 als actie is voltooid en 0 als dit niet is gelukt.
     */
    public function update($data)
    {
        try {

            $conn = $this->connect();
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $fields = "";


            foreach ($data as $key => $val) {
                $fields .= $fields == "" ? "{$key}=?" : ", {$key}=?";
                $val = $this->cleanUpValue($val);
                $values[] = $val;
            }
            foreach ($this->executeParams as $v) {
                $values[] = $v;
            }

            $string = "UPDATE {$this->table} SET {$fields}";
            if ($this->WhereQueryString != null) {
                $string .= " WHERE {$this->WhereQueryString}";
            }

            $result = $conn->prepare($string);

            $string = "";
            $this->clearQueryStrings();

            return $result->execute($values);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return 0;
        }
    }


    /**
     * insert
     * Die functie voegt nieuwe data toe aan de opgegeven datatable.
     * @param  mixed $data - dit betreft de nieuwe data die opgeslagen moet worden in de datatable.
     * @return void return 1 als actie is voltooid en 0 als dit niet is gelukt.
     */
    public function insert($data)
    {
        try {
            $conn = $this->connect();
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $fields = "";
            $values = [];
            $marker = "";
            foreach ($data as $key => $val) {
                $fields .= $fields == "" ? "{$key}" : ", {$key}";
                $val = $this->cleanUpValue($val);
                $values[] = $val;
                ($marker == "") ? $marker .= "?" : $marker .= ", ?";
            }

            $string = "INSERT INTO {$this->table} ({$fields}) VALUES ({$marker})";
            echo $string;
            $result = $conn->prepare($string);

            return $result->execute($values);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return 0;
        }
    }



    /**
     * count
     * Deze functie stuurt het aantal resultaten terug. 
     * @return int - stuurt het aantal gevonden resultaten terug
     */
    public function count()
    {
        $conn = $this->connect();
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $string = "SELECT * FROM {$this->table}";
        if ($this->WhereQueryString != null) {
            $string .= " WHERE {$this->WhereQueryString}";
        }
        $result = $conn->prepare($string);

        $result->execute($this->executeParams);
        return $result->rowCount();
    }

    /**
     * cleanUpValue
     * Deze functie zorgt er voor dat de data schoon gemaakt wordt van onnodige witruimtes, html tekens en loze karakters
     * @param  string $value - de data die opgeschoont moet worden
     * @return string - de schone data die terug gestuurd wordt. 
     */
    private function cleanUpValue($value)
    {
        if (!empty($value)) {
            $value = trim($value);
            $value = stripslashes($value);
            $value = htmlspecialchars($value);
            return $value;
        } else {
            return null;
        }
    }

    private function clearQueryStrings()
    {
        $this->executeParams = [];
        $this->WhereQueryString = "";
        $this->JoinQuery = "";
        $this->selectfields = "";
        $this->table = "";
    }
}
