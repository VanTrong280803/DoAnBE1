<?php
class AccountModel extends Model
{
    public function getAllUsernameByUser()
    {
        $sql = parent::$connection -> prepare('SELECT `username` FROM account_user');
        return parent::select($sql);        
    }

    public function getAllUsernameByAdmin()
    {
        $sql = parent::$connection -> prepare('SELECT `username` FROM account_admin');
        return parent::select($sql); 
    }

    public function loginAdmin($username, $password)
    {
        $sql = parent::$connection->prepare('SELECT * FROM account_admin WHERE username=?');
        $sql -> bind_param('s', $username);
        $userName = parent::select($sql)[0];
        if(password_verify($password, $userName['password']))
        {
            return true;
        } 
        return false;
    }

    public function registerAdmin($username, $password)
    {
        $sql = parent::$connection->prepare('INSERT INTO account_admin(`username`, `password`) VALUES (?, ?)');
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql->bind_param('ss', $username, $password);
        return $sql->execute();
    }

    public function loginUser($username, $password)
    {
        $sql = parent::$connection->prepare('SELECT * FROM account_user WHERE username=?');
        $sql -> bind_param('s', $username);
        $userName = parent::select($sql)[0];
        if(password_verify($password, $userName['password']))
        {
            return true;
        } 
        return false;
    }

    public function registerUser($username, $password)
    {
        $sql = parent::$connection->prepare('INSERT INTO account_user(`username`, `password`) VALUES (?, ?)');
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql->bind_param('ss', $username, $password);
        return $sql->execute();
    }

    public function changePasswordForUser($username, $password)
    {
        $sql = parent::$connection->prepare('UPDATE account_user SET `password` = ? where `username` = ?');
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql->bind_param('ss', $password, $username);
        return $sql->execute();
    }

    public function changePasswordForAdmin($adminAccountName, $password)
    {
        $sql = parent::$connection->prepare('UPDATE account_admin SET `password` = ? where `username` = ?');
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql->bind_param('ss', $password, $adminAccountName);
        return $sql->execute();
    }
}