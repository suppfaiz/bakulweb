<?php

class HomeController extends Controller {
    public function index() {
        $data['judul'] = 'BAKUL Enterprise - Toko Gadget & Smartphone Premium';
        $productModel = $this->model('ProductModel');
        // Ambil 6 produk terbaru untuk featured section
        $data['featured'] = $productModel->getAllProducts();
        $data['categories'] = $productModel->getAllCategories();
        $data['brands'] = $productModel->getAllBrands();
        $data['promos'] = $this->model('PromoModel')->getActivePromos();
        $this->view('frontend/home/index', $data);
    }
}
