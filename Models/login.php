<?php


class login
{
    private $con;

    public function __construct()
    {
        $this->con = Connection::getConnection();
    }
    public function connectionCount()
    {
        $sql = "SELECT count(*) AS attempts, MINUTE(TIMEDIFF(NOW(), MAX(date_time))) AS 'minutes' ";
        $sql .= "FROM tab_log_attempts WHERE ip = ? and DATE_FORMAT(date_time,'%Y-%m-%d') = ? AND blocked = ?";
        $stm = $this->con->prepare($sql);
        $stm->bindValue(1, $_SERVER['SERVER_ADDR']);
        $stm->bindValue(2, date('Y-m-d'));
        $stm->bindValue(3, 'YES');
        $stm->execute();
        $return = $stm->fetch(PDO::FETCH_OBJ);
        return $return;
    }

    public function userVerify($email)
    {
        $sql = 'SELECT COUNT(id) FROM users WHERE email = ?';
        $stm = $this->con->prepare($sql);
        $stm->bindValue(1, $email);
        $stm->execute();
        $return = $stm->rowCount();
        return $return;
    }

    public function attempt($email, $password, $blocked)
    {
        $sql = 'INSERT INTO tab_log_attempts (ip, email, password, origin, blocked) VALUES (?, ?, ?, ?, ?)';
        $stm = $this->con->prepare($sql);
        $stm->bindValue(1, $_SERVER['SERVER_ADDR']);
        $stm->bindValue(2, $email);
        $stm->bindValue(3, $password);
        $stm->bindValue(4, $_SERVER['HTTP_REFERER']);
        $stm->bindValue(5, $blocked);
        $stm->execute();
    }


    public function getUserEmail($token)
    {
        $sql = 'SELECT * FROM users WHERE email = ?';
        $stm = $this->con->prepare($sql);
        $stm->bindValue(1, $token);
        $stm->execute();
        $data = $stm->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function getUserToken($token)
    {
        $sql = 'SELECT token FROM users WHERE token = :token';
        $stm = $this->con->prepare($sql);
        $stm->bindValue(':token', $token);
        $stm->execute();
        $data = $stm->fetch(PDO::FETCH_ASSOC);

        return $data;
    }

    public function register($nome, $email, $password)
    {
        $sql = "INSERT INTO users(name, email, password, status)VALUES('{$nome}', '{$email}', '{$password}', '1')";
        $stm = $this->con->prepare($sql);
        return $stm->execute();
    }

    public function renewPassword($password, $token)
    {
        $sql = 'UPDATE users SET password = ? WHERE token = ?';
        $stm = $this->con->prepare($sql);
        $stm->bindValue(1, $password);
        $stm->bindValue(2, $token);
        $stm->execute();
        //$data = $stm->fetch(PDO::FETCH_ASSOC);
        $rowCount = $stm->rowCount();
        return $rowCount;
    }
}
