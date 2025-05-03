<?php 
include 'header.php'; 
include 'database/db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<div class="position-relative text-center text-white my-5" 
     style="background: url('images/products/p-6.jpg') no-repeat center center / cover; 
            height: 500px; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            width: 100%;
            overflow: hidden;
            object-fit: contain;">
    <div class="position-relative text-center">
        <h2 class="fw-bold">Welcome to Floral Aura</h2>
        <p class="fs-6">Discover amazing products at the best prices.</p>
        <a href="product_list.php" class="btn" style="background-color: #E57373;color:white;">Shop now</a>
    </div>
</div>
<!-- Products Section -->
<div class="container my-3 p-5">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 justify-content-center">
        <?php
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
                <div class="col">
                    <div class="card shadow-sm p-2 d-flex flex-column h-100"> 
                        <div class="text-center">
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" 
                                 class="card-img-top img-fluid"
                                 alt="<?php echo htmlspecialchars($row['name']); ?>"
                                 style="max-height: 250px; object-fit: cover;"> 
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between p-2">
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <h6 class="mb-0"><?php echo htmlspecialchars($row['name']); ?></h6>
                                <p class="fw-bold text-danger mb-0">₹<?php echo number_format($row['price'], 2); ?></p>
                            </div>
                            <form action="add-to-cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-sm w-100 text-white"
                                        style="background-color: #E57373;height:40px; border: none; transition: 0.3s;"
                                        onmouseover="this.style.backgroundColor='#C66464'"
                                        onmouseout="this.style.backgroundColor='#E57373'">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p class='text-center'>No products available.</p>";
        }
        ?>
        <a href="product_list.php" class="btn" style="background-color: #E57373;color:white;position:absolute;left:83%;bottom:-45%;width:120px;">View more</a>
    </div>
</div>
