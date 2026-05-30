<?php

class OrderModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function createOrder($data) {
        $type = $data['type'] ?? 'online';
        $voucher_code = $data['voucher_code'] ?? null;
        $discount_amount = $data['discount_amount'] ?? 0.00;
        
        $this->db->query("INSERT INTO orders 
            (invoice, user_id, total_amount, shipping_cost, status, payment_status, payment_method, type,
             recipient_name, recipient_phone, shipping_address, courier, courier_service, voucher_code, discount_amount) 
            VALUES 
            (:invoice, :user_id, :total_amount, :shipping_cost, 'pending', 'unpaid', :payment_method, :type,
             :recipient_name, :recipient_phone, :shipping_address, :courier, :courier_service, :voucher_code, :discount_amount)");
        $this->db->bind('invoice', $data['invoice']);
        $this->db->bind('user_id', $data['user_id']);
        $this->db->bind('total_amount', $data['total_amount']);
        $this->db->bind('shipping_cost', $data['shipping_cost'] ?? 0);
        $this->db->bind('payment_method', $data['payment_method'] ?? 'Transfer Bank');
        $this->db->bind('type', $type);
        $this->db->bind('recipient_name', $data['recipient_name'] ?? null);
        $this->db->bind('recipient_phone', $data['recipient_phone'] ?? null);
        $this->db->bind('shipping_address', $data['shipping_address'] ?? null);
        $this->db->bind('courier', $data['courier'] ?? 'JNE');
        $this->db->bind('courier_service', $data['courier_service'] ?? 'REG');
        $this->db->bind('voucher_code', $voucher_code);
        $this->db->bind('discount_amount', $discount_amount);
        $this->db->execute();
        
        return $this->db->lastInsertId();
    }

