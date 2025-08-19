<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Index - Consulting Bootstrap Template</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Nucleo Icons -->
    <link href="/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <!-- Main Styling -->
    <link href="/assets/css/argon-dashboard-tailwind.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />


    <!-- Favicons -->
    <link href="/assets/img/favicon.png" rel="icon">
    <link href="/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="/assets/css/main.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: Consulting
  * Template URL: https://bootstrapmade.com/bootstrap-consulting-website-template/
  * Updated: May 01 2025 with Bootstrap v5.3.5
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
    <style>
    .employee-row.selected {
        background-color: #d1fae5 !important;
        /* Xanh nhạt, bạn có thể thay đổi */
    }
    </style>

</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container position-relative d-flex align-items-center justify-content-between">

            <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <!-- <img src="assets/img/logo.webp" alt=""> -->
                <h1 class="sitename">TaiMuiHongCare.</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <?php $user = AUTHGUARD()->user(); ?>

                <ul>
                    <li><a href="/#hero" class="active">Trang chủ</a></li>

                    <?php if ($user && $user->role === 'admin') : ?>
                    <li><a href="#section-statistics">Thống kê</a></li>
                    <li><a href="#section-employee-management">Nhân sự</a></li>
                    <li><a href="#section-service-management">Dịch vụ</a></li>
                    <li><a href="#section-schedule-management">Lịch làm việc</a></li>
                    <?php else : ?>
                    <li><a href="#about">Giới thiệu</a></li>
                    <li><a href="#services">Dịch vụ</a></li>
                    <li><a href="#consultation">Đặt lịch</a></li>
                    <li><a href="#why-choose-us">Điểm nổi bật</a></li>
                    <li><a href="#team">Đội ngũ Bác sĩ</a></li>
                    <li><a href="#contact">Liên hệ</a></li>
                    <?php endif; ?>

                </ul>

                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <div class="dropdown">
                <a class="text-white dropdown-toggle d-flex align-items-center gap-2" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-3"></i>
                    <?php if (AUTHGUARD()->isUserLoggedIn()) : ?>
                    <span><?= htmlspecialchars(AUTHGUARD()->user()->name) ?></span>
                    <?php endif; ?>
                </a>

                <ul class="dropdown-menu">
                    <?php if (!AUTHGUARD()->isUserLoggedIn()) : ?>
                    <!-- Nếu chưa đăng nhập -->
                    <li><a class="dropdown-item" href="/login">Đăng nhập</a></li>
                    <li><a class="dropdown-item" href="/register">Đăng ký</a></li>

                    <?php else : ?>
                    <!-- Nếu đã đăng nhập -->
                    <li>
                        <a class="dropdown-item" href="<?= '/profile/' . $this->e(AUTHGUARD()->user()->user_id) ?>">
                            Hồ sơ
                        </a>
                    </li>

                    <?php if (AUTHGUARD()->user()->role === 'patient') : ?>
                    <!-- Lịch khám bệnh cho bệnh nhân -->
                    <li>
                        <a class="dropdown-item"
                            href="<?= '/patient/appointments/' . $this->e(AUTHGUARD()->user()->user_id) ?>">
                            Lịch khám bệnh
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (AUTHGUARD()->user()->role === 'doctor') : ?>
                    <!-- Lịch khám bệnh cho bác sĩ -->
                    <li>
                        <a class="dropdown-item" href="/DoctorHistory">
                            Lịch sử khám bệnh
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (in_array(AUTHGUARD()->user()->role, ['doctor', 'admin', 'reception'])) : ?>
                    <!-- Nếu là bác sĩ, admin hoặc lễ tân -->
                    <li>
                        <a class="dropdown-item"
                            href="<?= '/doctor/schedule/' . $this->e(AUTHGUARD()->user()->user_id) ?>">
                            Lịch làm việc
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (AUTHGUARD()->user()->role === 'doctor') : ?>
                    <li>
                        <a class="dropdown-item" href="/doctor/appointments">Lịch khám bệnh<a>
                    </li>
                    <?php endif; ?>

                    <?php if (AUTHGUARD()->user()->role === 'reception') : ?>
                    <li><a class="dropdown-item" href="/reception/appointments">Quầy lễ tân</a></li>
                    <?php endif; ?>

                    <?php if (AUTHGUARD()->user()->role === 'admin') : ?>
                    <li><a class="dropdown-item" href="/admin">Quản trị</a></li>
                    <?php endif; ?>

                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item text-end" href="/logout"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Đăng xuất <i class="bi bi-box-arrow-in-left fs-5 ms-2"></i>
                        </a>
                        <form id="logout-form" class="d-none" action="/logout" method="POST"></form>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>


        </div>
    </header>


    <?= $this->section("page") ?>

    <footer id="footer" class="footer light-background">

        <div class="container">
            <div class="row gy-3">
                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-geo-alt icon"></i>
                    <div class="address">
                        <h4>Địa chỉ</h4>
                        <p>108 Đường Adam</p>
                        <p>New York, NY 535022</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-telephone icon"></i>
                    <div>
                        <h4>Liên hệ</h4>
                        <p>
                            <strong>Điện thoại:</strong> <span>+1 5589 55488 55</span><br>
                            <strong>Email:</strong> <span>info@example.com</span><br>
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-clock icon"></i>
                    <div>
                        <h4>Giờ làm việc</h4>
                        <p>
                            <strong>Thứ 2 - Thứ 7:</strong> <span>11:00 - 23:00</span><br>
                            <strong>Chủ nhật:</strong> <span>Nghỉ</span>
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h4>Theo dõi chúng tôi</h4>
                    <div class="social-links d-flex">
                        <a href="#" class="twitter"><i class="fab fa-x-twitter"></i></a>
                        <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="linkedin"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>Bản quyền</span> <strong class="px-1 sitename">TaiMuiHongCare.</strong> <span>Đã đăng ký</span>
            </p>
            <div class="credits">
                Thiết kế bởi <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
            <script src="https://app.tudongchat.com/js/chatbox.js"></script>
            <script>
            const tudong_chatbox = new TuDongChat('7sjxXdIj4WjY7sranh32J')
            tudong_chatbox.initial()
            </script>
        </div>

    </footer>


    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="/assets/vendor/php-email-form/validate.js"></script> -->
    <script src="/assets/vendor/aos/aos.js"></script>
    <script src="/assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="/assets/js/main.js"></script>
    <!-- plugin for scrollbar  -->
    <script src="/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <!-- main script file  -->

    <script src="/assets/js/argon-dashboard-tailwind.js"></script>



    <!-- plugin for charts  -->
    <!-- <script src="/assets/js/plugins/chartjs.min.js" async></script> -->
</body>

<script>
const IS_ADMIN = <?= json_encode(AUTHGUARD()->user()->role === 'admin') ?>;
</script>


</html>