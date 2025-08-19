<?php $this->layout("layouts/default", ["title" => "Thông tin bác sĩ Tai Mũi Họng"]) ?>

<?php $this->start("page") ?>
<style>
.btn-custom {
    position: relative;
    overflow: hidden;
    display: inline-block;
    border: none;
    border-radius: 8px;
    padding: 12px 28px;
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

    <section class="contact section bg-light">
        <div class="w-full p-6 mx-auto">
            <div class="flex flex-wrap -mx-3">
                <!-- Form chỉnh sửa thông tin bác sĩ -->
                <div class="w-full max-w-full px-3 shrink-0 md:w-8/12 md:flex-0 animate__animated animate__fadeInUp">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                        <?php if (isset($_SESSION['error_message'])) : ?>
                        <div class="mb-4 text-red-600 text-sm font-semibold">
                            <?php
                                if (is_array($_SESSION['error_message'])) {
                                    foreach ($_SESSION['error_message'] as $err) {
                                        echo '<div>' . htmlspecialchars($err) . '</div>';
                                    }
                                } else {
                                    echo htmlspecialchars($_SESSION['error_message']);
                                }
                                unset($_SESSION['error_message']);
                                ?>
                        </div>
                        <?php endif ?>

                        <form method="POST"
                            action="/update-doctor-info/<?= $this->e(AUTHGUARD()->user()->user_id) ?>/<?= $this->e(AUTHGUARD()->user()->role) ?>"
                            enctype="multipart/form-data" class="flex-auto p-6">

                            <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm mb-3">Thông tin
                                cá nhân</p>
                            <div class="flex flex-wrap -mx-3">

                                <input type="hidden" name="position" value="<?= $this->e(AUTHGUARD()->user()->role) ?>">

                                <!-- Username -->
                                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                                    <div class="mb-4">
                                        <label for="username"
                                            class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Tên
                                            đăng nhập</label>
                                        <input type="text" name="username" id="username"
                                            value="<?= $this->e(AUTHGUARD()->user()->username ?? '') ?>" required
                                            readonly
                                            class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500" />
                                        <?php if (isset($errors['username'])) : ?>
                                        <div class="invalid-feedback"><?= $this->e($errors['username']) ?></div>
                                        <?php endif ?>
                                    </div>
                                </div>

                                <!-- Name -->
                                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                                    <div class="mb-4">
                                        <label for="name"
                                            class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Họ
                                            và tên</label>
                                        <input type="text" name="name" id="name"
                                            value="<?= $this->e(AUTHGUARD()->user()->name ?? '') ?>" required
                                            class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500" />
                                        <?php if (isset($errors['name'])) : ?>
                                        <div class="invalid-feedback"><?= $this->e($errors['name']) ?></div>
                                        <?php endif ?>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                                    <div class="mb-4">
                                        <label for="email"
                                            class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Email</label>
                                        <input type="email" name="email" id="email"
                                            value="<?= $this->e(AUTHGUARD()->user()->email ?? '') ?>" required
                                            class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500" />
                                        <?php if (isset($errors['email'])) : ?>
                                        <div class="invalid-feedback"><?= $this->e($errors['email']) ?></div>
                                        <?php endif ?>
                                    </div>
                                </div>


                                <!-- Phone -->
                                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                                    <div class="mb-4">
                                        <label for="phone"
                                            class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Số
                                            điện thoại</label>
                                        <input type="tel" name="phone" id="phone"
                                            value="<?= $this->e(AUTHGUARD()->user()->phone ?? '') ?>" required
                                            class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500" />
                                        <?php if (isset($errors['phone'])) : ?>
                                        <div class="invalid-feedback"><?= $this->e($errors['phone']) ?></div>
                                        <?php endif ?>
                                    </div>
                                </div>


                                <!-- Address -->
                                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                                    <div class="mb-4">
                                        <label for="address"
                                            class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Địa
                                            chỉ</label>
                                        <input type="text" name="address" id="address"
                                            value="<?= $this->e(AUTHGUARD()->user()->address ?? '') ?>"
                                            class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500" />
                                        <?php if (isset($errors['address'])) : ?>
                                        <div class="invalid-feedback"><?= $this->e($errors['address']) ?></div>
                                        <?php endif ?>
                                    </div>
                                </div>

                                <!-- Specialization -->
                                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                                    <div class="mb-4">
                                        <label for="specialization"
                                            class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Chuyên
                                            môn</label>
                                        <input type="text" name="specialization" id="specialization"
                                            value="<?= $this->e(AUTHGUARD()->doctor()->specialization ?? '') ?>"
                                            class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500" />
                                        <?php if (isset($errors['specialization'])) : ?>
                                        <div class="invalid-feedback"><?= $this->e($errors['specialization']) ?></div>
                                        <?php endif ?>
                                    </div>
                                </div>

                                <!-- Working Time -->
                                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                                    <div class="mb-4">
                                        <label for="working_time"
                                            class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Thời
                                            gian làm việc</label>
                                        <input type="text" name="working_time" id="working_time"
                                            value="<?= $this->e(AUTHGUARD()->doctor()->working_time ?? '') ?>"
                                            class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500" />
                                        <?php if (isset($errors['working_time'])) : ?>
                                        <div class="invalid-feedback"><?= $this->e($errors['working_time']) ?></div>
                                        <?php endif ?>
                                    </div>
                                </div>

                                <!-- Experience Years -->
                                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                                    <div class="mb-4">
                                        <label for="experience_years"
                                            class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Số
                                            năm kinh nghiệm</label>
                                        <input type="number" name="experience_years" id="experience_years" min="0"
                                            value="<?= $this->e(AUTHGUARD()->doctor()->experience_years ?? '') ?>"
                                            class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500" />
                                        <?php if (isset($errors['experience_years'])) : ?>
                                        <div class="invalid-feedback"><?= $this->e($errors['experience_years']) ?></div>
                                        <?php endif ?>
                                    </div>
                                </div>

                                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                                    <div class="mb-4">
                                        <label for="dob"
                                            class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Ngày
                                            sinh</label>
                                        <input type="date" name="dob" id="dob"
                                            value="<?= $this->e(AUTHGUARD()->doctor()->date_of_birth ?? '') ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm
                                            leading-5.6 ease block w-full rounded-lg border border-gray-300 bg-white
                                            px-3 py-2 text-gray-700 outline-none transition-all
                                            placeholder:text-gray-500 focus:border-blue-500" />
                                        <?php if (isset($errors['dob'])) : ?>
                                        <div class="invalid-feedback"><?= $this->e($errors['dob']) ?></div>
                                        <?php endif ?>
                                    </div>

                                </div>

                                <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0 mt-4">
                                    <label for="avatar"
                                        class="ml-auto inline-block px-8 py-2 mb-4 font-bold leading-normal text-center text-white align-middle transition-all ease-in border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85 btn-custom">
                                        Upload Avatar
                                    </label>
                                    <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden" />
                                </div>

                            </div>


                            <hr class="my-4 border-t border-gray-200 dark:border-gray-700" />

                            <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm mb-3">Giới thiệu
                                bản thân</p>
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 shrink-0 md:w-full md:flex-0">
                                    <div class="mb-4">
                                        <label for="introduction"
                                            class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Giới
                                            thiệu</label>
                                        <textarea name="introduction" id="introduction" rows="4"
                                            class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500"
                                            placeholder="Viết đôi nét về bản thân, kinh nghiệm chuyên môn..."><?= $this->e(AUTHGUARD()->doctor()->introduction ?? '') ?></textarea>
                                        <?php if (isset($errors['introduction'])) : ?>
                                        <div class="invalid-feedback"><?= $this->e($errors['introduction']) ?></div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Nút lưu -->
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="ml-auto inline-block px-8 py-2 mb-4 font-bold leading-normal text-center text-white align-middle transition-all ease-in btn-custom border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">Lưu
                                    thông tin</button>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- Phần profile bên phải -->
                <div
                    class="w-full max-w-full px-3 mt-6 shrink-0 md:w-4/12 md:flex-0 md:mt-0 animate__animated animate__fadeInUp">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                        <img class="w-full rounded-t-2xl" src="../assets/img/about/ben-vien-hung-vuong-1-1-1024x580.jpg"
                            alt=" profile cover image">
                        <div class="flex flex-wrap justify-center -mx-3">
                            <div class="w-4/12 max-w-full px-3 flex-0 ">
                                <div class="mb-6 -mt-6 lg:mb-0 lg:-mt-16">
                                    <?php
                                    $avatar = AUTHGUARD()->user()->avatar ?? 'default.png';
                                    $avatarPath = "/assets/img/person/" . htmlspecialchars($avatar);
                                    ?>
                                    <img src="<?= $avatarPath ?>" alt="Ảnh đại diện"
                                        class="rounded-circle border border-white"
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                            </div>
                        </div>


                        <div class="flex-auto p-6 pt-0">
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 flex-1-0">
                                    <div class="flex justify-center">
                                        <div class="grid text-center">
                                            <span class="font-bold dark:text-white text-lg">22</span>
                                            <span
                                                class="dark:text-white/80 text-sm leading-normal uppercase dark:opacity-60">Bài
                                                báo</span>
                                        </div>
                                        <div class="grid px-6 text-center">
                                            <span class="font-bold dark:text-white text-lg">10K</span>
                                            <span
                                                class="dark:text-white/80 text-sm leading-normal uppercase dark:opacity-60">Người
                                                theo dõi</span>
                                        </div>
                                        <div class="grid text-center">
                                            <span class="font-bold dark:text-white text-lg">89</span>
                                            <span
                                                class="dark:text-white/80 text-sm leading-normal uppercase dark:opacity-60">Theo
                                                dõi</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr
                                class="h-px bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />
                            <div class="flex flex-wrap justify-center gap-4 mt-6">
                                <a href="javascript:;" class="text-slate-400 transition-all hover:text-slate-700">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="javascript:;" class="text-slate-400 transition-all hover:text-slate-700">
                                    <i class="fab fa-x-twitter"></i>
                                </a>
                                <a href="javascript:;" class="text-slate-400 transition-all hover:text-slate-700">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="javascript:;" class="text-slate-400 transition-all hover:text-slate-700">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php $this->stop() ?>