    public function addOrderItem($order_id, $variant_id, $qty, $price) {
        // Fetch purchase price from variant
        $this->db->query("SELECT purchase_price FROM product_variants WHERE id = :variant_id");
        $this->db->bind('variant_id', $variant_id);
        $res = $this->db->single();
        $purchase_price = $res ? $res['purchase_price'] : 0.00;

        $this->db->query("INSERT INTO order_items (order_id, product_variant_id, qty, price, purchase_price) 
                          VALUES (:order_id, :variant_id, :qty, :price, :purchase_price)");
        $this->db->bind('order_id', $order_id);
        $this->db->bind('variant_id', $variant_id);
        $this->db->bind('qty', $qty);
        $this->db->bind('price', $price);
        $this->db->bind('purchase_price', $purchase_price);
        return $this->db->execute();
    }

    public function decreaseStock($variant_id, $qty) {
        $this->db->query("UPDATE product_variants SET stock = stock - :qty WHERE id = :variant_id AND stock >= :qty");
        $this->db->bind('variant_id', $variant_id);
        $this->db->bind('qty', $qty);
        return $this->db->execute();
    }

    public function updatePaymentStatus($invoice, $status, $midtrans_id = null) {
        $this->db->query("UPDATE orders SET payment_status = :status, midtrans_trx_id = :midtrans_id, status = 'processing' WHERE invoice = :invoice");
        $this->db->bind('status', $status);
        $this->db->bind('midtrans_id', $midtrans_id);
        $this->db->bind('invoice', $invoice);
        return $this->db->execute();
    }

    public function getOrdersByUser($user_id) {
        $this->db->query("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $this->db->bind('user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getAllOrders($filters = []) {
        $query = "SELECT o.*, u.username as customer_name, u.email as customer_email 
                  FROM orders o 
                  JOIN users u ON o.user_id = u.id 
                  WHERE 1=1 ";
                  
        if(!empty($filters['status'])) {
            if($filters['status'] == 'perlu_dikirim') {
                $query .= " AND (o.status = 'pending' OR o.status = 'processing') AND o.payment_status = 'paid' ";
            } else if($filters['status'] == 'selesai') {
                $query .= " AND o.status = 'completed' ";
            }
        }
        
        if(!empty($filters['search'])) {
            $query .= " AND o.invoice LIKE :search ";
        }

        $query .= " ORDER BY o.created_at DESC";
        
        $this->db->query($query);
        
        if(!empty($filters['search'])) {
            $this->db->bind('search', '%' . $filters['search'] . '%');
        }
        
        return $this->db->resultSet();
    }

    public function updateOrderStatus($id, $status) {
        $this->db->query("UPDATE orders SET status = :status WHERE id = :id");
        $this->db->bind('status', $status);
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    public function getOrderById($id) {
        $this->db->query("SELECT o.*, u.username as customer_name, u.email as customer_email 
                          FROM orders o 
                          LEFT JOIN users u ON o.user_id = u.id 
                          WHERE o.id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getOrderItems($order_id) {
        $this->db->query("SELECT oi.*, pv.sku, pv.product_id, p.name as product_name, CONCAT(pv.storage, ' - ', pv.color) as variant_name 
                          FROM order_items oi 
                          JOIN product_variants pv ON oi.product_variant_id = pv.id 
                          JOIN products p ON pv.product_id = p.id 
                          WHERE oi.order_id = :order_id");
        $this->db->bind('order_id', $order_id);
        return $this->db->resultSet();
    }

    public function getDashboardStats() {
        // Total Pendapatan dari order yang paid
        $this->db->query("SELECT SUM(total_amount) as revenue FROM orders WHERE payment_status = 'paid'");
        $revenue = $this->db->single()['revenue'] ?? 0;

        // Total Pesanan baru (status pending atau processing)
        $this->db->query("SELECT COUNT(*) as count FROM orders WHERE status IN ('pending', 'processing')");
        $newOrders = $this->db->single()['count'] ?? 0;

        return [
            'revenue' => $revenue,
            'new_orders' => $newOrders
        ];
    }

    public function getRecentActivities() {
        $this->db->query("SELECT o.*, u.username as customer_name 
                          FROM orders o 
                          JOIN users u ON o.user_id = u.id 
                          ORDER BY o.created_at DESC LIMIT 5");
        return $this->db->resultSet();
    }

    public function getFinanceReport($period = '30') {
        $date_from = date('Y-m-d 00:00:00', strtotime("-{$period} days"));
        
        // 1. Fetch Revenue stats — akurat: pisahkan ongkir dan diskon
        $this->db->query("SELECT 
            COUNT(*) as total_transaksi,
            COUNT(CASE WHEN payment_status = 'paid' THEN 1 END) as jumlah_lunas,
            SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) as gross_revenue,
            SUM(CASE WHEN payment_status = 'paid' THEN COALESCE(shipping_cost, 0) ELSE 0 END) as shipping_revenue,
            SUM(CASE WHEN payment_status = 'paid' THEN COALESCE(discount_amount, 0) ELSE 0 END) as total_discount
            FROM orders 
            WHERE created_at >= :date_from");
        $this->db->bind('date_from', $date_from);
        $revenueData = $this->db->single();
        
        $gross_revenue    = (float)($revenueData['gross_revenue'] ?? 0);    // Total bayar pelanggan (incl. ongkir)
        $shipping_revenue = (float)($revenueData['shipping_revenue'] ?? 0); // Bagian ongkos kirim
        $total_discount   = (float)($revenueData['total_discount'] ?? 0);   // Diskon voucher
        $jumlah_lunas     = (int)($revenueData['jumlah_lunas'] ?? 0);
        $total_transaksi  = (int)($revenueData['total_transaksi'] ?? 0);

        // Revenue bersih = total bayar - ongkir (pass-through) - diskon
        // Ongkir bukan pendapatan bisnis, diskon mengurangi pendapatan
        $nett_revenue = $gross_revenue - $shipping_revenue - $total_discount;

        // 2. Fetch COGS (Harga Beli) — hanya dari order paid, exclude cancelled
        $this->db->query("SELECT SUM(oi.purchase_price * oi.qty) as total_cogs 
                          FROM order_items oi
                          JOIN orders o ON oi.order_id = o.id
                          WHERE o.payment_status = 'paid'
                            AND o.status NOT IN ('cancelled', 'refunded')
                            AND o.created_at >= :date_from");
        $this->db->bind('date_from', $date_from);
        $cogsData = $this->db->single();
        $total_cogs = (float)($cogsData['total_cogs'] ?? 0);

        // 3. Fetch Beban Operasional
        $date_from_only = date('Y-m-d', strtotime("-{$period} days"));
        $this->db->query("SELECT SUM(amount) as total_expenses 
                          FROM expenses 
                          WHERE date >= :date_from");
        $this->db->bind('date_from', $date_from_only);
        $expenseData = $this->db->single();
        $total_expenses = (float)($expenseData['total_expenses'] ?? 0);

        // Gross Profit = Revenue bersih - COGS
        $gross_profit = $nett_revenue - $total_cogs;
        // Nett Profit = Gross Profit - Beban Operasional
        $nett_profit = $gross_profit - $total_expenses;

        return [
            'pemasukan'        => $gross_revenue,   // backward-compat alias
            'gross_revenue'    => $gross_revenue,
            'shipping_revenue' => $shipping_revenue,
            'total_discount'   => $total_discount,
            'nett_revenue'     => $nett_revenue,
            'jumlah_lunas'     => $jumlah_lunas,
            'total_transaksi'  => $total_transaksi,
            'total_cogs'       => $total_cogs,
            'gross_profit'     => $gross_profit,
            'total_expenses'   => $total_expenses,
            'nett_profit'      => $nett_profit
        ];
    }
    
    public function getFinanceReportAll() {
        // 1. Fetch Revenue stats — akurat termasuk pemisahan ongkir dan diskon
        $this->db->query("SELECT 
            COUNT(CASE WHEN payment_status = 'paid' THEN 1 END) as total_lunas,
            COUNT(*) as total_semua,
            SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) as gross_revenue,
            SUM(CASE WHEN payment_status = 'paid' THEN COALESCE(shipping_cost, 0) ELSE 0 END) as shipping_revenue,
            SUM(CASE WHEN payment_status = 'paid' THEN COALESCE(discount_amount, 0) ELSE 0 END) as total_discount
            FROM orders");
        $revenueData = $this->db->single();
        $gross_revenue    = (float)($revenueData['gross_revenue'] ?? 0);
        $shipping_revenue = (float)($revenueData['shipping_revenue'] ?? 0);
        $total_discount   = (float)($revenueData['total_discount'] ?? 0);
        $total_lunas = (int)($revenueData['total_lunas'] ?? 0);
        $total_semua = (int)($revenueData['total_semua'] ?? 0);
        $nett_revenue = $gross_revenue - $shipping_revenue - $total_discount;

        // 2. Fetch COGS — exclude cancelled/refunded
        $this->db->query("SELECT SUM(oi.purchase_price * oi.qty) as total_cogs 
                          FROM order_items oi
                          JOIN orders o ON oi.order_id = o.id
                          WHERE o.payment_status = 'paid'
                            AND o.status NOT IN ('cancelled', 'refunded')");
        $cogsData = $this->db->single();
        $total_cogs = (float)($cogsData['total_cogs'] ?? 0);

        // 3. Fetch Expenses
        $this->db->query("SELECT SUM(amount) as total_expenses FROM expenses");
        $expenseData = $this->db->single();
        $total_expenses = (float)($expenseData['total_expenses'] ?? 0);

        $gross_profit = $nett_revenue - $total_cogs;
        $nett_profit = $gross_profit - $total_expenses;

        return [
            'total_pemasukan'  => $gross_revenue,   // backward-compat
            'gross_revenue'    => $gross_revenue,
            'shipping_revenue' => $shipping_revenue,
            'total_discount'   => $total_discount,
            'nett_revenue'     => $nett_revenue,
            'total_lunas'      => $total_lunas,
            'total_semua'      => $total_semua,
            'total_cogs'       => $total_cogs,
            'gross_profit'     => $gross_profit,
            'total_expenses'   => $total_expenses,
            'nett_profit'      => $nett_profit
        ];
    }
    
    public function getTransactionHistory($period = '30') {
        $date_from = date('Y-m-d', strtotime("-{$period} days"));
        $this->db->query("SELECT o.*, u.username as customer_name,
            (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.created_at >= :date_from
            ORDER BY o.created_at DESC
            LIMIT 50");
        $this->db->bind('date_from', $date_from);
        return $this->db->resultSet();
    }
    
    public function getMonthlyRevenue() {
        $this->db->query("SELECT 
            o_data.bulan,
            o_data.label,
            o_data.pemasukan,
            o_data.shipping_rev,
            o_data.diskon,
            o_data.jumlah_transaksi,
            COALESCE(cogs_data.total_cogs, 0) as total_cogs
            FROM (
                SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as bulan,
                    DATE_FORMAT(created_at, '%b %Y') as label,
                    SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) as pemasukan,
                    SUM(CASE WHEN payment_status = 'paid' THEN COALESCE(shipping_cost,0) ELSE 0 END) as shipping_rev,
                    SUM(CASE WHEN payment_status = 'paid' THEN COALESCE(discount_amount,0) ELSE 0 END) as diskon,
                    COUNT(CASE WHEN payment_status = 'paid' THEN 1 END) as jumlah_transaksi
                FROM orders
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%b %Y')
            ) o_data
            LEFT JOIN (
                SELECT 
                    DATE_FORMAT(o2.created_at, '%Y-%m') as bulan_cogs,
                    SUM(oi.purchase_price * oi.qty) as total_cogs
                FROM orders o2
                JOIN order_items oi ON oi.order_id = o2.id
                WHERE o2.payment_status = 'paid'
                  AND o2.status NOT IN ('cancelled', 'refunded')
                  AND o2.created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(o2.created_at, '%Y-%m')
            ) cogs_data ON o_data.bulan = cogs_data.bulan_cogs
            ORDER BY o_data.bulan ASC");
        $rows = $this->db->resultSet();
        // Compute nett_revenue and nett_profit per row
        foreach ($rows as &$row) {
            $row['nett_revenue'] = (float)$row['pemasukan'] - (float)$row['shipping_rev'] - (float)$row['diskon'];
            $row['nett_profit']  = $row['nett_revenue'] - (float)$row['total_cogs'];
        }
        return $rows;
    }
    
    public function getOrderByInvoice($invoice) {
        $this->db->query("SELECT o.*, u.username as customer_name, u.email as customer_email 
                          FROM orders o 
                          LEFT JOIN users u ON o.user_id = u.id 
                          WHERE o.invoice = :invoice");
        $this->db->bind('invoice', $invoice);
        return $this->db->single();
    }
}
