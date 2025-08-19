<?php $this->layout("layouts/default", ["title" => "Thanh toán"]) ?>
<?php $this->start("page") ?>

<main class="container py-5">
    <!-- Consultation Section -->
    <section id="consultation" class="consultation section light-background">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Đặt Lịch Khám</h2>
            <div><span>Đăng ký nhanh&nbsp;</span> <span class="description-title">lịch khám tại phòng khám</span></div>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="cta-wrapper">
                <div class="row align-items-center">
                    <!-- Phần giới thiệu -->
                    <div class="col-lg-7" data-aos="fade-up" data-aos-delay="200">
                        <div class="cta-content">
                            <h2>Bạn cần khám bệnh? Chúng tôi luôn sẵn sàng hỗ trợ!</h2>
                            <p>Phòng khám chuyên khoa Tai Mũi Họng mang đến quy trình đặt lịch nhanh chóng, thuận tiện
                                và dễ dàng cho bệnh nhân. Hãy điền thông tin để được sắp xếp lịch khám phù hợp với thời
                                gian của bạn.</p>
                            <div class="cta-stats">
                                <div class="stat-item">
                                    <span class="number"><span data-purecounter-start="0" data-purecounter-end="1000"
                                            data-purecounter-duration="1" class="purecounter">1000</span>+</span>
                                    <span class="text">Bệnh nhân tin tưởng</span>
                                </div>
                                <div class="stat-item">
                                    <span class="number"><span data-purecounter-start="0" data-purecounter-end="95"
                                            data-purecounter-duration="1" class="purecounter">95</span>%</span>
                                    <span class="text">Hài lòng sau khám</span>
                                </div>
                                <div class="stat-item">
                                    <span class="number"><span data-purecounter-start="0" data-purecounter-end="3"
                                            data-purecounter-duration="1" class="purecounter">3</span>+</span>
                                    <span class="text">Năm hoạt động</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form đặt lịch -->
                    <div class="col-lg-5" data-aos="fade-up" data-aos-delay="300">
                        <div class="cta-form">
                            <h3>Thông tin đặt khám</h3>

                            <?php if (isset($_SESSION['errors'])): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($_SESSION['errors'] as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php unset($_SESSION['errors']); ?>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success">
                                <?= htmlspecialchars($_SESSION['success_message']) ?>
                            </div>
                            <?php unset($_SESSION['success_message']); ?>
                            <?php endif; ?>

                            <form action="/payment" method="post">
                                <input type="hidden" name="patient_type" value="user">

                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" placeholder="Họ và tên"
                                        value="<?= AUTHGUARD()->user() ? htmlspecialchars(AUTHGUARD()->user()->name) : '' ?>"
                                        required autofocus>
                                </div>

                                <div class="form-group mt-3">
                                    <input type="text" class="form-control" name="phone" placeholder="Số điện thoại"
                                        value="<?= AUTHGUARD()->user() ? htmlspecialchars(AUTHGUARD()->user()->phone) : '' ?>"
                                        required>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <input type="date" class="form-control" name="appointment_date"
                                            id="appointment_date" required
                                            value="<?= isset($old['appointment_date']) ? htmlspecialchars($old['appointment_date']) : '' ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" id="session" name="session" required>
                                            <option value="">-- Chọn buổi khám --</option>
                                            <option value="Sáng"
                                                <?= (isset($old['session']) && $old['session'] === 'Sáng') ? 'selected' : '' ?>>
                                                Sáng</option>
                                            <option value="Chiều"
                                                <?= (isset($old['session']) && $old['session'] === 'Chiều') ? 'selected' : '' ?>>
                                                Chiều</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 d-flex align-items-center mt-3">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="follow_up" name="follow_up"
                                            value="1"
                                            <?= (isset($old['follow_up']) && $old['follow_up'] == 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label ms-2 mb-0" for="follow_up">
                                            Tôi đã từng khám tại đây (tái khám)
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <textarea class="form-control" name="symptoms" id="symptoms" rows="5"
                                        placeholder="Mô tả triệu chứng của bạn..."
                                        required><?= isset($old['symptoms']) ? htmlspecialchars($old['symptoms']) : '' ?></textarea>
                                </div>

                                <div class="text-center">
                                    <button type="submit">Gửi yêu cầu</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section><!-- /Consultation Section -->
</main>

<?php $this->stop() ?>