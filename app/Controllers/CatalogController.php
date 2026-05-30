<?php

class CatalogController extends Controller {
    public function index() {
        $data['judul'] = 'Katalog Produk | BAKUL Enterprise';
        
        $productModel = $this->model('ProductModel');
        
        $filters = [];
        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        if (!empty($_GET['category'])) {
            $filters['category'] = $_GET['category'];
        }
        if (!empty($_GET['brand'])) {
            $filters['brand'] = $_GET['brand'];
        }
        if (!empty($_GET['sort'])) {
            $filters['sort'] = $_GET['sort'];
        }
        
        $data['products'] = $productModel->filterProducts($filters);
        
        $data['categories'] = $productModel->getAllCategories();
        $data['brands'] = $productModel->getAllBrands();
        
        $this->view('frontend/catalog/index', $data);
    }
    
    // Endpoint for AJAX Filter
    public function filter() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productModel = $this->model('ProductModel');
            
            // Get JSON payload if sent via fetch
            $json = file_get_contents('php://input');
            $filters = json_decode($json, true);
            
            if(!$filters) {
                $filters = $_POST;
            }
            
            $results = $productModel->filterProducts($filters);
            
            header('Content-Type: application/json');
            echo json_encode($results);
            exit;
        }
    }
}
