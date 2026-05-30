<?php

class ProductModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllProducts() {
        // Gabungkan dengan varian dan gambar utama serta review
        $query = "SELECT p.id, p.name, p.slug, b.name as brand_name, c.name as category_name, 
                          (SELECT IF(pv.flash_sale_price IS NOT NULL AND pv.flash_sale_end > NOW(), pv.flash_sale_price, pv.price) 
                           FROM product_variants pv 
                           WHERE pv.product_id = p.id 
                           ORDER BY (pv.flash_sale_price IS NOT NULL AND pv.flash_sale_end > NOW()) DESC, IF(pv.flash_sale_price IS NOT NULL AND pv.flash_sale_end > NOW(), pv.flash_sale_price, pv.price) ASC LIMIT 1) as starting_price,
                          (SELECT pv.price 
                           FROM product_variants pv 
                           WHERE pv.product_id = p.id 
                           ORDER BY (pv.flash_sale_price IS NOT NULL AND pv.flash_sale_end > NOW()) DESC, IF(pv.flash_sale_price IS NOT NULL AND pv.flash_sale_end > NOW(), pv.flash_sale_price, pv.price) ASC LIMIT 1) as original_starting_price,
                          (SELECT MAX(IF(pv.flash_sale_price IS NOT NULL AND pv.flash_sale_end > NOW(), 1, 0)) 
                           FROM product_variants pv 
                           WHERE pv.product_id = p.id) as has_flash_sale,
                          (SELECT MIN(IF(pv.flash_sale_price IS NOT NULL AND pv.flash_sale_end > NOW(), pv.flash_sale_end, NULL)) 
                           FROM product_variants pv 
                           WHERE pv.product_id = p.id) as flash_sale_end,
                          (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image,
                          AVG(pr.rating) as avg_rating,
                          COUNT(pr.id) as review_count
                  FROM products p
                  LEFT JOIN brands b ON p.brand_id = b.id
                  LEFT JOIN categories c ON p.category_id = c.id
                  LEFT JOIN product_reviews pr ON p.id = pr.product_id
                  GROUP BY p.id
                  ORDER BY p.created_at DESC";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getAllCategories() {
        $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $this->db->resultSet();
    }
    
    public function getAllBrands() {
        $this->db->query("SELECT * FROM brands ORDER BY name ASC");
        return $this->db->resultSet();
    }

    public function addCategory($name, $slug) {
        $this->db->query("INSERT INTO categories (name, slug) VALUES (:name, :slug)");
        $this->db->bind('name', $name);
        $this->db->bind('slug', $slug);
        return $this->db->execute();
    }

    public function addBrand($name, $slug) {
        $this->db->query("INSERT INTO brands (name, slug) VALUES (:name, :slug)");
        $this->db->bind('name', $name);
        $this->db->bind('slug', $slug);
        return $this->db->execute();
    }

