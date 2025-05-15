<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EFWAY Fresh Store Footer</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/footer.css'])
</head>

<body>
    <!-- Food Decorative Background -->
    <div class="food-decor"></div>

    <!-- Footer Wrapper -->
    <div class="footer-wrapper">
        <!-- Newsletter Section -->
        <section class="newsletter-section">
            <div class="container-fluid">
                <h2>BẢNG TIN</h2>
                <p>Hãy đăng ký để có thể nhận các thông tin mới nhất từ chúng tôi</p>
                <div class="d-flex justify-content-center">
                    <div class="search-container d-flex">
                        <input type="email" class="search-input" placeholder="Nhập email của bạn">
                        <button class="search-button">Đăng ký</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Divider -->
        <div class="divider"></div>

        <!-- Main Content Section -->
        <section class="main-content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Company Info -->
                    <div class="col-md-3">
                        <img src="image/logo.jpg" alt="Logo" width="130">
                        <p>Bạn đang khó khăn trong việc sử dụng web đừng lo vì chúng tôi có các trang mạng xã hội
                            có thể giúp đỡ cho bạn. Đừng ngần ngại mã hãy liên hệ với chúng tôi nhé .</p>
                        <div class="social-icons">
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-twitter"></i></a>
                            <a href="#"><i class="bi bi-google"></i></a>
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                    <!-- Quick Links -->
                    <div class="col-md-3">
                        <h5>COMPANY INFO</h5>
                        <p><a href="#">About Us</a></p>
                        <p><a href="#">Careers</a></p>
                        <p><a href="#">Social Responsibility</a></p>
                        <p><a href="#">Affiliate Program</a></p>
                        <p><a href="#">Business With Us</a></p>
                        <p><a href="#">Press & Media</a></p>
                        <p><a href="#">Terms of Use</a></p>
                    </div>
                    <!-- Hot Categories -->
                    <div class="col-md-3">
                        <h5>QUICK LINKS</h5>
                        <p><a href="#">Special Offers</a></p>
                        <p><a href="#">Gift Cards</a></p>
                        <p><a href="#">F21 Red</a></p>
                        <p><a href="#">Privacy Policy</a></p>
                        <p><a href="#">F21 Red</a></p>
                        <p><a href="#">Privacy Policy</a></p>
                        <p><a href="#">Gift Cards</a></p>
                    </div>
                    <!-- Contact Us -->
                    <div class="col-md-3">
                        <h5>CONTACT US</h5>
                        <p><i class="bi bi-headset"></i> Through WhatsApp</p>
                        <p><a href="tel:+0804-084-08789">+0804 084 08789</a></p>
                        <p><i class="bi bi-geo-alt"></i> GymVault, 18 East 50th Street, 4th Floor, New York, NY 10022
                        </p>
                        <p><a href="mailto:support@example.com">support@example.com</a></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Divider -->
        <div class="divider"></div>

        <!-- Footer Section -->
        <footer class="footer-section">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p>Nhóm ĐATN</p>
                    </div>
                    <div class="col-md-6 text-end payment-icons">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa"
                            height="24">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png"
                            alt="MasterCard" height="24">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal"
                            height="24">

                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scroll to Top Button -->
    <a href="#" class="scroll-top"><i class="bi bi-chevron-up"></i></a>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
