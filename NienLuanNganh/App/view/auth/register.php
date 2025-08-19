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
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
                        class="img-fluid" alt="Sample image">
                </div>

                <!-- Form bên phải -->
                <div class="col-md-6 col-lg-5 animate__animated animate__fadeInUp">
                    <div class="card shadow-lg rounded-4">
                        <div class="card-body">
                            <h3 class="text-center mb-4">Đăng ký</h3>

                            <!-- Mạng xã hội -->
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
                                <span class="text-muted">Đăng ký bằng email</span>
                            </div>

                            <form action="/register" method="POST">

                                <!-- Tên đăng nhập -->
                                <div class="form-floating mb-3">
                                    <input type="text"
                                        class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                                        id="username" name="username" placeholder="Tên đăng nhập"
                                        value="<?= isset($old['username']) ? $this->e($old['username']) : '' ?>"
                                        required>
                                    <label for="username">Tên đăng nhập</label>
                                    <?php if (isset($errors['username'])): ?>
                                    <div class="invalid-feedback"><?= $this->e($errors['username']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="row">
                                    <!-- Họ và tên -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating">
                                            <input type="text"
                                                class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                                                id="name" name="name" placeholder="Họ và tên"
                                                value="<?= isset($old['name']) ? $this->e($old['name']) : '' ?>"
                                                required>
                                            <label for="name">Họ và tên</label>
                                            <?php if (isset($errors['name'])): ?>
                                            <div class="invalid-feedback"><?= $this->e($errors['name']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Số điện thoại -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating">
                                            <input type="text"
                                                class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                                                id="phone" name="phone" placeholder="Số điện thoại"
                                                value="<?= isset($old['phone']) ? $this->e($old['phone']) : '' ?>">
                                            <label for="phone">Số điện thoại</label>
                                            <?php if (isset($errors['phone'])): ?>
                                            <div class="invalid-feedback"><?= $this->e($errors['phone']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="form-floating mb-3">
                                    <input type="email"
                                        class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                        id="email" name="email" placeholder="Địa chỉ email"
                                        value="<?= isset($old['email']) ? $this->e($old['email']) : '' ?>" required>
                                    <label for="email">Địa chỉ email</label>
                                    <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= $this->e($errors['email']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="row">
                                    <!-- Mật khẩu -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating">
                                            <input type="password"
                                                class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                                                id="password" name="password" placeholder="Mật khẩu" required>
                                            <label for="password">Mật khẩu</label>
                                            <?php if (isset($errors['password'])): ?>
                                            <div class="invalid-feedback"><?= $this->e($errors['password']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Xác nhận mật khẩu -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating">
                                            <input type="password"
                                                class="form-control <?= isset($errors['password_confirmation']) ? 'is-invalid' : '' ?>"
                                                id="confirmPassword" name="password_confirmation"
                                                placeholder="Xác nhận mật khẩu" required>
                                            <label for="confirmPassword">Xác nhận mật khẩu</label>
                                            <?php if (isset($errors['password_confirmation'])): ?>
                                            <div class="invalid-feedback">
                                                <?= $this->e($errors['password_confirmation']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Điều khoản -->
                                <div class="form-check mb-3">
                                    <input class="form-check-input <?= isset($errors['terms']) ? 'is-invalid' : '' ?>"
                                        type="checkbox" id="terms" name="terms"
                                        <?= isset($old['terms']) ? 'checked' : '' ?> required>
                                    <label class="form-check-label" for="terms">
                                        Tôi đồng ý với <a href="#" class="text-decoration-underline text-primary">điều
                                            khoản sử dụng</a>
                                    </label>
                                    <?php if (isset($errors['terms'])): ?>
                                    <div class="invalid-feedback"><?= $this->e($errors['terms']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <!-- Nút đăng ký -->
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-custom btn-lg">Đăng ký</button>
                                </div>

                                <!-- Đã có tài khoản -->
                                <div class="text-center">
                                    <p class="small fw-bold mb-0">Đã có tài khoản?
                                        <a href="/login" class="link-light text-decoration-underline">Đăng nhập</a>
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