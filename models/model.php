<?php

session_start();

require './config/config.php';

class Account
{
    private $conn_db;

    public function __construct()
    {
        $this->conn_db = new Database;
    }

    public function emailExists($table, $email)
    {
        $sql_check_email = "SELECT count(*) as count FROM $table WHERE email = ?";
        $stmt = $this->conn_db->mysqli->prepare($sql_check_email);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $stmt->close();

        return $count > 0;
    }

    public function insertAccount($table, $params = [])
    {
        if ($this->conn_db->tableExists($table)) {
            $email = $params['email'];
            $hovaten = $params['hovaten'];

            if ($this->emailExists($table, $email)) {
                return 'Email của bạn đã được sử dụng';
            } else {
                $columns = implode(',', array_keys($params));
                $values = "'" . implode("','", array_values($params)) . "'";

                $sql = "INSERT INTO $table ($columns) VALUES ($values)";

                if ($this->conn_db->sql($sql)) {
                    array_push($this->conn_db->result, $this->conn_db->mysqli->insert_id);
                    if (isset($_SESSION['hovaten'])) {
                        $_SESSION['hovaten'] = $params['hovaten'];
                        return $_SESSION['hovaten'];
                    }
                    $_SESSION['hovaten'] = $hovaten;
                    return true;
                } else {
                    array_push($this->conn_db->result, $this->conn_db->mysqli->error);
                    return false;
                }
            }
        } else {
            return 'Không có bảng yêu cầu';
        }
    }

    public function signOut() {
        if (isset($_SESSION['hovaten'])) {
            unset($_SESSION['hovaten']);
        }
        if (isset($_SESSION['email'])) {
            unset($_SESSION['email']);
        }
        return true;
    }
    
}
