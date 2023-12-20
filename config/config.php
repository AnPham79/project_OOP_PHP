<?php

class database
{
    private $db_host = 'localhost';
    private $db_user = 'root';
    private $db_pass = '';
    private $db_name = 'project_oop_php';

    public $mysqli = "";
    private $conn = false;
    public $result = array();

    public function __construct()
    {
        if (!$this->conn) {
            $this->mysqli = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
            return $this->conn = true;

            if ($this->mysqli->connect_error) {
                array_push($this->result, $this->mysqli->connect_error);
                return false;
            }
        } else {
            return false;
        }
    }

    public function tableExists($table)
    {
        $sql = "SHOW TABLES LIKE '$table'";
        $tableInDB = $this->mysqli->query($sql);
        if ($tableInDB->num_rows == 1) {
            return true;
        } else {
            array_push($this->result, $table . "Không có bảng yêu cầu");
            return false;
        }
    }

    public function result()
    {
        $var = $this->result;
        $this->result = array();
        return $var;
    }

    public function sql($sql)
    {
        $query = $this->mysqli->query($sql);

        if ($query !== false) {
            if ($query instanceof mysqli_result) {
                $this->result = $query->fetch_all(MYSQLI_ASSOC);
            }
            return true;
        } else {
            array_push($this->result, $this->mysqli->error);
            return false;
        }
    }

    public function __destruct()
    {
        if ($this->conn) {
            if ($this->mysqli->close()) {
                $this->conn = false;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