    public function filterProducts($filters) {
        $query = "SELECT p.id, p.name, p.slug, b.name as brand_name, c.name as category_name, 
                          (SELECT IF(pv.flash_sale_price IS NOT NULL AND pv.flash_sale_end > NOW(), pv.flash_sale_price, pv.price) 
                           FROM product_variants pv 
                           WHERE pv.product_id = p.id 
                           ORDER BY (pv.flash_sale_price IS NOT NULL AND pv.flash_sale_end > NOW()) DESC, IF(pv.flash_sale_price IS NOT NULL AND pv.flash_sale_end > NOW(), pv.flash_sale_price, pv.price) ASC LIMIT 1) as starting_price,
                          (SELECT pv.price 
                           FROM product_variants pv 
                           WHERE pv.product_id = p.id 
                           ORDER BY (pv.flash_sale_price IS NOT NULL AND pv.flash_sale_end > NOW()) DESC, IF(pv.flash_sale_price IS NOT NULL AND pv.flash_sale_end > NOW(), pv.flash_sale_price, pv.price) ASC LIMIT 1) as original_starting_price,
                          (SELECT MAX(IF(pv.flash_sale_price IS NOT NULL AND pv.flash_sale_end > NOW(), 1, 0)) 
                           FROM product_variants pv 
                           WHERE pv.product_id = p.id) as has_flash_sale,
                          (SELECT MIN(IF(pv.flash_sale_price IS NOT NULL AND pv.flash_sale_end > NOW(), pv.flash_sale_end, NULL)) 
                           FROM product_variants pv 
                           WHERE pv.product_id = p.id) as flash_sale_end,
                          (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image,
                          AVG(pr.rating) as avg_rating,
                          COUNT(pr.id) as review_count
                  FROM products p
                  LEFT JOIN brands b ON p.brand_id = b.id
                  LEFT JOIN categories c ON p.category_id = c.id
                  LEFT JOIN product_reviews pr ON p.id = pr.product_id
                  WHERE 1=1 ";
                  
        if(!empty($filters['category'])) {
            if (is_array($filters['category'])) {
                $placeholders = [];
                foreach ($filters['category'] as $key => $val) {
                    $placeholders[] = ":cat_" . $key;
                }
                $query .= " AND p.category_id IN (" . implode(',', $placeholders) . ") ";
            } else {
                $query .= " AND p.category_id = :category ";
            }
        }
        if(!empty($filters['brand'])) {
            if (is_array($filters['brand'])) {
                $placeholders = [];
                foreach ($filters['brand'] as $key => $val) {
                    $placeholders[] = ":brand_" . $key;
                }
                $query .= " AND p.brand_id IN (" . implode(',', $placeholders) . ") ";
            } else {
                $query .= " AND p.brand_id = :brand ";
            }
        }
        if(!empty($filters['search'])) {
            $query .= " AND p.name LIKE :search ";
        }
        
        $query .= " GROUP BY p.id ";
        
        $sort = $filters['sort'] ?? 'latest';
        if ($sort === 'price_asc') {
            $query .= " ORDER BY starting_price ASC ";
        } elseif ($sort === 'price_desc') {
            $query .= " ORDER BY starting_price DESC ";
        } else {
            $query .= " ORDER BY p.created_at DESC ";
        }
        
        $this->db->query($query);
        
        if(!empty($filters['category'])) {
            if (is_array($filters['category'])) {
                foreach ($filters['category'] as $key => $val) {
                    $this->db->bind("cat_" . $key, $val);
                }
            } else {
                $this->db->bind('category', $filters['category']);
            }
        }
        if(!empty($filters['brand'])) {
            if (is_array($filters['brand'])) {
                foreach ($filters['brand'] as $key => $val) {
                    $this->db->bind("brand_" . $key, $val);
                }
            } else {
                $this->db->bind('brand', $filters['brand']);
            }
        }
        if(!empty($filters['search'])) {
            $this->db->bind('search', '%' . $filters['search'] . '%');
        }
        
        return $this->db->resultSet();
    }

    public function getProductBySlug($slug) {
        $this->db->query("SELECT p.*, b.name as brand_name, c.name as category_name 
                          FROM products p 
                          LEFT JOIN brands b ON p.brand_id = b.id 
                          LEFT JOIN categories c ON p.category_id = c.id 
                          WHERE p.slug = :slug");
        $this->db->bind('slug', $slug);
        return $this->db->single();
    }

    public function getProductVariants($product_id) {
        $this->db->query("SELECT * FROM product_variants WHERE product_id = :product_id");
        $this->db->bind('product_id', $product_id);
        $rows = $this->db->resultSet();
        if ($rows) {
            foreach ($rows as &$row) {
                $row['original_price'] = $row['price'];
                if ($row['flash_sale_price'] !== null && strtotime($row['flash_sale_end']) > time()) {
                    $row['price'] = $row['flash_sale_price'];
                    $row['is_flash_sale'] = true;
                } else {
                    $row['is_flash_sale'] = false;
                }
            }
        }
        return $rows;
    }

    public function getProductImages($product_id) {
        $this->db->query("SELECT * FROM product_images WHERE product_id = :product_id ORDER BY is_primary DESC");
        $this->db->bind('product_id', $product_id);
        return $this->db->resultSet();
    }

    public function addProduct($data) {
        $this->db->query("INSERT INTO products (category_id, brand_id, name, slug, description) 
                          VALUES (:category_id, :brand_id, :name, :slug, :description)");
        $this->db->bind('category_id', $data['category_id']);
        $this->db->bind('brand_id', $data['brand_id']);
        $this->db->bind('name', $data['name']);
        $this->db->bind('slug', $data['slug']);
        $this->db->bind('description', $data['description']);
        $this->db->execute();
        
        return $this->db->lastInsertId();
    }

    public function addProductVariant($product_id, $sku, $storage, $color, $price, $stock, $purchase_price = null) {
        if ($purchase_price === null || $purchase_price === '') {
            $purchase_price = (float)$price * 0.70;
        }
        $this->db->query("INSERT INTO product_variants (product_id, sku, storage, color, price, stock, purchase_price) 
                          VALUES (:product_id, :sku, :storage, :color, :price, :stock, :purchase_price)");
        $this->db->bind('product_id', $product_id);
        $this->db->bind('sku', $sku);
        $this->db->bind('storage', $storage);
        $this->db->bind('color', $color);
        $this->db->bind('price', $price);
        $this->db->bind('stock', $stock);
        $this->db->bind('purchase_price', $purchase_price);
        return $this->db->execute();
    }

    public function addProductImage($product_id, $image_path, $is_primary = 1) {
        $this->db->query("INSERT INTO product_images (product_id, image_path, is_primary) 
                          VALUES (:product_id, :image_path, :is_primary)");
        $this->db->bind('product_id', $product_id);
        $this->db->bind('image_path', $image_path);
        $this->db->bind('is_primary', $is_primary);
        return $this->db->execute();
    }

    public function getAllVariantsWithProduct() {
        $this->db->query("SELECT pv.*, p.name as product_name, p.slug 
                          FROM product_variants pv 
                          JOIN products p ON pv.product_id = p.id 
                          ORDER BY p.name ASC");
        return $this->db->resultSet();
    }

    public function getVariantDetails($variant_id) {
        $this->db->query("SELECT pv.*, p.name as product_name, p.slug,
                                 (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
                          FROM product_variants pv
                          JOIN products p ON pv.product_id = p.id
                          WHERE pv.id = :variant_id");
        $this->db->bind('variant_id', $variant_id);
        $row = $this->db->single();
        if ($row) {
            $row['original_price'] = $row['price'];
            if ($row['flash_sale_price'] !== null && strtotime($row['flash_sale_end']) > time()) {
                $row['price'] = $row['flash_sale_price'];
                $row['is_flash_sale'] = true;
            } else {
                $row['is_flash_sale'] = false;
            }
        }
        return $row;
    }

    public function addReview($userId, $productId, $orderId, $rating, $comment) {
        $this->db->query("INSERT INTO product_reviews (user_id, product_id, order_id, rating, comment) 
                          VALUES (:user_id, :product_id, :order_id, :rating, :comment)");
        $this->db->bind('user_id', $userId);
        $this->db->bind('product_id', $productId);
        $this->db->bind('order_id', $orderId);
        $this->db->bind('rating', $rating);
        $this->db->bind('comment', $comment);
        return $this->db->execute();
    }

    public function getReviewsByProduct($productId) {
        $this->db->query("SELECT pr.*, u.username as customer_name 
                          FROM product_reviews pr 
                          JOIN users u ON pr.user_id = u.id 
                          WHERE pr.product_id = :product_id 
                          ORDER BY pr.created_at DESC");
        $this->db->bind('product_id', $productId);
        return $this->db->resultSet();
    }

    public function getAverageRating($productId) {
        $this->db->query("SELECT AVG(rating) as avg_rating, COUNT(id) as review_count 
                          FROM product_reviews 
                          WHERE product_id = :product_id");
        $this->db->bind('product_id', $productId);
        return $this->db->single();
    }
    
    public function hasUserReviewedProduct($userId, $productId, $orderId) {
        $this->db->query("SELECT id FROM product_reviews 
                          WHERE user_id = :user_id AND product_id = :product_id AND order_id = :order_id");
        $this->db->bind('user_id', $userId);
        $this->db->bind('product_id', $productId);
        $this->db->bind('order_id', $orderId);
        return $this->db->single() ? true : false;
    }

    public function getLowStockCount() {
        $this->db->query("SELECT COUNT(*) as count FROM product_variants WHERE stock <= 5");
        return $this->db->single()['count'] ?? 0;
    }
    
    public function updateVariantStock($variant_id, $new_stock) {
        $this->db->query("UPDATE product_variants SET stock = :stock WHERE id = :id");
        $this->db->bind('stock', $new_stock);
        $this->db->bind('id', $variant_id);
        return $this->db->execute();
    }
    
    public function getVariantById($variant_id) {
        $this->db->query("SELECT pv.*, p.name as product_name 
                          FROM product_variants pv
                          JOIN products p ON pv.product_id = p.id
                          WHERE pv.id = :id");
        $this->db->bind('id', $variant_id);
        $row = $this->db->single();
        if ($row) {
            $row['original_price'] = $row['price'];
            if ($row['flash_sale_price'] !== null && strtotime($row['flash_sale_end']) > time()) {
                $row['price'] = $row['flash_sale_price'];
                $row['is_flash_sale'] = true;
            } else {
                $row['is_flash_sale'] = false;
            }
        }
        return $row;
    }

    public function deleteProduct($id) {
        // Disable FK checks agar bisa hapus data terkait tanpa error constraint
        $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
        $this->db->execute();

        // 1. Hapus inventory logs milik varian produk ini
        $this->db->query("DELETE il FROM inventory_logs il
                          INNER JOIN product_variants pv ON il.product_variant_id = pv.id
                          WHERE pv.product_id = :product_id");
        $this->db->bind('product_id', $id);
        $this->db->execute();

        // 2. Hapus order_items yang mereferensi varian produk ini
        //    (order header/induk tetap tersimpan, hanya baris item yang dihapus)
        $this->db->query("DELETE oi FROM order_items oi
                          INNER JOIN product_variants pv ON oi.product_variant_id = pv.id
                          WHERE pv.product_id = :product_id");
        $this->db->bind('product_id', $id);
        $this->db->execute();

        // 3. Hapus varian produk
        $this->db->query("DELETE FROM product_variants WHERE product_id = :product_id");
        $this->db->bind('product_id', $id);
        $this->db->execute();

        // 4. Hapus gambar produk
        $this->db->query("DELETE FROM product_images WHERE product_id = :product_id");
        $this->db->bind('product_id', $id);
        $this->db->execute();

        // 5. Hapus produk utama
        $this->db->query("DELETE FROM products WHERE id = :id");
        $this->db->bind('id', $id);
        $result = $this->db->execute();

        // Re-enable FK checks
        $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
        $this->db->execute();

        return $result;
    }


    public function getProductById($id) {
        $this->db->query("SELECT * FROM products WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function updateProduct($id, $data) {
        $this->db->query("UPDATE products SET category_id = :category_id, brand_id = :brand_id, name = :name, slug = :slug, description = :description WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->bind('category_id', $data['category_id']);
        $this->db->bind('brand_id', $data['brand_id']);
        $this->db->bind('name', $data['name']);
        $this->db->bind('slug', $data['slug']);
        $this->db->bind('description', $data['description']);
        return $this->db->execute();
    }

    public function updateProductVariant($id, $sku, $storage, $color, $price, $stock, $flash_sale_price = null, $flash_sale_end = null, $purchase_price = null) {
        if ($purchase_price === null || $purchase_price === '') {
            $purchase_price = (float)$price * 0.70;
        }
        $this->db->query("UPDATE product_variants SET sku = :sku, storage = :storage, color = :color, price = :price, stock = :stock, flash_sale_price = :flash_sale_price, flash_sale_end = :flash_sale_end, purchase_price = :purchase_price WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->bind('sku', $sku);
        $this->db->bind('storage', $storage);
        $this->db->bind('color', $color);
        $this->db->bind('price', $price);
        $this->db->bind('stock', $stock);
        $this->db->bind('flash_sale_price', $flash_sale_price);
        $this->db->bind('flash_sale_end', $flash_sale_end);
        $this->db->bind('purchase_price', $purchase_price);
        return $this->db->execute();
    }
}
