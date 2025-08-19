<?php $this->layout("layouts/default", ["title" => CONGNGHE]) ?>

<?php $this->start("page") ?>
<main>
    <div id="index-banner" class="parallax-container">
        <div class="section no-pad-bot">
            <div class="row">
                <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $key => $error): ?>
                        <li><strong><?php echo $key; ?>:</strong> <?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <div class="col-md-8 offset-md-2 mt-5">
                    <div class="card mt-5">
                        <div class="card-header fw-bold text-uppercase text-center h3">ĐĂNG KÝ</div>
                        <div class="card-body bg-body-tertiary">
                            <form action="/register" method="post">
                                <div class="row mb-3">
                                    <!-- Full name -->
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text"
                                                class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                                                id="name" name="name" placeholder="Full name"
                                                value="<?= isset($old['name']) ? $this->e($old['name']) : '' ?>"
                                                required autofocus>
                                            <label for="name">Full name</label>
                                            <?php if (isset($errors['name'])): ?>
                                            <div class="invalid-feedback"><?= $this->e($errors['name']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Username -->
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text"
                                                class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                                                id="username" name="username" placeholder="Username"
                                                value="<?= isset($old['username']) ? $this->e($old['username']) : '' ?>"
                                                required>
                                            <label for="username">Username</label>
                                            <?php if (isset($errors['username'])): ?>
                                            <div class="invalid-feedback"><?= $this->e($errors['username']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text"
                                                class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                                                id="phone" name="phone" placeholder="Phone number"
                                                value="<?= isset($old['phone']) ? $this->e($old['phone']) : '' ?>"
                                                required>
                                            <label for="phone">Phone number</label>
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
                                        id="email" name="email" placeholder="name@example.com"
                                        value="<?= isset($old['email']) ? $this->e($old['email']) : '' ?>" required>
                                    <label for="email">Email address</label>
                                    <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= $this->e($errors['email']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="row mb-3">
                                    <!-- Password -->
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="password"
                                                class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                                                id="password" name="password" placeholder="Password" required>
                                            <label for="password">Password</label>
                                            <?php if (isset($errors['password'])): ?>
                                            <div class="invalid-feedback"><?= $this->e($errors['password']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="password"
                                                class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>"
                                                id="confirmPassword" name="confirm_password"
                                                placeholder="Confirm Password" required>
                                            <label for="confirmPassword">Confirm Password</label>
                                            <?php if (isset($errors['confirm_password'])): ?>
                                            <div class="invalid-feedback"><?= $this->e($errors['confirm_password']) ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms and conditions -->
                                <div class="form-check mb-3">
                                    <input class="form-check-input <?= isset($errors['terms']) ? 'is-invalid' : '' ?>"
                                        type="checkbox" id="terms" name="terms"
                                        <?= isset($old['terms']) ? 'checked' : '' ?> required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" class="text-decoration-underline text-primary">terms
                                            and conditions</a>
                                    </label>
                                    <?php if (isset($errors['terms'])): ?>
                                    <div class="invalid-feedback d-block"><?= $this->e($errors['terms']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <!-- Submit button -->
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg">Register</button>
                                </div>

                                <!-- Login link -->
                                <div class="text-center">
                                    <p class="small fw-bold mb-0">Already have an account?
                                        <a href="/login" class="link-primary text-decoration-underline">Sign in</a>
                                    </p>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="parallax"><img
                src="/img/Xanh dương Rõ ràng và Doanh nghiệp Sự thật và Hư cấu Biến đổi Khí hậu Bài đăng Twitter.png"
                alt="Unsplashed background img 1"></div>
    </div>
</main>

<?php $this->stop() ?>