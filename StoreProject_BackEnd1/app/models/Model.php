<?php
class Model
{
    // Bước 2: Tạo connection
    public static $connection = NULL;
    public function  __construct() {
        if (!self::$connection) {
            self::$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            self::$connection -> set_charset('utf8mb4');
        }
        return self::$connection;
    }

    public function select($sql)
    {
        $sql -> execute(); // Thực thi câu sql
        // Thực thi kết quả trả về
        $items = $sql -> get_result() -> fetch_all(MYSQLI_ASSOC);
        return $items;  
    }
}