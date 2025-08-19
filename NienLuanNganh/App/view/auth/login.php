<?php $this->layout("layouts/default", ["title" => CONGNGHE]) ?>

<?php $this->start("page") ?>
<style>
.btn-custom {
    position: relative;
    overflow: hidden;
    display: inline-block;
    border: none;
    border-radius: 8px;
    padding: 12px 28px;
    font-size: 18px;
    font-weight: 600;
    color: white;
    background-color: #018880;
    cursor: pointer;
    z-index: 0;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 5px 15px rgba(1, 136, 128, 0.4);
}

.btn-custom::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(120deg, #01a79d, #02d5c3);
    z-index: -1;
    transition: all 0.4s ease-in-out;
}

.btn-custom:hover::before {
    left: 0;
}

.btn-custom:hover {
    box-shadow: 0 8px 25px rgba(1, 136, 128, 0.6);
    transform: translateY(-2px);
}
</style>

<main>
    <div
        class="absolute w-full top-0 bg-[url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg')] bg-cover bg-center min-h-75 dark-background">
        <span class="absolute top-0 left-0 w-full h-full"
            style="background-color: var(--background-color); opacity: 0.5;"></span>
    </div>

    <section class="vh-100 d-flex align-items-center justify-content-center bg-light">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <!-- Ảnh bên trái -->
                <div class="col-md-6 d-none d-md-block animate__animated animate__fadeInUp">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg"
                        class="img-fluid" alt="Phone image">
                </div>

                <!-- Form bên phải -->
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow-lg rounded-4 animate__animated animate__fadeInUp">
                        <div class="card-body p-5">
                            <h3 class="text-center mb-4">Đăng nhập</h3>

                            <!-- Nút mạng xã hội -->
                            <div class="d-flex justify-content-center gap-3 mb-4">
                                <a href="#" class="btn btn-outline-primary rounded-circle">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="btn btn-outline-info rounded-circle">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary rounded-circle">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>

                            <div class="text-center mb-4">
                                <span class="text-muted">Đăng nhập bằng email</span>
                            </div>

                            <!-- Hiển thị lỗi nếu có -->
                            <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>

                            <form action="/login" method="POST">
                                <!-- Tên đăng nhập -->
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="username" name="username"
                                        placeholder="Tên đăng nhập" required>
                                    <label for="username">Tên đăng nhập</label>
                                </div>

                                <!-- Mật khẩu -->
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Mật khẩu" required>
                                    <label for="password">Mật khẩu</label>
                                </div>

                                <!-- Ghi nhớ + Quên mật khẩu -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rememberMe"
                                            name="rememberMe">
                                        <label class="form-check-label" for="rememberMe">Ghi nhớ tôi</label>
                                    </div>
                                    <a href="#" class="text-body">Quên mật khẩu?</a>
                                </div>

                                <!-- Nút đăng nhập -->
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-custom btn-lg">Đăng nhập</button>
                                </div>

                                <!-- Chưa có tài khoản -->
                                <div class="text-center">
                                    <p class="small fw-bold mb-0">Chưa có tài khoản?
                                        <a href="#" class="link-light text-decoration-underline">Đăng ký</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php $this->stop() ?>