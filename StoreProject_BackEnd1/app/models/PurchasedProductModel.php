<?php
class PurchasedProductModel extends Model
{
    public function getProductPurchased($username)
    {
        $sql = parent::$connection->prepare('SELECT * FROM `purchased_product` INNER JOIN `products` ON purchased_product.product_id = products.id WHERE `username` = ?');
        $sql->bind_param('s', $username);
        return parent::select($sql);
    }

    public function addProductPurchased($username, $productId)
    {
        $sql = parent::$connection->prepare("INSERT INTO `purchased_product`(`username`, `product_id`) VALUES (?, ?)");
        $sql->bind_param('si', $username, $productId);
        return $sql->execute();
    }
}
