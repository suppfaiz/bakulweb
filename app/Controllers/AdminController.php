<?php
class AdminController extends Controller {
    public function __construct() {
        // Khusus halaman login admin, skip auth check
        $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $isLoginPage = (strpos($url, '/admin/login') !== false || strpos($url, '/admin/process_login') !== false);

        if (!$isLoginPage) {
            require_once __DIR__ . '/../Helpers/Auth.php';
            Auth::requireAdminLogin();
        } else {
            require_once __DIR__ . '/../Helpers/Auth.php';
        }
    }

    // ─── Admin Login ──────────────────────────────────────────────────────────

    public function login() {
        // Init CSRF Token if not exists
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        // Jika admin sudah login, redirect ke dashboard
        if (Auth::isAdmin()) {
            header('Location: ' . BASEURL . '/admin');
            exit;
        }
        $data['judul'] = 'Login Admin | BAKUL Enterprise';
        $this->view('admin/login', $data);
    }


    public function process_login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/admin/login');
            exit;
        }

        // Validasi CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            Flasher::setFlash('Gagal', 'Token CSRF tidak valid!', 'red');
            header('Location: ' . BASEURL . '/admin/login');
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $userModel = $this->model('UserModel');
        $user = $userModel->getUserByEmail($email);

        $adminRoles = ['superadmin', 'admin', 'gudang'];

        if ($user && password_verify($password, $user['password'])) {
            if (!in_array($user['role'], $adminRoles)) {
                Flasher::setFlash('Akses Ditolak', 'Akun ini bukan akun admin/staf.', 'red');
                header('Location: ' . BASEURL . '/admin/login');
                exit;
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role']    = $user['role'];
            $_SESSION['name']    = $user['username'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['login_type'] = 'admin'; // penanda session admin

            Flasher::setFlash('Selamat Datang', 'Login berhasil, ' . $user['username'] . '!', 'green');
            header('Location: ' . BASEURL . '/admin');
            exit;
        } else {
            Flasher::setFlash('Login Gagal', 'Email atau Password salah!', 'red');
            header('Location: ' . BASEURL . '/admin/login');
            exit;
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASEURL . '/admin/login');
        exit;
    }

    public function index() {
        $data['judul'] = 'Dashboard Admin | BAKUL Enterprise';
        
        $orderModel = $this->model('OrderModel');
        $productModel = $this->model('ProductModel');
        $userModel = $this->model('UserModel');
        
        // Fetch stats
        $data['stats'] = $orderModel->getDashboardStats();
        $data['low_stock_count'] = $productModel->getLowStockCount();
        $data['customer_count'] = $userModel->getCustomerCount();
        $data['recent_activities'] = $orderModel->getRecentActivities();
        
        $this->view('admin/dashboard', $data);
    }

    public function products() {
        $data['judul'] = 'Manajemen Produk | BAKUL Enterprise';
        $productModel = $this->model('ProductModel');
        $data['products'] = $productModel->getAllProducts();
        $this->view('admin/products/index', $data);
    }
    
    public function product_create() {
        $data['judul'] = 'Tambah Produk Baru | BAKUL Enterprise';
        $productModel = $this->model('ProductModel');
        $data['categories'] = $productModel->getAllCategories();
        $data['brands'] = $productModel->getAllBrands();
        $this->view('admin/products/create', $data);
    }
    
    public function categories() {
        $data['judul'] = 'Manajemen Kategori & Merek | BAKUL Enterprise';
        $productModel = $this->model('ProductModel');
        $data['categories'] = $productModel->getAllCategories();
        $data['brands'] = $productModel->getAllBrands();
        $this->view('admin/categories/index', $data);
    }
    
    public function category_store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $slug = strtolower(str_replace(' ', '-', $name));
            $slug = preg_replace('/[^a-zA-Z0-9\-]/', '', $slug);
            $slug = preg_replace('/-+/', '-', $slug);
            $slug = trim($slug, '-');
            $this->model('ProductModel')->addCategory($name, $slug);
            Flasher::setFlash('Kategori berhasil', 'ditambahkan', 'success');
            header('Location: ' . BASEURL . '/admin/categories');
            exit;
        }
    }
    
    public function brand_store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $slug = strtolower(str_replace(' ', '-', $name));
            $slug = preg_replace('/[^a-zA-Z0-9\-]/', '', $slug);
            $slug = preg_replace('/-+/', '-', $slug);
            $slug = trim($slug, '-');
            $this->model('ProductModel')->addBrand($name, $slug);
            Flasher::setFlash('Merek berhasil', 'ditambahkan', 'success');
            header('Location: ' . BASEURL . '/admin/categories');
            exit;
        }
    }
    
    public function product_store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $slug = strtolower(str_replace(' ', '-', $name));
            $slug = preg_replace('/[^a-zA-Z0-9\-]/', '', $slug);
            $slug = preg_replace('/-+/', '-', $slug);
            $slug = trim($slug, '-');
            $category_id = $_POST['category_id'];
            $brand_id = $_POST['brand_id'];
            $description = $_POST['description'];
            
            $productModel = $this->model('ProductModel');
            
            // Simpan produk
            $product_id = $productModel->addProduct([
                'name' => $name,
                'slug' => $slug,
                'category_id' => $category_id,
                'brand_id' => $brand_id,
                'description' => $description
            ]);
            
            // Ambil data varian awal dari form POST
            $sku = $_POST['sku'] ?? 'SKU-'.$product_id;
            $storage = $_POST['storage'] ?? 'Default';
            $color = $_POST['color'] ?? 'Default';
            $price = (float)($_POST['price'] ?? 1000000);
            $purchase_price = !empty($_POST['purchase_price']) ? (float)$_POST['purchase_price'] : null;
            $stock = (int)($_POST['stock'] ?? 10);

            // Simpan varian awal
            $productModel->addProductVariant($product_id, $sku, $storage, $color, $price, $stock, $purchase_price);
            
            // Handle upload gambar jika ada
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = __DIR__ . "/../../public/uploads/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $file_name = time() . '_' . basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $file_name;
                
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $productModel->addProductImage($product_id, "uploads/" . $file_name, 1);
                }
            }
            
            Flasher::setFlash('berhasil', 'ditambahkan', 'success');
            header('Location: ' . BASEURL . '/admin/products');
            exit;
        }
    }

    public function inventory() {
        $data['judul'] = 'Inventory & Stock Opname | BAKUL Enterprise';
        $productModel = $this->model('ProductModel');
        $data['variants'] = $productModel->getAllVariantsWithProduct();
        $this->view('admin/inventory/index', $data);
    }

    public function pos() {
        $data['judul'] = 'Point of Sale (POS) | BAKUL Enterprise';
        $this->view('admin/pos/index', $data);
    }
    
    // API endpoint for POS search
    public function api_pos_search() {
        header('Content-Type: application/json');
        $query = $_GET['q'] ?? '';
        
        $productModel = $this->model('ProductModel');
        $variants = $productModel->getAllVariantsWithProduct();
        
        $results = [];
        foreach($variants as $v) {
            if(empty($query) || stripos($v['product_name'], $query) !== false || stripos($v['sku'], $query) !== false) {
                $variant_name = trim(($v['color'] ?? '') . ' ' . ($v['storage'] ?? ''));
                $results[] = [
                    'id' => $v['id'],
                    'name' => $v['product_name'] . ($variant_name ? ' - ' . $variant_name : ''),
                    'sku' => $v['sku'],
                    'price' => $v['price'],
                    'stock' => $v['stock']
                ];
            }
        }
        
        echo json_encode($results);
        exit;
    }
    
    // API endpoint for POS checkout
    public function api_pos_checkout() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $items = $input['items'] ?? [];
            $amount_paid = $input['amount_paid'] ?? 0;
            
            if(empty($items)) {
                echo json_encode(['success' => false, 'message' => 'Keranjang kosong']);
                exit;
            }
            
            $total_amount = 0;
            foreach($items as $item) {
                $total_amount += ($item['price'] * $item['qty']);
            }
            
            if($amount_paid < $total_amount) {
                echo json_encode(['success' => false, 'message' => 'Uang tidak cukup']);
                exit;
            }
            
            $orderModel = $this->model('OrderModel');
            $invoice = 'POS-' . date('YmdHis') . '-' . rand(100, 999);
            
            $order_id = $orderModel->createOrder([
                'invoice' => $invoice,
                'user_id' => $_SESSION['user_id'], // Admin as the user
                'total_amount' => $total_amount,
                'shipping_cost' => 0,
                'payment_method' => 'Cash',
                'type' => 'offline'
            ]);
            
            // Tandai lunas karena ini kasir offline
            $orderModel->updatePaymentStatus($invoice, 'paid');
            // Tandai selesai
            $orderModel->updateOrderStatus($order_id, 'completed');
            
            foreach($items as $item) {
                $orderModel->addOrderItem($order_id, $item['id'], $item['qty'], $item['price']);
                $orderModel->decreaseStock($item['id'], $item['qty']);
            }
            
            echo json_encode([
                'success' => true, 
                'invoice' => $invoice, 
                'change' => $amount_paid - $total_amount
            ]);
            exit;
        }
    }

    public function finance() {
        $data['judul'] = 'Finance & Laporan | BAKUL Enterprise';
        
        $period = $_GET['period'] ?? '30';
        $orderModel = $this->model('OrderModel');
        $reconciliationModel = $this->model('ReconciliationModel');
        $expenseModel = $this->model('ExpenseModel');
        
        $data['period'] = $period;
        $data['report'] = $orderModel->getFinanceReport($period);
        $data['report_all'] = $orderModel->getFinanceReportAll();
        $data['transactions'] = $orderModel->getTransactionHistory($period);
        $data['monthly'] = $orderModel->getMonthlyRevenue();
        
        // Load reconciliation data
        $data['unreconciled_statements'] = $reconciliationModel->getUnreconciledStatements();
        $data['unreconciled_orders'] = $reconciliationModel->getUnreconciledOrders();
        $data['all_statements'] = $reconciliationModel->getBankStatements();
        $data['reconciliation_summary'] = $reconciliationModel->getReconciliationSummary();
        
        // Load expense data
        $data['expenses'] = $expenseModel->getAllExpenses([
            'period' => $period,
            'category' => $_GET['expense_category'] ?? null,
            'search' => $_GET['expense_search'] ?? null
        ]);
        $data['expense_categories'] = $expenseModel->getCategories();
        
        $data['tab'] = $_GET['tab'] ?? 'report';
        
        $this->view('admin/finance/index', $data);
    }

    public function expense_store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'title' => trim($_POST['title']),
                'category' => $_POST['category'],
                'amount' => (float)$_POST['amount'],
                'date' => $_POST['date'],
                'description' => trim($_POST['description'] ?? '')
            ];
            
            $expenseModel = $this->model('ExpenseModel');
            if ($expenseModel->addExpense($data)) {
                Flasher::setFlash('berhasil', 'Beban pengeluaran berhasil dicatat', 'success');
            } else {
                Flasher::setFlash('gagal', 'Gagal mencatat beban pengeluaran', 'error');
            }
            header('Location: ' . BASEURL . '/admin/finance?tab=expenses');
            exit;
        }
    }

    public function expense_update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            $data = [
                'title' => trim($_POST['title']),
                'category' => $_POST['category'],
                'amount' => (float)$_POST['amount'],
                'date' => $_POST['date'],
                'description' => trim($_POST['description'] ?? '')
            ];
            
            $expenseModel = $this->model('ExpenseModel');
            if ($id && $expenseModel->updateExpense($id, $data)) {
                Flasher::setFlash('berhasil', 'Data pengeluaran berhasil diperbarui', 'success');
            } else {
                Flasher::setFlash('gagal', 'Gagal memperbarui data pengeluaran', 'error');
            }
            header('Location: ' . BASEURL . '/admin/finance?tab=expenses');
            exit;
        }
    }

    public function expense_delete($id) {
        $expenseModel = $this->model('ExpenseModel');
        if ($id && $expenseModel->deleteExpense($id)) {
            Flasher::setFlash('berhasil', 'Pengeluaran berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('gagal', 'Gagal menghapus pengeluaran', 'error');
        }
        header('Location: ' . BASEURL . '/admin/finance?tab=expenses');
        exit;
    }
    
    public function inventory_update_stock() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $variant_id = $_POST['variant_id'] ?? 0;
            $new_stock   = (int)($_POST['new_stock'] ?? 0);
            $note        = trim($_POST['note'] ?? '');
            
            if ($variant_id && $new_stock >= 0) {
                $productModel = $this->model('ProductModel');
                $productModel->updateVariantStock($variant_id, $new_stock);
                Flasher::setFlash('Stok berhasil', 'diperbarui via Stock Opname', 'success');
            } else {
                Flasher::setFlash('Gagal update', 'data tidak valid', 'error');
            }
            
            header('Location: ' . BASEURL . '/admin/inventory');
            exit;
        }
    }


    public function orders() {
        $data['judul'] = 'Manajemen Pesanan | BAKUL Enterprise';
        
        $filters = [
            'status' => $_GET['status'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];
        
        $orderModel = $this->model('OrderModel');
        $data['orders'] = $orderModel->getAllOrders($filters);
        $data['filters'] = $filters;
        
        $this->view('admin/orders/index', $data);
    }

    public function update_order_status() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order_id = $_POST['order_id'];
            $status = $_POST['status'];
            
            $orderModel = $this->model('OrderModel');
            $order = $orderModel->getOrderById($order_id);
            
            if ($order) {
                $orderModel->updateOrderStatus($order_id, $status);
                
                // Map status to dynamic message
                $title = 'Status Pesanan Diperbarui';
                $message = 'Pesanan Anda dengan invoice ' . $order['invoice'] . ' kini berstatus: ' . ucfirst($status) . '.';
                
                if ($status == 'processing') {
                    $title = 'Pesanan Sedang Diproses';
                    $message = 'Pesanan Anda ' . $order['invoice'] . ' sedang diproses dan dipersiapkan oleh toko.';
                } else if ($status == 'shipped') {
                    $title = 'Pesanan Telah Dikirim';
                    $message = 'Pesanan Anda ' . $order['invoice'] . ' telah diserahkan ke kurir logistik dan sedang dalam perjalanan.';
                } else if (in_array($status, ['completed', 'delivered'])) {
                    $title = 'Pesanan Selesai';
                    $message = 'Pesanan Anda ' . $order['invoice'] . ' telah berhasil diterima. Silakan berikan ulasan Anda!';
                }
                
                $notificationModel = $this->model('NotificationModel');
                $notificationModel->createNotification($order['user_id'], $title, $message);
            }
            
            Flasher::setFlash('Status pesanan berhasil', 'diperbarui', 'success');
            header('Location: ' . BASEURL . '/admin/orders');
            exit;
        }
    }

    public function order_detail($id = null) {
        if(!$id) {
            header('Location: ' . BASEURL . '/admin/orders');
            exit;
        }
        $data['judul'] = 'Detail Pesanan | BAKUL Enterprise';
        $orderModel = $this->model('OrderModel');
        $order = $orderModel->getOrderById($id);
        if(!$order) {
            Flasher::setFlash('gagal', 'Pesanan tidak ditemukan', 'error');
            header('Location: ' . BASEURL . '/admin/orders');
            exit;
        }
        $data['order'] = $order;
        $data['items'] = $orderModel->getOrderItems($id);
        $this->view('admin/orders/detail', $data);
    }

    public function product_edit($id) {
        $data['judul'] = 'Edit Produk | BAKUL Enterprise';
        
        $productModel = $this->model('ProductModel');
        $product = $productModel->getProductById($id);
        
        if (!$product) {
            Flasher::setFlash('gagal', 'Produk tidak ditemukan.', 'error');
            header('Location: ' . BASEURL . '/admin/products');
            exit;
        }
        
        $variants = $productModel->getProductVariants($id);
        $first_variant = $variants[0] ?? null;
        
        $data['product'] = $product;
        $data['first_variant'] = $first_variant;
        $data['categories'] = $productModel->getAllCategories();
        $data['brands'] = $productModel->getAllBrands();
        
        $this->view('admin/products/edit', $data);
    }

    public function product_update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $slug = strtolower(str_replace(' ', '-', $name));
            $slug = preg_replace('/[^a-zA-Z0-9\-]/', '', $slug);
            $slug = preg_replace('/-+/', '-', $slug);
            $slug = trim($slug, '-');
            $category_id = $_POST['category_id'];
            $brand_id = $_POST['brand_id'];
            $description = $_POST['description'];
            
            $productModel = $this->model('ProductModel');
            
            // Update produk
            $productModel->updateProduct($id, [
                'name' => $name,
                'slug' => $slug,
                'category_id' => $category_id,
                'brand_id' => $brand_id,
                'description' => $description
            ]);
            
            // Update varian pertama
            $variants = $productModel->getProductVariants($id);
            $first_variant = $variants[0] ?? null;
            
            $sku = $_POST['sku'] ?? '';
            $storage = $_POST['storage'] ?? '';
            $color = $_POST['color'] ?? '';
            $price = (float)($_POST['price'] ?? 0);
            $purchase_price = !empty($_POST['purchase_price']) ? (float)$_POST['purchase_price'] : null;
            $stock = (int)($_POST['stock'] ?? 0);
            
            $flash_sale_price = !empty($_POST['flash_sale_price']) ? (float)$_POST['flash_sale_price'] : null;
            $flash_sale_duration = !empty($_POST['flash_sale_duration']) ? (int)$_POST['flash_sale_duration'] : 0;
            
            $flash_sale_end = null;
            if ($flash_sale_price !== null && $flash_sale_duration > 0) {
                $flash_sale_end = date('Y-m-d H:i:s', time() + ($flash_sale_duration * 3600));
            }
            
            if ($first_variant) {
                // If the user modified the regular price, use $_POST['price']. 
                // The update function will store it in the database `price` column.
                $productModel->updateProductVariant($first_variant['id'], $sku, $storage, $color, $price, $stock, $flash_sale_price, $flash_sale_end, $purchase_price);
            } else {
                $productModel->addProductVariant($id, $sku, $storage, $color, $price, $stock, $purchase_price);
                // (Optional: handle flash sale on variant creation if needed, but variants are pre-created)
            }
            
            // Handle upload gambar baru jika ada
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = __DIR__ . "/../../public/uploads/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $file_name = time() . '_' . basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $file_name;
                
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    // Set all existing images for this product as not primary first
                    $db = new Database;
                    $db->query("UPDATE product_images SET is_primary = 0 WHERE product_id = :product_id");
                    $db->bind('product_id', $id);
                    $db->execute();
                    
                    $productModel->addProductImage($id, "uploads/" . $file_name, 1);
                }
            }
            
            Flasher::setFlash('berhasil', 'diperbarui', 'success');
            header('Location: ' . BASEURL . '/admin/products');
            exit;
        }
    }

    public function product_delete($id) {
        $productModel = $this->model('ProductModel');
        $productModel->deleteProduct($id);
        Flasher::setFlash('berhasil', 'dihapus', 'success');
        header('Location: ' . BASEURL . '/admin/products');
        exit;
    }

    // --- Banners / Promos Management ---
    public function promos() {
        $data['judul'] = 'Manajemen Banner Promo | BAKUL Enterprise';
        $promoModel = $this->model('PromoModel');
        $data['promos'] = $promoModel->getAllPromos();
        $this->view('admin/promos/index', $data);
    }

    public function promo_create() {
        $data['judul'] = 'Tambah Banner Promo Baru | BAKUL Enterprise';
        $this->view('admin/promos/create', $data);
    }

    public function promo_store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'];
            $subtitle = $_POST['subtitle'];
            $link_url = $_POST['link_url'];
            $sort_order = (int)$_POST['sort_order'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $image_path = '';

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "uploads/promos/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $file_name = time() . '_' . basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $file_name;
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_path = "uploads/promos/" . $file_name;
                }
            }

            if (empty($image_path)) {
                Flasher::setFlash('Gambar banner', 'wajib diunggah', 'error');
                header('Location: ' . BASEURL . '/admin/promo_create');
                exit;
            }

            $promoModel = $this->model('PromoModel');
            $promoModel->addPromo([
                'title' => $title,
                'subtitle' => $subtitle,
                'image_path' => $image_path,
                'link_url' => $link_url,
                'sort_order' => $sort_order,
                'is_active' => $is_active
            ]);

            Flasher::setFlash('Banner promo berhasil', 'ditambahkan', 'success');
            header('Location: ' . BASEURL . '/admin/promos');
            exit;
        }
    }

    public function promo_edit($id) {
        $data['judul'] = 'Sunting Banner Promo | BAKUL Enterprise';
        $promoModel = $this->model('PromoModel');
        $data['promo'] = $promoModel->getPromoById($id);
        if (!$data['promo']) {
            Flasher::setFlash('Promo tidak', 'ditemukan', 'error');
            header('Location: ' . BASEURL . '/admin/promos');
            exit;
        }
        $this->view('admin/promos/edit', $data);
    }

    public function promo_update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $promoModel = $this->model('PromoModel');
            $promo = $promoModel->getPromoById($id);
            if (!$promo) {
                Flasher::setFlash('Promo tidak', 'ditemukan', 'error');
                header('Location: ' . BASEURL . '/admin/promos');
                exit;
            }

            $title = $_POST['title'];
            $subtitle = $_POST['subtitle'];
            $link_url = $_POST['link_url'];
            $sort_order = (int)$_POST['sort_order'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $image_path = $promo['image_path']; // Default to old image

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "uploads/promos/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $file_name = time() . '_' . basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $file_name;
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    // Delete old image if it exists and is not one of the seeded defaults
                    if (!empty($promo['image_path']) && file_exists($promo['image_path']) && strpos($promo['image_path'], 'images/') === false) {
                        unlink($promo['image_path']);
                    }
                    $image_path = "uploads/promos/" . $file_name;
                }
            }

            $promoModel->updatePromo($id, [
                'title' => $title,
                'subtitle' => $subtitle,
                'image_path' => $image_path,
                'link_url' => $link_url,
                'sort_order' => $sort_order,
                'is_active' => $is_active
            ]);

            Flasher::setFlash('Banner promo berhasil', 'diperbarui', 'success');
            header('Location: ' . BASEURL . '/admin/promos');
            exit;
        }
    }

    public function promo_delete($id) {
        $promoModel = $this->model('PromoModel');
        $promo = $promoModel->getPromoById($id);
        if ($promo) {
            // Delete image file if it's not a seeded default
            if (!empty($promo['image_path']) && file_exists($promo['image_path']) && strpos($promo['image_path'], 'images/') === false) {
                unlink($promo['image_path']);
            }
            $promoModel->deletePromo($id);
            Flasher::setFlash('Banner promo berhasil', 'dihapus', 'success');
        } else {
            Flasher::setFlash('Promo tidak', 'ditemukan', 'error');
        }
        header('Location: ' . BASEURL . '/admin/promos');
        exit;
    }

    // --- Vouchers Management ---
    public function vouchers() {
        $data['judul'] = 'Manajemen Voucher Diskon | BAKUL Enterprise';
        $voucherModel = $this->model('VoucherModel');
        $data['vouchers'] = $voucherModel->getAllVouchers();
        $this->view('admin/vouchers/index', $data);
    }

    public function voucher_create() {
        $data['judul'] = 'Buat Voucher Diskon Baru | BAKUL Enterprise';
        $this->view('admin/vouchers/create', $data);
    }

    public function voucher_store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $code = strtoupper(trim($_POST['code']));
            $discount_type = $_POST['discount_type'];
            $discount_amount = (float)$_POST['discount_amount'];
            $min_spend = (float)$_POST['min_spend'];
            $expiry_date = $_POST['expiry_date'];
            $usage_limit = $_POST['usage_limit'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            $voucherModel = $this->model('VoucherModel');
            
            // Check if code already exists
            if ($voucherModel->getVoucherByCode($code)) {
                Flasher::setFlash('Kode voucher sudah', 'digunakan', 'error');
                header('Location: ' . BASEURL . '/admin/voucher_create');
                exit;
            }

            $voucherModel->addVoucher([
                'code' => $code,
                'discount_type' => $discount_type,
                'discount_amount' => $discount_amount,
                'min_spend' => $min_spend,
                'expiry_date' => $expiry_date,
                'usage_limit' => $usage_limit,
                'is_active' => $is_active
            ]);

            Flasher::setFlash('Voucher berhasil', 'dibuat', 'success');
            header('Location: ' . BASEURL . '/admin/vouchers');
            exit;
        }
    }

    public function voucher_edit($id) {
        $data['judul'] = 'Sunting Voucher Diskon | BAKUL Enterprise';
        $voucherModel = $this->model('VoucherModel');
        $data['voucher'] = $voucherModel->getVoucherById($id);
        if (!$data['voucher']) {
            Flasher::setFlash('Voucher tidak', 'ditemukan', 'error');
            header('Location: ' . BASEURL . '/admin/vouchers');
            exit;
        }
        $this->view('admin/vouchers/edit', $data);
    }

    public function voucher_update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $voucherModel = $this->model('VoucherModel');
            $voucher = $voucherModel->getVoucherById($id);
            if (!$voucher) {
                Flasher::setFlash('Voucher tidak', 'ditemukan', 'error');
                header('Location: ' . BASEURL . '/admin/vouchers');
                exit;
            }

            $code = strtoupper(trim($_POST['code']));
            $discount_type = $_POST['discount_type'];
            $discount_amount = (float)$_POST['discount_amount'];
            $min_spend = (float)$_POST['min_spend'];
            $expiry_date = $_POST['expiry_date'];
            $usage_limit = $_POST['usage_limit'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            // Check if code already exists for another voucher
            $existing = $voucherModel->getVoucherByCode($code);
            if ($existing && $existing['id'] != $id) {
                Flasher::setFlash('Kode voucher sudah', 'digunakan', 'error');
                header('Location: ' . BASEURL . '/admin/voucher_edit/' . $id);
                exit;
            }

            $voucherModel->updateVoucher($id, [
                'code' => $code,
                'discount_type' => $discount_type,
                'discount_amount' => $discount_amount,
                'min_spend' => $min_spend,
                'expiry_date' => $expiry_date,
                'usage_limit' => $usage_limit,
                'is_active' => $is_active
            ]);

            Flasher::setFlash('Voucher berhasil', 'diperbarui', 'success');
            header('Location: ' . BASEURL . '/admin/vouchers');
            exit;
        }
    }

    public function voucher_delete($id) {
        $voucherModel = $this->model('VoucherModel');
        $voucherModel->deleteVoucher($id);
        Flasher::setFlash('Voucher berhasil', 'dihapus', 'success');
        header('Location: ' . BASEURL . '/admin/vouchers');
        exit;
    }

    public function refunds() {
        Auth::requireRole(['superadmin', 'admin']);
        
        $data['judul'] = 'Manajemen Refund & Penarikan | Admin ERP';
        $refundModel = $this->model('RefundModel');
        
        $data['refunds'] = $refundModel->getAllRefundRequests();
        $data['withdrawals'] = $refundModel->getAllWithdrawals();
        
        $this->view('admin/refunds/index', $data);
    }
    
    public function update_refund_status() {
        Auth::requireRole(['superadmin', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? 'pending';
            $adminNotes = trim($_POST['admin_notes'] ?? '');
            
            $refundModel = $this->model('RefundModel');
            
            if ($refundModel->updateRefundStatus($id, $status, $adminNotes)) {
                Flasher::setFlash('Berhasil!', 'Status pengajuan refund berhasil diperbarui.', 'success');
            } else {
                Flasher::setFlash('Gagal:', 'Gagal memperbarui status refund.', 'error');
            }
            header('Location: ' . BASEURL . '/admin/refunds');
            exit;
        }
    }
    
    public function update_withdrawal_status() {
        Auth::requireRole(['superadmin', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? 'pending';
            $adminNotes = trim($_POST['admin_notes'] ?? '');
            
            $refundModel = $this->model('RefundModel');
            
            if ($refundModel->updateWithdrawalStatus($id, $status, $adminNotes)) {
                Flasher::setFlash('Berhasil!', 'Status penarikan dana berhasil diperbarui.', 'success');
            } else {
                Flasher::setFlash('Gagal:', 'Gagal memperbarui status penarikan.', 'error');
            }
            header('Location: ' . BASEURL . '/admin/refunds');
            exit;
        }
    }

    public function reconcile_match() {
        Auth::requireRole(['superadmin', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $statementId = intval($_POST['statement_id'] ?? 0);
            $orderId = intval($_POST['order_id'] ?? 0);
            
            $reconciliationModel = $this->model('ReconciliationModel');
            
            if ($reconciliationModel->reconcile($statementId, $orderId)) {
                Flasher::setFlash('Berhasil!', 'Mutasi bank berhasil dicocokkan dengan pesanan.', 'success');
            } else {
                Flasher::setFlash('Gagal:', 'Gagal melakukan rekonsiliasi.', 'error');
            }
            header('Location: ' . BASEURL . '/admin/finance?tab=reconciliation');
            exit;
        }
    }

    public function reconcile_unmatch() {
        Auth::requireRole(['superadmin', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $statementId = intval($_POST['statement_id'] ?? 0);
            
            $reconciliationModel = $this->model('ReconciliationModel');
            
            if ($reconciliationModel->unreconcile($statementId)) {
                Flasher::setFlash('Berhasil!', 'Status rekonsiliasi berhasil dibatalkan.', 'success');
            } else {
                Flasher::setFlash('Gagal:', 'Gagal membatalkan rekonsiliasi.', 'error');
            }
            header('Location: ' . BASEURL . '/admin/finance?tab=reconciliation');
            exit;
        }
    }

    public function add_bank_statement() {
        Auth::requireRole(['superadmin', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $date = $_POST['statement_date'] ?? '';
            $description = trim($_POST['description'] ?? '');
            $amount = floatval($_POST['amount'] ?? 0);
            $ref = trim($_POST['reference_code'] ?? '');
            
            if (empty($date) || empty($description) || empty($ref)) {
                Flasher::setFlash('Gagal:', 'Semua kolom mutasi bank wajib diisi.', 'error');
                header('Location: ' . BASEURL . '/admin/finance?tab=reconciliation');
                exit;
            }
            
            $reconciliationModel = $this->model('ReconciliationModel');
            $data = [
                'statement_date' => $date,
                'description' => $description,
                'amount' => $amount,
                'reference_code' => $ref
            ];
            
            if ($reconciliationModel->addBankStatement($data)) {
                Flasher::setFlash('Berhasil!', 'Mutasi rekening baru berhasil ditambahkan.', 'success');
            } else {
                Flasher::setFlash('Gagal:', 'Gagal menambahkan mutasi rekening.', 'error');
            }
            header('Location: ' . BASEURL . '/admin/finance?tab=reconciliation');
            exit;
        }
    }

    public function analysis() {
        Auth::requireRole(['superadmin', 'admin']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sales_target'])) {
            $_SESSION['sales_target'] = (float)$_POST['sales_target'];
        }
        $data['sales_target'] = $_SESSION['sales_target'] ?? 50000000.0;

        $data['judul'] = 'Analisa Bisnis & Auto Promosi | BAKUL Enterprise';
        
        $analysisModel = $this->model('AnalysisModel');
        $data['fast_moving'] = $analysisModel->getFastMovingProducts(5);
        $data['slow_moving'] = $analysisModel->getSlowMovingProducts(5);
        $data['current_sales'] = $analysisModel->getMonthlySalesProgress();
        $data['out_of_stock'] = $analysisModel->getOutOfStockAlerts();
        $data['bundling_suggestions'] = $analysisModel->getBundlingSuggestions();

        $this->view('admin/analysis/index', $data);
    }

    public function auto_create_promo() {
        Auth::requireRole(['superadmin', 'admin']);

        $product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
        if ($product_id <= 0) {
            Flasher::setFlash('Gagal:', 'ID produk tidak valid.', 'error');
            header('Location: ' . BASEURL . '/admin/analysis');
            exit;
        }

        $productModel = $this->model('ProductModel');
        $product = $productModel->getProductById($product_id);
        if (!$product) {
            Flasher::setFlash('Gagal:', 'Produk tidak ditemukan.', 'error');
            header('Location: ' . BASEURL . '/admin/analysis');
            exit;
        }

        $images = $productModel->getProductImages($product_id);
        $image_path = 'images/promo_banner_1.jpg'; // default banner image
        if (!empty($images)) {
            foreach ($images as $img) {
                if ($img['is_primary'] == 1) {
                    $image_path = $img['image_path'];
                    break;
                }
            }
            if ($image_path === 'images/promo_banner_1.jpg') {
                $image_path = $images[0]['image_path'];
            }
        }

        $promoModel = $this->model('PromoModel');
        $promoModel->addPromo([
            'title' => 'PROMO SPESIAL: ' . $product['name'],
            'subtitle' => 'Dapatkan ' . $product['name'] . ' dengan penawaran dan harga terbaik hari ini! Jangan lewatkan kesempatan ini.',
            'image_path' => $image_path,
            'link_url' => 'product/' . $product['slug'],
            'sort_order' => 1,
            'is_active' => 1
        ]);

        Flasher::setFlash('Berhasil!', 'Banner promosi otomatis untuk ' . $product['name'] . ' telah diterbitkan.', 'success');
        header('Location: ' . BASEURL . '/admin/analysis');
        exit;
    }

    public function auto_create_voucher() {
        Auth::requireRole(['superadmin', 'admin']);

        $product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
        if ($product_id <= 0) {
            Flasher::setFlash('Gagal:', 'ID produk tidak valid.', 'error');
            header('Location: ' . BASEURL . '/admin/analysis');
            exit;
        }

        $productModel = $this->model('ProductModel');
        $product = $productModel->getProductById($product_id);
        if (!$product) {
            Flasher::setFlash('Gagal:', 'Produk tidak ditemukan.', 'error');
            header('Location: ' . BASEURL . '/admin/analysis');
            exit;
        }

        $variants = $productModel->getProductVariants($product_id);
        $sku = '';
        if (!empty($variants)) {
            $sku = $variants[0]['sku'];
        }

        $voucherModel = $this->model('VoucherModel');
        
        $code = "AUTO-" . strtoupper($sku ? $sku : $product['slug']) . "-15";
        $code = preg_replace('/[^A-Z0-9\-]/', '', $code);
        
        // Ensure code uniqueness
        if ($voucherModel->getVoucherByCode($code)) {
            $code .= "-" . strtoupper(substr(md5(time()), 0, 3));
        }

        $voucherModel->addVoucher([
            'code' => $code,
            'discount_type' => 'percentage',
            'discount_amount' => 15.00,
            'min_spend' => 0.00,
            'expiry_date' => date('Y-m-d', strtotime('+14 days')),
            'usage_limit' => 100,
            'is_active' => 1
        ]);

        Flasher::setFlash('Berhasil!', 'Voucher diskon otomatis ' . $code . ' (15%) berhasil dibuat.', 'success');
        header('Location: ' . BASEURL . '/admin/analysis');
        exit;
    }

    public function claims() {
        Auth::requireRole(['superadmin', 'admin']);
        
        $data['judul'] = 'Manajemen Klaim Garansi | Admin ERP';
        $warrantyModel = $this->model('WarrantyModel');
        $data['claims'] = $warrantyModel->getAllClaims();
        
        $this->view('admin/claims/index', $data);
    }
    
    public function update_claim_status() {
        Auth::requireRole(['superadmin', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? 'pending';
            
            $warrantyModel = $this->model('WarrantyModel');
            $claim = $warrantyModel->getClaimById($id);
            
            if ($claim) {
                $warrantyModel->updateClaimStatus($id, $status);
                
                // Create user notification
                $notificationModel = $this->model('NotificationModel');
                $title = 'Status Klaim Garansi Diperbarui';
                $message = 'Klaim garansi Anda untuk invoice ' . $claim['invoice'] . ' (' . $claim['device_name'] . ') saat ini berstatus: ' . ucfirst($status) . '.';
                $notificationModel->createNotification($claim['user_id'], $title, $message);
                
                Flasher::setFlash('Berhasil!', 'Status klaim garansi berhasil diperbarui.', 'success');
            } else {
                Flasher::setFlash('Gagal:', 'Klaim garansi tidak ditemukan.', 'error');
            }
            
            header('Location: ' . BASEURL . '/admin/claims');
            exit;
        }
    }
}

