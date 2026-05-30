<?php

class CartController extends Controller {
    public function index() {
        $data['judul'] = 'Keranjang Belanja | BAKUL Enterprise';
        
        if(!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        $productModel = $this->model('ProductModel');
        $cart_items = [];
        $subtotal = 0;

        foreach($_SESSION['cart'] as $variant_id => $item) {
            $variant = $productModel->getVariantDetails($variant_id);
            if($variant) {
                $item_total = $variant['price'] * $item['qty'];
                $subtotal += $item_total;
                $cart_items[] = [
                    'variant_id' => $variant_id,
                    'name' => $variant['product_name'],
                    'storage' => $variant['storage'],
                    'color' => $variant['color'],
                    'price' => $variant['price'],
                    'qty' => $item['qty'],
                    'image' => $variant['image'],
                    'total' => $item_total
                ];
            }
        }
        
        $data['cart_items'] = $cart_items;
        $data['subtotal'] = $subtotal;
        $data['tax'] = 0; // Bebas pajak untuk sementara
        $data['total'] = $subtotal + $data['tax'];
        
        $this->view('frontend/cart/index', $data);
    }
    
    public function add() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $variant_id = $_POST['variant_id'] ?? 0;
            $qty = $_POST['qty'] ?? 1;
            
            if(!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // Cek ke DB untuk detail stok
            $productModel = $this->model('ProductModel');
            $variant = $productModel->getVariantDetails($variant_id);
            
            if(!$variant) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
                exit;
            }

            $current_qty = isset($_SESSION['cart'][$variant_id]) ? $_SESSION['cart'][$variant_id]['qty'] : 0;
            $new_qty = $current_qty + $qty;
            
            if ($new_qty > $variant['stock']) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Stok produk tidak mencukupi (Tersedia: ' . $variant['stock'] . ')']);
                exit;
            }

            if(isset($_SESSION['cart'][$variant_id])) {
                $_SESSION['cart'][$variant_id]['qty'] += $qty;
            } else {
                $_SESSION['cart'][$variant_id] = [
                    'qty' => $qty
                ];
            }
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Added to cart']);
            exit;
        }
    }

    public function remove($variant_id = null) {
        if ($variant_id && isset($_SESSION['cart'][$variant_id])) {
            unset($_SESSION['cart'][$variant_id]);
        }
        header('Location: ' . BASEURL . '/cart');
        exit;
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $variant_id = $_POST['variant_id'] ?? 0;
            $qty = intval($_POST['qty'] ?? 1);
            
            if ($variant_id && isset($_SESSION['cart'][$variant_id])) {
                if ($qty <= 0) {
                    unset($_SESSION['cart'][$variant_id]);
                } else {
                    $productModel = $this->model('ProductModel');
                    $variant = $productModel->getVariantDetails($variant_id);
                    if ($variant) {
                        if ($qty <= $variant['stock']) {
                            $_SESSION['cart'][$variant_id]['qty'] = $qty;
                            Flasher::setFlash('berhasil', 'Jumlah keranjang belanja diperbarui.', 'success');
                        } else {
                            Flasher::setFlash('gagal', 'Stok produk tidak mencukupi (Tersedia: ' . $variant['stock'] . ')', 'error');
                        }
                    }
                }
            }
        }
        header('Location: ' . BASEURL . '/cart');
        exit;
    }
}
