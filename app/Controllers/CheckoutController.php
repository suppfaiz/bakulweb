<?php

class CheckoutController extends Controller {
    public function __construct() {
        require_once __DIR__ . '/../Helpers/Auth.php';
        Auth::requireLogin();
    }

    // GET /checkout — Tampilkan halaman review + pilih alamat & kurir
    public function index() {
        if(empty($_SESSION['cart'])) {
            header('Location: ' . BASEURL . '/cart');
            exit;
        }

        $productModel = $this->model('ProductModel');
        $userModel    = $this->model('UserModel');

        $cart_items = [];
        $subtotal   = 0;

        foreach($_SESSION['cart'] as $variant_id => $item) {
            $variant = $productModel->getVariantDetails($variant_id);
            if($variant) {
                $line_total = $variant['price'] * $item['qty'];
                $subtotal  += $line_total;
                $cart_items[] = [
                    'variant_id' => $variant_id,
                    'name'       => $variant['product_name'],
                    'color'      => $variant['color'],
                    'storage'    => $variant['storage'],
                    'image'      => $variant['image'] ?? null,
                    'price'      => $variant['price'],
                    'qty'        => $item['qty'],
                    'total'      => $line_total,
                ];
            }
        }

        $user = $userModel->getUserById($_SESSION['user_id']);

        $data['judul']      = 'Konfirmasi Pesanan | BAKUL';
        $data['cart_items'] = $cart_items;
        $data['subtotal']   = $subtotal;
        $data['user']       = $user;

        $this->view('frontend/checkout/index', $data);
    }

