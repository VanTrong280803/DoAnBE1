<?php
class CategoryModel extends Model
{
    public function getAllCategory()
    {
        $sql = parent::$connection->prepare('SELECT * FROM categories');
        return parent::select($sql);
    }

    public function getCategoryIdByProductId($id)
    {
        $sql = parent::$connection->prepare('SELECT products_categories.category_id FROM products INNER JOIN products_categories ON products.id = products_categories.product_id WHERE product_id=?');
        $sql->bind_param('i', $id);
        return parent::select($sql)[0];
    }

    public function getCategoryName($id)
    {
        $sql = parent::$connection->prepare('SELECT category_name FROM categories WHERE id=?');
        $sql->bind_param('i', $id);
        return parent::select($sql)[0];
    }

    public function addCategory($categoryId, $productId)
    {
        $sql = parent::$connection->prepare('INSERT INTO `products_categories`(`product_id`, `category_id`) VALUE(?, ?)');
        $sql->bind_param('ii', $productId, $categoryId);
        return $sql->execute();
    }

    public function updateCategory($categoryId, $productId)
    {
        $sql = parent::$connection->prepare('UPDATE products_categories SET `category_id` = ? WHERE `product_id` = ?');
        $sql->bind_param('ii', $categoryId, $productId);
        return $sql->execute();
    }

    public function deleteProductInCategory($productId)
    {
        $sql = parent::$connection->prepare('DELETE FROM products_categories WHERE `product_id` = ?');
        $sql->bind_param('i', $productId);
        return $sql->execute();
    }
    public function getProductsByCategoryAndKeyword($categoryId = 0, $keyword = '')
    {
        $stmt = parent::$connection->prepare("
            SELECT products.* 
            FROM products 
            INNER JOIN products_categories ON products.id = products_categories.product_id
            WHERE products_categories.category_id = COALESCE(?, products_categories.category_id)
            AND products.product_name LIKE COALESCE(CONCAT('%', ?, '%'), products.product_name)
        ");

        $stmt->execute([$categoryId, $keyword]);

        return $stmt->get_result();
    }

}