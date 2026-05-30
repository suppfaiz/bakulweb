<?php

class AnalysisModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getFastMovingProducts($limit = 5) {
        $this->db->query("SELECT 
            p.id as product_id,
            p.name as product_name,
            p.slug as product_slug,
            c.name as category_name,
            b.name as brand_name,
            COALESCE(SUM(oi.qty), 0) as total_qty_sold,
            COALESCE(SUM(oi.qty * oi.price), 0) as total_revenue,
            COALESCE(SUM(pv.stock), 0) as total_stock,
            (SELECT image_path FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, id ASC LIMIT 1) as primary_image
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN brands b ON p.brand_id = b.id
        JOIN product_variants pv ON pv.product_id = p.id
        LEFT JOIN order_items oi ON oi.product_variant_id = pv.id
        LEFT JOIN orders o ON oi.order_id = o.id AND o.payment_status = 'paid'
        GROUP BY p.id
        HAVING total_qty_sold > 0
        ORDER BY total_qty_sold DESC, total_revenue DESC
        LIMIT :limit");
        
        $this->db->bind(':limit', (int)$limit);
        return $this->db->resultSet();
    }

    public function getSlowMovingProducts($limit = 5) {
        $this->db->query("SELECT 
            p.id as product_id,
            p.name as product_name,
            p.slug as product_slug,
            c.name as category_name,
            b.name as brand_name,
            COALESCE(SUM(oi.qty), 0) as total_qty_sold,
            COALESCE(SUM(pv.stock), 0) as total_stock,
            (SELECT image_path FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, id ASC LIMIT 1) as primary_image
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN brands b ON p.brand_id = b.id
        JOIN product_variants pv ON pv.product_id = p.id
        LEFT JOIN order_items oi ON oi.product_variant_id = pv.id
        LEFT JOIN orders o ON oi.order_id = o.id AND o.payment_status = 'paid'
        GROUP BY p.id
        HAVING total_stock > 0
        ORDER BY total_qty_sold ASC, total_stock DESC
        LIMIT :limit");
        
        $this->db->bind(':limit', (int)$limit);
        return $this->db->resultSet();
    }

    public function getMonthlySalesProgress() {
        // Calculate paid sales for current month
        $this->db->query("SELECT COALESCE(SUM(total_amount), 0) as current_sales 
                          FROM orders 
                          WHERE payment_status = 'paid' 
                            AND DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')");
        $res = $this->db->single();
        return $res ? (float)$res['current_sales'] : 0.0;
    }

    public function getOutOfStockAlerts() {
        $this->db->query("SELECT 
            p.id as product_id,
            p.name as product_name,
            p.slug as product_slug,
            COALESCE(SUM(pv.stock), 0) as total_stock,
            COALESCE(SUM(oi.qty), 0) as total_qty_sold
        FROM products p
        JOIN product_variants pv ON pv.product_id = p.id
        LEFT JOIN order_items oi ON oi.product_variant_id = pv.id
        LEFT JOIN orders o ON oi.order_id = o.id AND o.payment_status = 'paid'
        GROUP BY p.id
        HAVING total_stock <= 5 AND total_qty_sold > 0
        ORDER BY total_qty_sold DESC
        LIMIT 5");
        return $this->db->resultSet();
    }

    public function getBundlingSuggestions() {
        // Fetch all products with category data
        $fast = $this->getFastMovingProducts(10);
        $slow = $this->getSlowMovingProducts(10);
        
        $suggestions = [];
        
        // Loop through fast moving products and pair them with slow moving products in the same category
        foreach ($fast as $f) {
            foreach ($slow as $s) {
                if ($f['category_name'] === $s['category_name'] && $f['product_id'] !== $s['product_id']) {
                    $suggestions[] = [
                        'fast_product' => $f,
                        'slow_product' => $s,
                        'category' => $f['category_name'],
                        'discount_pct' => 10,
                        'reason' => "Tingkatkan penjualan '" . $s['product_name'] . "' dengan membundelnya bersama produk terlaris '" . $f['product_name'] . "'."
                    ];
                    break; // Suggest only one pair per fast product to keep it neat
                }
            }
        }
        
        return array_slice($suggestions, 0, 3);
    }
}