    // POST /checkout/process — Simpan pesanan lalu ke pembayaran
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/checkout');
            exit;
        }
        if(empty($_SESSION['cart'])) {
            header('Location: ' . BASEURL . '/cart');
            exit;
        }

        // Ambil data form
        $recipient_name     = trim($_POST['recipient_name']    ?? '');
        $recipient_phone    = trim($_POST['recipient_phone']   ?? '');
        $shipping_address   = trim($_POST['shipping_address']  ?? '');
        $courier_key        = $_POST['courier_service_key']    ?? 'JNE|REG';
        $shipping_cost_raw  = (int)($_POST['shipping_cost']    ?? 15000);
        $save_address       = isset($_POST['save_address']);
        $payment_method_val = $_POST['payment_method']         ?? 'midtrans';
        $is_cod             = ($payment_method_val === 'cod');

        // Validasi
        if(!$recipient_name || !$recipient_phone || !$shipping_address) {
            Flasher::setFlash('gagal', 'Harap isi nama penerima, nomor HP, dan alamat pengiriman.', 'error');
            header('Location: ' . BASEURL . '/checkout');
            exit;
        }

        // Parse kurir (override if COD)
        if ($is_cod) {
            $courier         = 'COD';
            $courier_service = 'Ketemuan';
            $shipping_cost   = 0;
            $payment_method  = 'COD (Bayar di Tempat)';
        } elseif ($payment_method_val === 'bank_transfer') {
            $courier_parts   = explode('|', $courier_key);
            $courier         = $courier_parts[0] ?? 'JNE';
            $courier_service = $courier_parts[1] ?? 'REG';
            $shipping_cost   = $shipping_cost_raw;
            $payment_method  = 'Transfer Bank BNI';
        } else {
            $courier_parts   = explode('|', $courier_key);
            $courier         = $courier_parts[0] ?? 'JNE';
            $courier_service = $courier_parts[1] ?? 'REG';
            $shipping_cost   = $shipping_cost_raw;
            $payment_method  = 'Midtrans Payment Gateway';
        }

        $productModel = $this->model('ProductModel');
        $orderModel   = $this->model('OrderModel');

        $subtotal = 0;
        foreach($_SESSION['cart'] as $variant_id => $item) {
            $variant = $productModel->getVariantDetails($variant_id);
            if (!$variant || $item['qty'] > $variant['stock']) {
                $name = $variant ? $variant['product_name'] : 'Produk';
                $available = $variant ? $variant['stock'] : 0;
                Flasher::setFlash('gagal', "Stok produk '$name' tidak mencukupi (Tersedia: $available). Mohon sesuaikan keranjang belanja Anda.", 'error');
                header('Location: ' . BASEURL . '/cart');
                exit;
            }
            $subtotal += $variant['price'] * $item['qty'];
        }

        // Voucher Validation
        $voucher_code = strtoupper(trim($_POST['voucher_code'] ?? ''));
        $discount_amount = 0;

        if (!empty($voucher_code)) {
            $voucherModel = $this->model('VoucherModel');
            $voucher = $voucherModel->getVoucherByCode($voucher_code);
            
            if ($voucher) {
                $valid = true;
                if (!empty($voucher['expiry_date']) && strtotime($voucher['expiry_date'] . ' 23:59:59') < time()) {
                    $valid = false;
                }
                if ($voucher['usage_limit'] !== null && $voucher['usage_count'] >= $voucher['usage_limit']) {
                    $valid = false;
                }
                if ($subtotal < $voucher['min_spend']) {
                    $valid = false;
                }

                if ($valid) {
                    if ($voucher['discount_type'] === 'fixed') {
                        $discount_amount = (float)$voucher['discount_amount'];
                        $discount_amount = min($discount_amount, $subtotal);
                    } else if ($voucher['discount_type'] === 'percentage') {
                        $discount_amount = ($subtotal * (float)$voucher['discount_amount']) / 100;
                        $discount_amount = min($discount_amount, $subtotal);
                    }
                } else {
                    Flasher::setFlash('gagal', 'Voucher tidak memenuhi syarat atau sudah kedaluwarsa.', 'error');
                    header('Location: ' . BASEURL . '/checkout');
                    exit;
                }
            } else {
                Flasher::setFlash('gagal', 'Kode voucher tidak valid.', 'error');
                header('Location: ' . BASEURL . '/checkout');
                exit;
            }
        }

        $total_amount  = max(0, ($subtotal - $discount_amount) + $shipping_cost);

        // Generate Invoice
        $invoice = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        // Buat order
        $orderData = [
            'invoice'          => $invoice,
            'user_id'          => $_SESSION['user_id'],
            'total_amount'     => $total_amount,
            'shipping_cost'    => $shipping_cost,
            'payment_method'   => $payment_method,
            'recipient_name'   => $recipient_name,
            'recipient_phone'  => $recipient_phone,
            'shipping_address' => $shipping_address,
            'courier'          => $courier,
            'courier_service'  => $courier_service,
            'voucher_code'     => !empty($voucher_code) ? $voucher_code : null,
            'discount_amount'  => $discount_amount
        ];

        $order_id = $orderModel->createOrder($orderData);

        // Increment usage count of voucher
        if (!empty($voucher_code)) {
            $this->model('VoucherModel')->incrementUsage($voucher_code);
        }

        // Simpan order items & potong stok
        foreach($_SESSION['cart'] as $variant_id => $item) {
            $variant = $productModel->getVariantDetails($variant_id);
            if($variant) {
                $orderModel->addOrderItem($order_id, $variant_id, $item['qty'], $variant['price']);
                $orderModel->decreaseStock($variant_id, $item['qty']);
            }
        }

        // Simpan alamat ke profil user jika diminta
        if($save_address) {
            $userModel = $this->model('UserModel');
            $userModel->updateProfile($_SESSION['user_id'], $_SESSION['name'], $recipient_phone, $shipping_address);
        }

        // Kosongkan keranjang
        unset($_SESSION['cart']);

        // Notifikasi
        $notificationModel = $this->model('NotificationModel');
        if ($is_cod) {
            $notificationModel->createNotification(
                $_SESSION['user_id'],
                'Pesanan COD Dibuat',
                'Pesanan COD dengan invoice ' . $invoice . ' telah berhasil dibuat. Silakan hubungi penjual untuk koordinasi ketemuan.'
            );
        } elseif ($payment_method_val === 'bank_transfer') {
            $notificationModel->createNotification(
                $_SESSION['user_id'],
                'Pesanan Menunggu Transfer',
                'Pesanan ' . $invoice . ' berhasil dibuat. Silakan transfer Rp ' . number_format($total_amount, 0, ',', '.') . ' ke BNI 0231090661 a/n Septian Faiz Witana, lalu kirim bukti via WhatsApp.'
            );
        } else {
            $notificationModel->createNotification(
                $_SESSION['user_id'],
                'Pesanan Baru Dibuat',
                'Pesanan Anda dengan invoice ' . $invoice . ' telah berhasil dibuat. Silakan selesaikan pembayaran.'
            );
        }

        // Redirect ke halaman yang sesuai
        if ($is_cod) {
            header('Location: ' . BASEURL . '/checkout/cod_success/' . $invoice);
        } elseif ($payment_method_val === 'bank_transfer') {
            header('Location: ' . BASEURL . '/checkout/bank_transfer_success/' . $invoice);
        } else {
            header('Location: ' . BASEURL . '/checkout/payment/' . $invoice);
        }
        exit;
    }

    // GET /checkout/cod_success/{invoice}
    public function cod_success($invoice = null) {
        if(!$invoice) {
            header('Location: ' . BASEURL);
            exit;
        }

        $orderModel = $this->model('OrderModel');
        $order      = $orderModel->getOrderByInvoice($invoice);

        if(!$order || ($order['user_id'] != $_SESSION['user_id'])) {
            header('Location: ' . BASEURL);
            exit;
        }

        $data['judul']   = 'Pesanan COD Berhasil | BAKUL';
        $data['invoice'] = htmlspecialchars($invoice);
        $data['order']   = $order;
        $data['wa_number'] = '6282312345678'; // Update dengan nomor WA penjual
        $this->view('frontend/checkout/cod_success', $data);
    }

    // GET /checkout/bank_transfer_success/{invoice}
    public function bank_transfer_success($invoice = null) {
        if(!$invoice) {
            header('Location: ' . BASEURL);
            exit;
        }

        $orderModel = $this->model('OrderModel');
        $order      = $orderModel->getOrderByInvoice($invoice);

        if(!$order || ($order['user_id'] != $_SESSION['user_id'])) {
            header('Location: ' . BASEURL);
            exit;
        }

        $data['judul']   = 'Menunggu Pembayaran Transfer | BAKUL';
        $data['invoice'] = htmlspecialchars($invoice);
        $data['order']   = $order;
        $this->view('frontend/checkout/bank_transfer_success', $data);
    }

    // GET /checkout/payment/{invoice}
    public function payment($invoice = null) {
        if(!$invoice) {
            header('Location: ' . BASEURL);
            exit;
        }
        $orderModel = $this->model('OrderModel');
        $order      = $orderModel->getOrderByInvoice($invoice);

        if(!$order || ($order['user_id'] != $_SESSION['user_id'])) {
            Flasher::setFlash('gagal', 'Invoice tidak valid.', 'error');
            header('Location: ' . BASEURL . '/account');
            exit;
        }

        $data['judul']   = 'Pembayaran Aman | Midtrans Simulator';
        $data['invoice'] = htmlspecialchars($invoice);
        $data['amount']  = $order['total_amount'];
        $this->view('frontend/checkout/midtrans_mock', $data);
    }

    // GET /checkout/success/{invoice}
    public function success($invoice = null) {
        if(!$invoice) {
            header('Location: ' . BASEURL);
            exit;
        }

        $orderModel = $this->model('OrderModel');
        $trx_id     = 'TRX-' . time();
        $orderModel->updatePaymentStatus($invoice, 'paid', $trx_id);

        $notificationModel = $this->model('NotificationModel');
        $notificationModel->createNotification(
            $_SESSION['user_id'],
            'Pembayaran Berhasil',
            'Pembayaran untuk pesanan ' . $invoice . ' telah diverifikasi. Pesanan sedang diproses.'
        );

        $data['judul']   = 'Pembayaran Berhasil';
        $data['invoice'] = htmlspecialchars($invoice);
        $this->view('frontend/checkout/success', $data);
    }

    // POST /checkout/apply_voucher
    public function apply_voucher() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Metode request tidak diizinkan.']);
            exit;
        }

        $code = strtoupper(trim($_POST['code'] ?? ''));
        if (empty($code)) {
            echo json_encode(['success' => false, 'message' => 'Kode voucher tidak boleh kosong.']);
            exit;
        }

        $voucherModel = $this->model('VoucherModel');
        $voucher = $voucherModel->getVoucherByCode($code);

        if (!$voucher) {
            echo json_encode(['success' => false, 'message' => 'Kode voucher tidak valid atau tidak aktif.']);
            exit;
        }

        // 1. Cek Expiry Date
        if (!empty($voucher['expiry_date']) && strtotime($voucher['expiry_date'] . ' 23:59:59') < time()) {
            echo json_encode(['success' => false, 'message' => 'Kode voucher ini sudah kedaluwarsa.']);
            exit;
        }

        // 2. Cek Limit Penggunaan
        if ($voucher['usage_limit'] !== null && $voucher['usage_count'] >= $voucher['usage_limit']) {
            echo json_encode(['success' => false, 'message' => 'Kuota penggunaan kode voucher ini telah habis.']);
            exit;
        }

        // 3. Cek Minimum Belanja (hitung subtotal dari keranjang belanja user)
        $subtotal = 0;
        $productModel = $this->model('ProductModel');
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $variant_id => $item) {
                $variant = $productModel->getVariantDetails($variant_id);
                if ($variant) {
                    $subtotal += $variant['price'] * $item['qty'];
                }
            }
        }

        if ($subtotal < $voucher['min_spend']) {
            echo json_encode([
                'success' => false,
                'message' => 'Minimal belanja untuk voucher ini adalah Rp ' . number_format($voucher['min_spend'], 0, ',', '.')
            ]);
            exit;
        }

        // Hitung diskon
        $discount_amount = 0;
        if ($voucher['discount_type'] === 'fixed') {
            $discount_amount = (float)$voucher['discount_amount'];
            $discount_amount = min($discount_amount, $subtotal); // Diskon tidak boleh melebihi subtotal
        } else if ($voucher['discount_type'] === 'percentage') {
            $discount_amount = ($subtotal * (float)$voucher['discount_amount']) / 100;
            $discount_amount = min($discount_amount, $subtotal);
        }

        echo json_encode([
            'success' => true,
            'code' => $code,
            'discount_amount' => $discount_amount,
            'discount_formatted' => 'Rp ' . number_format($discount_amount, 0, ',', '.'),
            'message' => 'Voucher berhasil diterapkan!'
        ]);
        exit;
    }
}
