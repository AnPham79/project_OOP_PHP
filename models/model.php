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

    public function processLogin($email, $matkhau)
    {
        if (isset($_POST['email']) && isset($_POST['matkhau'])) {
            $sql = "SELECT * FROM taikhoan WHERE email = ? AND matkhau = ?";
            $stmt = $this->conn_db->mysqli->prepare($sql);

            if ($stmt) {
                $stmt->bind_param('ss', $email, $matkhau);
                $stmt->execute();

                $result = $stmt->get_result();

                if ($result) {
                    $user = $result->fetch_assoc();

                    if ($user) {
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['matkhau'] = $user['matkhau'];
                        $_SESSION['hovaten'] = isset($user['hovaten']) ? $user['hovaten'] : '';
                        $_SESSION['quyen'] = isset($user['quyen']) ? $user['quyen'] : '';

                        if ($user['quyen'] === null) {
                            header('location: ./index.php');
                        } elseif ($user['quyen'] === 1) {
                            header('location: ./admin/index.php');
                        }
                    } else {
                        header('location: ./index.php?action=login&error=Tên đăng nhập hoặc mật khẩu không đúng!');
                        exit();
                    }
                } else {
                    echo "Có lỗi trong quá trình lấy dữ liệu từ cơ sở dữ liệu.";
                }
            } else {
                echo "Có lỗi trong quá trình chuẩn bị truy vấn.";
            }
        }
    }



    public function signOut()
    {
        if (isset($_SESSION['hovaten'])) {
            unset($_SESSION['hovaten']);
        }
        if (isset($_SESSION['email'])) {
            unset($_SESSION['email']);
        }
        return true;
    }
}
