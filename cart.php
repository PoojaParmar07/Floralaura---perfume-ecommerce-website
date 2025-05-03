<?php include 'header.php'; ?>
<?php
include 'database/db.php';

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    echo "<script>alert('Please log in to view your cart'); window.location.href='login.php';</script>";
    exit;
}

$sql = "SELECT cart.id AS cart_id, products.name, products.price, products.image, cart.quantity 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = $user_id";

$result = $conn->query($sql);
$total = 0;

// 🔥 Fetch all data into an array
$cart_items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mb-4 p-5">
            <?php if (!empty($cart_items)) { ?>
                <div class="table-responsive py-5">
                    <table class="table align-middle shadow-sm">
                        <thead>
                            <tr>
                                <th scope="col" style="color: #666666;">Image</th>
                                <th scope="col" style="color: #666666">Product</th>
                                <th scope="col" style="color: #666666">Price</th>
                                <th scope="col" style="color: #666666">Quantity</th>
                                <th scope="col" style="color: #666666">Subtotal</th>
                                <th scope="col" style="color: #666666">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item) { 
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            ?>
                                <tr>
                                    <td style="width: 120px;">
                                        <img src="<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="img-fluid rounded" style="height: 80px; object-fit: cover;">
                                    </td>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td><strong>₹<?php echo number_format($subtotal, 2); ?></strong></td>
                                    <td>
                                        <a href="remove-from-cart.php?cart_id=<?php echo $item['cart_id']; ?>" class="btn btn-outline-danger btn-sm">Remove</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <div class="alert alert-info">Your cart is empty. Start adding delicious food!</div>
            <?php } ?>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4 p-5">
            <div class="card shadow border-0 p-4 my-5 sticky-top" style="top: 20px;">
                <h4 class="mb-3" style="color: #E57373;;">Order Summary</h4>
                <table class="table align-middle shadow-sm">
                    <?php 
                    $order_total = 0; // reset order summary total
                    foreach ($cart_items as $item) {
                        $subtotal = $item['price'] * $item['quantity'];
                        $order_total += $subtotal;
                    ?>
                        <tr>
                            <td style="width: 60px;">
                                <img src="<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="img-fluid rounded" style="height: 40px; object-fit: cover;">
                            </td>
                            <td style="font-size:12px;font-weight:500;"><?php echo htmlspecialchars($item['name']); ?></td>
                            <td style="font-size:12px;font-weight:500;"><?php echo $item['quantity']; ?></td>
                            <td style="font-size:12px;font-weight:500;">₹<?php echo number_format($item['price'], 2); ?></td>
                            <td style="font-size:12px;font-weight:500;"><strong>₹<?php echo number_format($subtotal, 2); ?></strong></td>
                        </tr>
                    <?php } ?>
                </table>

                <div class="d-flex justify-content-between fw-bold fs-6" >
                    <span>Total :</span>
                    <span>₹<?php echo number_format($order_total, 2); ?></span>
                </div>

                <form action="order-success.php" method="post" class="mt-4">
                    <input type="hidden" name="total_amount" value="<?php echo $order_total; ?>">
                    <button type="submit" class="btn w-100 py-2" style="background-color:rgba(102, 102, 102, 0.4)" id="order-place">Place order</button>
                </form>
            </div>
        </div>
    </div>
</div>
