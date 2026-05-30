<?php

class ProductController extends Controller {
    public function index($slug = '') {
        if(empty($slug)) {
            header('Location: ' . BASEURL . '/catalog');
            exit;
        }

        $productModel = $this->model('ProductModel');
        $product = $productModel->getProductBySlug($slug);

        if(!$product) {
            // Tampilkan halaman 404
            echo "404 - Produk tidak ditemukan!";
            exit;
        }

        $data['judul'] = $product['name'] . ' | BAKUL Enterprise';
        $data['product'] = $product;
        $data['variants'] = $productModel->getProductVariants($product['id']);
        $data['images'] = $productModel->getProductImages($product['id']);
        $data['reviews'] = $productModel->getReviewsByProduct($product['id']);
        $data['rating_info'] = $productModel->getAverageRating($product['id']);

        $this->view('frontend/product/detail', $data);
    }
}
