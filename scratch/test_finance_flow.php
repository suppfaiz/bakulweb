<?php
// scratch/test_finance_flow.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Models/ProductModel.php';
require_once __DIR__ . '/../app/Models/OrderModel.php';
require_once __DIR__ . '/../app/Models/ExpenseModel.php';

$db = new Database();
$productModel = new ProductModel();
$orderModel = new OrderModel();
$expenseModel = new ExpenseModel();

echo "Starting Finance Module Integration Verification...\n";

// Ensure tables exist and columns are present
$db->query("SHOW COLUMNS FROM `product_variants` LIKE 'purchase_price'");
if (!$db->single()) {
    die("Verification Failed: purchase_price column not found in product_variants table.\n");
}
$db->query("SHOW COLUMNS FROM `order_items` LIKE 'purchase_price'");
if (!$db->single()) {
    die("Verification Failed: purchase_price column not found in order_items table.\n");
}
echo "✓ Database schema verified.\n";

// 1. Create a mock product and variant
$product_id = $productModel->addProduct([
    'name' => 'TEST VERIFICATION GADGET',
    'slug' => 'test-verification-gadget-' . time(),
    'category_id' => 1, // assumes category 1 exists
    'brand_id' => 1,    // assumes brand 1 exists
    'description' => 'Test verification gadget'
]);

$sku = 'TEST-VERIF-' . time();
$purchase_price = 8000000.00; // Rp 8.000.000,00
$selling_price = 12000000.00; // Rp 12.000.000,00
$stock = 10;

$productModel->addProductVariant($product_id, $sku, '256GB', 'Titanium Gray', $selling_price, $stock, $purchase_price);

// Retrieve the variant ID
$db->query("SELECT id FROM product_variants WHERE sku = :sku");
$db->bind('sku', $sku);
$variant = $db->single();
$variant_id = $variant['id'];
echo "✓ Mock Product and Variant created. ID: $product_id, Variant ID: $variant_id\n";

// 2. Create a mock order and order items
$invoice = 'INV-TEST-' . time();
$order_id = $orderModel->createOrder([
    'invoice' => $invoice,
    'user_id' => 2, // assume user 2 is test customer
    'total_amount' => $selling_price,
    'payment_method' => 'Transfer Bank',
    'type' => 'online'
]);

$orderModel->addOrderItem($order_id, $variant_id, 1, $selling_price);

// Mark order as paid so it is included in reports
$orderModel->updatePaymentStatus($invoice, 'paid');
echo "✓ Mock Order created and marked as Paid. Order ID: $order_id, Invoice: $invoice\n";

// 3. Verify that purchase_price was captured in order_items
$db->query("SELECT * FROM order_items WHERE order_id = :order_id");
$db->bind('order_id', $order_id);
$item = $db->single();

if (!$item) {
    die("Verification Failed: Order item not found.\n");
}

if ((float)$item['purchase_price'] !== (float)$purchase_price) {
    die("Verification Failed: Captured purchase_price " . $item['purchase_price'] . " does not match expected variant purchase_price " . $purchase_price . ".\n");
}
echo "✓ Historical purchase price correctly captured in order items (Rp " . number_format($item['purchase_price'], 0, ',', '.') . ").\n";

// 4. Record an expense
$expense_title = 'TEST OPERATIONAL EXPENSE ' . time();
$expense_amount = 1500000.00; // Rp 1.500.000,00
$expenseModel->addExpense([
    'title' => $expense_title,
    'category' => 'Operasional',
    'amount' => $expense_amount,
    'date' => date('Y-m-d'),
    'description' => 'Test description'
]);

// Retrieve expense ID
$db->query("SELECT id FROM expenses WHERE title = :title");
$db->bind('title', $expense_title);
$expense = $db->single();
$expense_id = $expense['id'];
echo "✓ Mock Expense created. Expense ID: $expense_id\n";

// 5. Verify finance report calculations
$report = $orderModel->getFinanceReport(30);

echo "Report Summary (Last 30 Days):\n";
echo " - Gross Revenue: Rp " . number_format($report['pemasukan'], 0, ',', '.') . "\n";
echo " - Total COGS:    Rp " . number_format($report['total_cogs'], 0, ',', '.') . "\n";
echo " - Gross Profit:  Rp " . number_format($report['gross_profit'], 0, ',', '.') . "\n";
echo " - Total Expense: Rp " . number_format($report['total_expenses'], 0, ',', '.') . "\n";
echo " - Nett Profit:   Rp " . number_format($report['nett_profit'], 0, ',', '.') . "\n";

// Assertions
if ($report['pemasukan'] < $selling_price) {
    die("Assertion Failed: Revenue too low.\n");
}
if ($report['total_cogs'] < $purchase_price) {
    die("Assertion Failed: COGS too low.\n");
}
if ($report['total_expenses'] < $expense_amount) {
    die("Assertion Failed: Expenses too low.\n");
}
$expected_gross = $report['pemasukan'] - $report['total_cogs'];
if (abs($report['gross_profit'] - $expected_gross) > 0.01) {
    die("Assertion Failed: Gross profit calculation incorrect. Expected $expected_gross, got " . $report['gross_profit'] . "\n");
}
$expected_nett = $report['gross_profit'] - $report['total_expenses'];
if (abs($report['nett_profit'] - $expected_nett) > 0.01) {
    die("Assertion Failed: Nett profit calculation incorrect. Expected $expected_nett, got " . $report['nett_profit'] . "\n");
}
echo "✓ Financial calculations assertions passed.\n";

// 6. Cleanup mock data
// Delete order item
$db->query("DELETE FROM order_items WHERE order_id = :order_id");
$db->bind('order_id', $order_id);
$db->execute();

// Delete order
$db->query("DELETE FROM orders WHERE id = :order_id");
$db->bind('order_id', $order_id);
$db->execute();

// Delete variant
$db->query("DELETE FROM product_variants WHERE id = :variant_id");
$db->bind('variant_id', $variant_id);
$db->execute();

// Delete product
$db->query("DELETE FROM products WHERE id = :product_id");
$db->bind('product_id', $product_id);
$db->execute();

// Delete expense
$db->query("DELETE FROM expenses WHERE id = :expense_id");
$db->bind('expense_id', $expense_id);
$db->execute();

echo "✓ Mock verification data cleaned up.\n";
echo "✓ Finance Module Integration Verification COMPLETED SUCCESSFULY!\n";
