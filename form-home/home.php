<?php
require '../connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Store</title>
    <link rel="stylesheet" href="css_of_html.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <!-- Header -->
    <div class="Header" id="header">
        <!-- Logo -->
        <div class="logo">
            <img src="../img/LOGO.png" alt="Logo" class="logo-image">
        </div>

        <!-- Navigation - Đã xóa dropdown -->
        <nav>
            <a href="#">Món Khai Vị</a>
            <a href="#">Món Chính</a>
            <a href="#">Tráng Miệng</a>
        </nav>

        <!-- Header icons -->
        <div class="header-icons">
            <a href="#" class="icon-link"><i class="fas fa-search"></i></a>
            <a href="../login/Login.html" class="icon-link"><i class="fas fa-user"></i></a>
            <a href="cart.html" class="icon-link"><i class="fas fa-shopping-cart"></i><span class="badge">3</span></a>
        </div>
    </div>

    <!-- Flash Sales Section -->
    <div class="flash-sales">
        <div class="flash-sales-header">
            <h2>Today's <span>Flash Sales</span></h2>
            <a href="#" class="view-all">View All Products<i class="fas fa-arrow-right"></i></a>
        </div>

        <!-- Slideshow Container -->
        <div class="slideshow-container">
            <!-- Slide 1 -->
            <div class="slide fade">
                <div class="slideshow-product">
                    <div class="discount-badge">-20%</div>
                    <div class="slideshow-content">
                        <div class="slideshow-text">
                            <h3 class="slideshow-title">Phở Bò Đặc Biệt</h3>
                            <p class="slideshow-description">Phở bò truyền thống với thịt bò tươi ngon, nước dùng đậm đà.</p>
                            <div class="price-container">
                                <p class="slideshow-price">80,000₫</p>
                                <p class="original-price">100,000₫</p>
                            </div>
                            <button class="slideshow-add-to-cart">Add to Cart</button>
                        </div>
                        <img src="../img/Phở Bò Đặc Biệt.jpg" alt="Phở Bò Đặc Biệt" class="slideshow-image">
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="slide fade">
                <div class="slideshow-product">
                    <div class="discount-badge">-15%</div>
                    <div class="slideshow-content">
                        <div class="slideshow-text">
                            <h3 class="slideshow-title">Bún Chả Hà Nội</h3>
                            <p class="slideshow-description">Bún chả thơm ngon với thịt nướng than hoa, nước mắm chua ngọt.</p>
                            <div class="price-container">
                                <p class="slideshow-price">60,000₫</p>
                                <p class="original-price">75,000₫</p>
                            </div>
                            <button class="slideshow-add-to-cart">Add to Cart</button>
                        </div>
                        <img src="../img/Bún Chả Hà Nội.jpeg" alt="Bún Chả Hà Nội" class="slideshow-image">
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="slide fade">
                <div class="slideshow-product">
                    <div class="discount-badge">-25%</div>
                    <div class="slideshow-content">
                        <div class="slideshow-text">
                            <h3 class="slideshow-title">Bánh Mì Thịt Nướng</h3>
                            <p class="slideshow-description">Bánh mì giòn tan với thịt nướng, pate, rau sống tươi mát.</p>
                            <div class="price-container">
                                <p class="slideshow-price">25,000₫</p>
                                <p class="original-price">35,000₫</p>
                            </div>
                            <button class="slideshow-add-to-cart">Add to Cart</button>
                        </div>
                        <img src="../img/Banh-Mi-Thit-Nuong.jpg" alt="Bánh Mì Thịt Nướng" class="slideshow-image">
                    </div>
                </div>
            </div>

            <!-- Navigation arrows -->
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>

        <!-- Dots indicators -->
        <div class="dots-container">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
        </div>
    </div>

    <!-- Regular Products Section -->
        <div class="Content">
        <h1>Món mới</h1>
        <div class="new-products-container">
            <?php
            // Lấy dữ liệu sản phẩm từ bảng products
            // (nếu cấu trúc cột khác, chỉ cần chỉnh lại các $row[...] bên dưới)
            $sql = "SELECT * FROM products ORDER BY id DESC"; // nếu không có cột id, bạn đổi sang tên PK đang dùng

            $result = $conn->query($sql);

            if ($result === false) {
                echo "<p>Lỗi truy vấn dữ liệu: " . $conn->error . "</p>";
            } else {
                if ($result->num_rows == 0) {
                    echo '<p class="no-products">
                            Hiện chưa có món ăn nào. Admin hãy thêm dữ liệu vào bảng 
                            <b>products</b> trong database <b>asm</b>.
                          </p>';
                } else {
                    echo '<div class="new-products-grid">';
                    while ($row = $result->fetch_assoc()) {
                        // Xử lý linh hoạt tên cột (tuỳ bảng của bạn đang dùng)
                        $id = 0;
                        if (isset($row['id'])) {
                            $id = (int)$row['id'];
                        } elseif (isset($row['productID'])) {
                            $id = (int)$row['productID'];
                        }

                        // Tên món ăn
                        $name = $row['name'] 
                            ?? $row['productName'] 
                            ?? 'Tên món chưa đặt';

                        // Giá (VND)
                        $price = (int)($row['price'] ?? 0);

                        // Ảnh (chỉ lưu tên file, ví dụ: "bun-bo-hue.jpg")
                        $image = $row['image'] 
                            ?? $row['imageURL'] 
                            ?? 'no-image.png';

                        // Mô tả
                        $desc = $row['description'] 
                            ?? 'Chưa có mô tả cho món ăn này.';

                        $name  = htmlspecialchars($name);
                        $image = htmlspecialchars($image);
                        $desc  = htmlspecialchars($desc);

                        echo '
                        <div class="explore-product-card new-product-card" data-product-id="'.$id.'">
                            <img src="../img/'.$image.'" alt="'.$name.'" class="explore-product-image">
                            <h3 class="explore-product-title">'.$name.'</h3>
                            <p class="new-product-desc">'.$desc.'</p>
                            <div class="explore-product-price-container">
                                <p class="explore-product-price">'.number_format($price, 0, ",", ".").'₫</p>
                            </div>
                            <button class="explore-add-to-cart">Add to Cart</button>
                        </div>
                        ';
                    }
                    echo '</div>'; // end .new-products-grid
                }
            }
            ?>
        </div>
    </div>
    <!-- Best Selling Products Section -->
    <div class="best-selling-container">
        <div class="best-selling">
            <div class="best-selling-header">
                <h2>Best Selling Products</h2>
                <a href="#" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="best-selling-products">
                <!-- Product 1 -->
                <div class="best-selling-card" data-product-id="1">
                    <div class="product-badge badge-bestseller">HOT</div>
                    <img src="../img/1.jpg" alt="Gỏi Cuốn Tôm Thịt" class="best-selling-image">
                    <h3 class="best-selling-title">Gỏi Cuốn Tôm Thịt</h3>
                    <div class="best-selling-price-container">
                        <p class="best-selling-price">45,000₫</p>
                    </div>
                    <button class="best-selling-add-to-cart">Add to Cart</button>
                </div>

                <!-- Product 2 -->
                <div class="best-selling-card" data-product-id="2">
                    <div class="product-badge badge-bestseller">HOT</div>
                    <img src="../img/2.jpg" alt="Cơm Tấm Sườn Bì" class="best-selling-image">
                    <h3 class="best-selling-title">Cơm Tấm Sườn Bì</h3>
                    <div class="best-selling-price-container">
                        <p class="best-selling-price">55,000₫</p>
                    </div>
                    <button class="best-selling-add-to-cart">Add to Cart</button>
                </div>

                <!-- Product 3 -->
                <div class="best-selling-card" data-product-id="3">
                    <div class="product-badge badge-bestseller">HOT</div>
                    <img src="../img/3.jpeg" alt="Bánh Xèo Miền Tây" class="best-selling-image">
                    <h3 class="best-selling-title">Bánh Xèo Miền Tây</h3>
                    <div class="best-selling-price-container">
                        <p class="best-selling-price">50,000₫</p>
                    </div>
                    <button class="best-selling-add-to-cart">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Explore Our Products Section -->
    <div class="explore-products-container">
        <div class="explore-products">
            <div class="explore-products-header">
                <h2>Explore Our Products</h2>
                <a href="#" class="view-all">View All Products <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="explore-products-grid">
                <!-- Product 1 -->
                <div class="explore-product-card" data-product-id="5">
                    <img src="../img/4.png" alt="Bún Bò Huế" class="explore-product-image">
                    <h3 class="explore-product-title">Bún Bò Huế</h3>
                    <div class="explore-product-price-container">
                        <p class="explore-product-price">65,000₫</p>
                    </div>
                    <button class="explore-add-to-cart">Add to Cart</button>
                </div>

                <!-- Product 2 -->
                <div class="explore-product-card" data-product-id="6">
                    <img src="../img/5.jpg" alt="Cá Kho Tộ" class="explore-product-image">
                    <h3 class="explore-product-title">Cá Kho Tộ</h3>
                    <div class="explore-product-price-container">
                        <p class="explore-product-price">75,000₫</p>
                    </div>
                    <button class="explore-add-to-cart">Add to Cart</button>
                </div>

                <!-- Product 3 -->
                <div class="explore-product-card" data-product-id="7">
                    <img src="../img/6.jpg" alt="Mì Quảng" class="explore-product-image">
                    <h3 class="explore-product-title">Mì Quảng</h3>
                    <div class="explore-product-price-container">
                        <p class="explore-product-price">55,000₫</p>
                    </div>
                    <button class="explore-add-to-cart">Add to Cart</button>
                </div>

                <!-- Product 4 -->
                <div class="explore-product-card" data-product-id="8">
                    <img src="../img/cao-lau-hoi-an.webp" alt="Cao Lầu Hội An" class="explore-product-image">
                    <h3 class="explore-product-title">Cao Lầu Hội An</h3>
                    <div class="explore-product-price-container">
                        <p class="explore-product-price">60,000₫</p>
                    </div>
                    <button class="explore-add-to-cart">Add to Cart</button>
                </div>

                <!-- Product 5 -->
                <div class="explore-product-card" data-product-id="9">
                    <img src="../img/7.jpg" alt="Hủ Tiếu Nam Vang" class="explore-product-image">
                    <h3 class="explore-product-title">Hủ Tiếu Nam Vang</h3>
                    <div class="explore-product-price-container">
                        <p class="explore-product-price">50,000₫</p>
                    </div>
                    <button class="explore-add-to-cart">Add to Cart</button>
                </div>

                <!-- Product 6 -->
                <div class="explore-product-card" data-product-id="10">
                    <img src="../img/8.jpg" alt="Chả Cá Lã Vọng" class="explore-product-image">
                    <h3 class="explore-product-title">Chả Cá Lã Vọng</h3>
                    <div class="explore-product-price-container">
                        <p class="explore-product-price">85,000₫</p>
                    </div>
                    <button class="explore-add-to-cart">Add to Cart</button>
                </div>

                <!-- Product 7 -->
                <div class="explore-product-card" data-product-id="11">
                    <img src="../img/9.jpg" alt="Nem Rán Hà Nội" class="explore-product-image">
                    <h3 class="explore-product-title">Nem Rán Hà Nội</h3>
                    <div class="explore-product-price-container">
                        <p class="explore-product-price">40,000₫</p>
                    </div>
                    <button class="explore-add-to-cart">Add to Cart</button>
                </div>

                <!-- Product 8 -->
                <div class="explore-product-card" data-product-id="12">
                    <img src="../img/10.webp" alt="Lẩu Thái Hải Sản" class="explore-product-image">
                    <h3 class="explore-product-title">Lẩu Thái Hải Sản</h3>
                    <div class="explore-product-price-container">
                        <p class="explore-product-price">150,000₫</p>
                    </div>
                    <button class="explore-add-to-cart">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="Footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p><i class="fas fa-map-marker-alt"></i> Trinh Van Bo, Hanoi, Vietnam</p>
                <p><i class="fas fa-phone"></i> +84 248 613 579</p>
                <p><i class="fas fa-envelope"></i> foodstore@gmail.com</p>
            </div>

            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Home</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Món Khai Vị</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Món Chính</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Tráng Miệng</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="youtube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2023 Food Store. All rights reserved.</p>
        </div>
    </div>
    <script src="jsofhome.js"></script>
    <?php $conn->close(); ?>
</body>

</html>