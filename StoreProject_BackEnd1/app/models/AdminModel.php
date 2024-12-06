<?php
class AdminModel extends Model
{
    public function getInfoAdminByUsername($username)
    {
        $sql = parent::$connection -> prepare("SELECT * FROM `admin` WHERE username = ?");
        $sql->bind_param('s', $username);
        return parent::select($sql)[0];
    }

    public function addInfoAdmin($username, $fullname, $email)
    {
        $sql = parent::$connection->prepare('INSERT INTO `admin`(`username`, `fullname`, `email`) VALUES (?, ?, ?)');
        $sql->bind_param('sss', $username, $fullname, $email);
        return $sql->execute();
    }

    public function editInfoUser($username, $fullname, $email)
    {
        $sql = parent::$connection->prepare('UPDATE user SET `fullname`=?,`email`=? WHERE `username`=?');
        $sql->bind_param('sss', $fullname, $email, $username);
        return $sql->execute();
    }
}