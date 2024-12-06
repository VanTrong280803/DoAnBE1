<?php
class UserModel extends Model
{
    public function getInfoUserByUsername($username)
    {
        $sql = parent::$connection -> prepare('SELECT * FROM user WHERE username = ?');
        $sql->bind_param('s', $username);
        return parent::select($sql)[0];
    }

    public function addInfoUser($username, $fullname, $email)
    {
        $sql = parent::$connection->prepare('INSERT INTO `user`(`username`, `fullname`, `email`) VALUES (?, ?, ?)');
        $sql->bind_param('sss', $username, $fullname, $email);
        return $sql->execute();
    }

    public function editInfoUser($username, $fullname, $email, $phone, $avatar)
    {
        if(is_null($avatar))
        {
            $sql = parent::$connection->prepare('UPDATE user SET `fullname`=?,`email`=?,`phone`=? WHERE `username`=?');
            $sql->bind_param('ssss', $fullname, $email, $phone, $username);
            return $sql->execute();
        }
        else
        {
            $sql = parent::$connection->prepare('UPDATE user SET `fullname`=?,`email`=?,`phone`=?, `avatar`=? WHERE `username`=?');
            $sql->bind_param('sssss', $fullname, $email, $phone, $avatar, $username);
            return $sql->execute();
        }
    }
}