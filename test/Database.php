<?php

class Database
{
    private static $instance = null;
    private $pdo, $query, $error = false, $results, $count;

    private function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=marlin_part_2;', "root", "root");
        } catch (PDOException $exception) {
            die($exception->getMessage());
        }
    }

    public static function GetInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function query($sql, $params = [])
    {

        $this->error = false;
        $this->query = $this->pdo->prepare($sql);
        if (count($params)) {
            $i = 1;
            foreach ($params as $param) {
                $this->query->bindValue($i, $param);
                $i++;
            }
        }
        if (!$this->query->execute()) {
            $this->error = true;
        } else {
            $this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
            $this->count = $this->query->rowCount();
        }
        return $this;
    }

    public function get($table, $where = [])
    {
        return $this->action('SELECT *', $table, $where);
    }
    public function delete($table, $where=[]){
        return $this->action('DELETE', $table, $where);
    }
    public function action($action, $table, $where = []){
        if (count($where) === 3) {
            $operators = ['=', '>', '<', '>=', '<='];

            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];
            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} where {$field} {$operator} ?";
                if (!$this->query($sql, [$value])->error()) {
                    return $this;
                }
            }
        }
        return false;
    }

    public function insert($table, $fields=[])
    {
        $values="";
        foreach ($fields as $field){
            $values.="?, ";
        }
        $values=rtrim($values, ', ' );
        $sql="INSERT {$table} (".'`'.implode("`, `", array_keys($fields)).'`'.") VALUES({$values})";
        if(!$this->query($sql, $fields)->error()){
            return true;
        }
        return false;
    }
    public function result()
    {
        return $this->results;
    }

    public function error()
    {
        return $this->error;
    }

    public function count()
    {
        return $this->count;
    }
}