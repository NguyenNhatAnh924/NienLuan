<?php $this->layout("layouts/default", ["title" => CONGNGHE]) ?>

<?php $this->start("page") ?>
<style>
.custom-active {
    color: #018880 !important;
    text-decoration: underline;
}



.selected {
    background-color: #d1fae5;
    /* Xanh lá nhạt giống bg-green-100 của Tailwind */
    color: rgb(2, 56, 40);
    /* Màu chữ xanh đậm */
    border-radius: 8px;
    /* Bo góc nhẹ */
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    /* Đổ bóng nhẹ */
    transition: all 0.2s ease;
    /* Chuyển mượt khi chọn */
}

.calendar {
    width: 100%;
    max-width: 100%;
    background-color: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
}

.calendar-header {
    position: relative;
    background: linear-gradient(90deg, #016a6a, #018880, #01a099);
    background-size: 300% 300%;
    animation: gradientShift 5s ease infinite;
    color: white;
    padding: 20px;
    box-shadow: 0 0 20px rgba(1, 136, 128, 0.6);
    overflow: hidden;
}

/* Animation chuyển động gradient */
@keyframes gradientShift {
    0% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }

    100% {
        background-position: 0% 50%;
    }
}

/* Hiệu ứng ánh sáng lấp lánh */
.calendar-header::before {
    content: "";
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle at center, rgba(255, 255, 255, 0.2), transparent 70%);
    animation: shine 6s linear infinite;
    pointer-events: none;
    mix-blend-mode: screen;
}

@keyframes shine {
    0% {
        transform: rotate(0deg) translate(-50%, -50%);
    }

    100% {
        transform: rotate(360deg) translate(-50%, -50%);
    }
}

.calendar-header h1 {
    margin: 0;
    font-size: 1.5rem;
    text-transform: uppercase;
    color: white;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
}

.calendar-header p {
    margin: 0;
    font-size: 0.9rem;
}

.calendar-nav i {
    cursor: pointer;
    color: white;
    font-size: 1.2rem;
}

.weekdays,
.days {
    display: flex;
    flex-wrap: wrap;
    padding: 10px;
}

.weekdays div,
.days div {
    width: calc(100% / 7);
    text-align: center;
    padding: 10px 0;
    font-size: 0.9rem;
}

.days .today {
    background-color: #018880;
    color: white;
    border-radius: 50%;
    font-weight: bold;
    box-shadow: 0 0 10px rgba(1, 136, 128, 0.5);
}

.days div:hover {
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: 50%;
    cursor: pointer;
}

.text-muted {
    color: rgba(0, 0, 0, 0.3) !important;
}

.bg-transparent {
    background-color: transparent !important;
}
</style>

<main>
    <div
        class="absolute w-full top-0 bg-[url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg')] bg-cover bg-center min-h-75 dark-background">
        <!-- Lớp phủ dùng màu --background-color -->
        <span class="absolute top-0 left-0 w-full h-full"
            style="background-color: var(--background-color); opacity: 0.5;"></span>
    </div>
    <div class="container">
        <section class="align-items-center justify-content-center bg-light contact section">
            <div class="w-full px-6 py-6 mx-auto">

                <!-- cards row 2 -->
                <section class="flex flex-wrap mt-6 -mx-3 animate__animated animate__fadeInUp " id="section-statistics">
                    <!-- Section Title -->
                    <div class="container section-title" data-aos="fade-up">
                        <div><span>Thống kê lịch hẹn</span>
                        </div>
                    </div><!-- End Section Title -->
                    <div class="w-full max-w-full mt-0 lg:w-7/12 lg:flex-none">
                        <div
                            class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                            <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pt-4 pb-0">
                                <h6 class="capitalize dark:text-white">Biểu đồ </h6>
                                <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60">
                                    <i class="fa fa-arrow-up text-emerald-500"></i>
                                    <span class="font-semibold">Lịch hẹn trong năm 2025</span>
                                </p>
                            </div>
                            <div class="flex-auto p-4">
                                <div>
                                    <canvas id="chart-line" height="300"></canvas>
                                </div>
                                <div class="relative flex flex-row justify-between">
                                    <div class="flex flex-col items-center">
                                        <h6 class="mb-4 dark:text-white">Tỉ lệ trạng thái lịch hẹn</h6>
                                        <canvas id="statusChart" style="max-width: 300px; max-height: 220px;"></canvas>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <h6 class="mb-4 dark:text-white">Tỉ lệ tái khám / khám mới</h6>
                                        <canvas id="reexamChart" style="max-width: 300px; max-height: 220px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full max-w-full px-3 mt-0 lg:w-5/12 lg:flex-none">
                        <div
                            class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl border-black-125 rounded-2xl bg-clip-border">
                            <div class="p-4 pb-0 mb-0 rounded-t-4">
                                <h6 class="mb-2 dark:text-white">Thống kê lịch khám bệnh theo tháng</h6>
                            </div>
                            <div class="overflow-x-auto">
                                <table
                                    class="items-center w-full mb-4 align-top border-collapse border-gray-200 dark:border-white/40 text-sm text-left text-gray-700 dark:text-white table table-bordered table-striped text-center custom-striped-table">
                                    <thead style="background-color: #4ade80; color: white;"
                                        class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-white uppercase text-xs">
                                        <tr>
                                            <th class="px-4 py-3">Tháng</th>
                                            <th class="px-4 py-3 text-center">Hoàn thành</th>
                                            <th class="px-4 py-3 text-center">Đang chờ</th>
                                            <th class="px-4 py-3 text-center">Hủy</th>
                                            <th class="px-4 py-3 text-center">Tổng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($appointmentData as $item): ?>
                                        <tr class="border-b dark:border-white/40">
                                            <td class="px-4 py-2">Tháng <?= $item['month'] ?></td>
                                            <td class="px-4 py-2 text-center"><?= $item['completed_appointments'] ?>
                                            </td>
                                            <td class="px-4 py-2 text-center"><?= $item['pending_appointments'] ?>
                                            </td>
                                            <td class="px-4 py-2 text-center"><?= $item['cancelled_appointments'] ?>
                                            </td>
                                            <td class="px-4 py-2 text-center"><?= $item['total_appointments'] ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="container section-title" data-aos="fade-up">
                        <div><span>Danh sách lịch hẹn</span>
                        </div>
                    </div><!-- End Section Title -->
                    <div class="row justify-content-center animate__animated animate__fadeInUp">
                        <div class="col-lg-10">
                            <div class="bg-white rounded shadow p-3">
                                <div class="overflow-y-auto w-100">
                                    <div style="max-height: 500px; overflow-y: auto;">
                                        <div class="mb-4 bg-light p-3 rounded shadow-sm">
                                            <div class="row gx-2 gy-2 align-items-center">

                                                <div class="col-6 col-md">
                                                    <input type="text" id="filterPatientName" class="form-control"
                                                        placeholder="Tên bệnh nhân">
                                                </div>

                                                <div class="col-6 col-md">
                                                    <input type="text" id="filterPhone" class="form-control"
                                                        placeholder="Số điện thoại">
                                                </div>

                                                <div class="col-6 col-md">
                                                    <input type="text" id="filterAddress" class="form-control"
                                                        placeholder="Địa chỉ" oninput="filterAppointments()">
                                                </div>

                                                <div class="col-6 col-md">
                                                    <input type="date" id="filterDate" class="form-control">
                                                </div>

                                                <div class="col-6 col-md">
                                                    <select id="filterSession" class="form-select">
                                                        <option value="">Buổi</option>
                                                        <option value="Sáng">Sáng</option>
                                                        <option value="Chiều">Chiều</option>
                                                    </select>
                                                </div>

                                                <div class="col-6 col-md">
                                                    <select id="filterStatus" class="form-select">
                                                        <option value="">Trạng thái</option>
                                                        <option value="pending">Đang chờ</option>
                                                        <option value="completed">Hoàn thành</option>
                                                        <option value="cancelled">Đã hủy</option>
                                                    </select>
                                                </div>

                                                <div class="col-6 col-md-auto">
                                                    <button onclick="resetFilters()"
                                                        class="btn btn-outline-danger w-100">
                                                        <i class="fa-solid fa-rotate"></i>
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                        <table class="table table-bordered text-center mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Bệnh nhân</th>
                                                    <th>Địa chỉ</th> <!-- ✅ THÊM CỘT -->
                                                    <th>Ngày khám</th>
                                                    <th>Buổi khám</th>
                                                    <th>Tên bác sĩ</th>
                                                    <th>Trạng thái</th>
                                                    <th>Lịch sử bệnh án</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($appointments as $index => $appointment): ?>
                                                <tr>
                                                    <td>
                                                        <?= htmlspecialchars($appointment->patient_name) ?><br>
                                                        <small><?= htmlspecialchars($appointment->phone) ?></small>
                                                    </td>

                                                    <td
                                                        class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <?= $this->e($appointment->patient_address) ?>
                                                    </td> <!-- ✅ THÊM DÒNG -->

                                                    <td
                                                        class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <?= $this->e($appointment->appointment_date) ?>
                                                    </td>

                                                    <td
                                                        class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <?= $this->e($appointment->session) ?>
                                                    </td>

                                                    <td
                                                        class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <?= $this->e($appointment->doctor_name) ?>
                                                    </td>

                                                    <td>
                                                        <?php
                                                        $status = $appointment->status ?? 'Đang chờ';
                                                        $badgeClass = match ($status) {
                                                            'completed' => 'badge bg-success',
                                                            'pending' => 'badge bg-info',
                                                            'cancelled' => 'badge bg-danger',
                                                            default => 'bg-warning'
                                                        };
                                                        ?>
                                                        <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
                                                    </td>
                                                    <td
                                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <button type="button"
                                                            class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 hover:text-blue-500 underline view-history-btn"
                                                            data-bs-toggle="modal" data-bs-target="#historyRecordModal"
                                                            data-patient-id="<?= $appointment->patient_id ?>"
                                                            data-guest-id="<?= $appointment->guest_id ?>"
                                                            data-patient-type="<?= $appointment->patient_type ?>">
                                                            <i class="bi bi-clock-history fs-3"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- cards row 4 -->
                <section class="flex flex-wrap -mx-3 row animate__animated animate__fadeInUp bg-transparent"
                    id="section-employee-management">
                    <!-- Section Title -->
                    <div class="container section-title" data-aos="fade-up">
                        <div><span>Quản lý nhân sự</span>
                        </div>
                    </div><!-- End Section Title -->
                    <div class="col-lg-5 w-full max-w-full px-3 mt-0 mb-6 lg:w-7/12 lg:flex-none">
                        <div class="contact-form-wrapper">
                            <!-- Thanh menu nút căn giữa -->
                            <div class="d-flex justify-content-center gap-3 mb-4">
                                <a href="javascript:void(0);" id="btnAdd" onclick="showForm('formAdd')"
                                    class="text-dark fw-bold active">THÊM MỚI</a>
                                <a href="javascript:void(0);" id="btnEdit" onclick="showForm('formEdit')"
                                    class="text-dark fw-bold">CẬP NHẬT</a>
                            </div>

                            <!-- form thêm -->
                            <form action="/add-doctor-info" method="post" enctype="multipart/form-data"
                                class="php-email-form" id="formAdd">
                                <div class="row">
                                    <input type="hidden" name="password" value="123456">
                                    <input type="hidden" name="password_confirmation" value="123456">
                                    <div class="col-md-4 form-group">
                                        <label for="username">Username</label>
                                        <input type="text" name="username" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="name">Họ tên</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4 form-group">
                                        <label for="dob">Ngày sinh</label>
                                        <input type="date" name="dob" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="gender">Giới tính</label>
                                        <select name="gender" class="form-control" required>
                                            <option value="">-- Chọn --</option>
                                            <option value="Nam">Nam</option>
                                            <option value="Nữ">Nữ</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="position">Chức vụ</label>
                                        <select name="position" class="form-control" required>
                                            <option value="">-- Chọn --</option>
                                            <option value="doctor">Bác sĩ</option>
                                            <option value="reception">Lễ tân</option>
                                            <option value="admin">Quản trị</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4 form-group">
                                        <label for="status">Trạng thái</label>
                                        <select name="status" class="form-control" required>
                                            <option value="Đang làm việc">Đang làm việc</option>
                                            <option value="Nghỉ việc">Nghỉ việc</option>
                                            <option value="Nghỉ hưu">Nghỉ hưu</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="specialization">Chuyên khoa</label>
                                        <input type="text" name="specialization" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="working_time">Thời gian LV</label>
                                        <input type="text" name="working_time" class="form-control">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4 form-group">
                                        <label for="experience_years">Kinh nghiệm (năm)</label>
                                        <input type="number" name="experience_years" class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="phone">SĐT</label>
                                        <input type="tel" name="phone" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="address">Địa chỉ</label>
                                    <textarea name="address" class="form-control" rows="2" required></textarea>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="introduction">Giới thiệu</label>
                                    <textarea name="introduction" class="form-control" rows="2"></textarea>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="avatar">Ảnh đại diện</label>
                                    <input type="file" name="avatar" class="form-control">
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary">Thêm thông tin</button>
                                </div>
                            </form>
                            <!-- Form sủa -->
                            <form action="" method="post" enctype="multipart/form-data" class="php-email-form"
                                id="formEdit">
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="user_id">Mã NV</label>
                                        <input type="text" name="user_id" id="user_id" class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="username">Username</label>
                                        <input type="text" name="username" id="username" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="name">Họ tên</label>
                                        <input type="text" name="name" id="name" class="form-control" required>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4 form-group">
                                        <label for="dob">Ngày sinh</label>
                                        <input type="date" name="dob" id="dob" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="gender">Giới tính</label>
                                        <select name="gender" id="gender" class="form-control" required>
                                            <option value="">-- Chọn --</option>
                                            <option value="Nam">Nam</option>
                                            <option value="Nữ">Nữ</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="position">Chức vụ</label>
                                        <select name="position" id="position" class="form-control" required>
                                            <option value="">-- Chọn --</option>
                                            <option value="doctor">Bác sĩ</option>
                                            <option value="reception">Lễ tân</option>
                                            <option value="admin">Quản trị</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4 form-group">
                                        <label for="status">Trạng thái</label>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="Đang làm việc">Đang làm việc</option>
                                            <option value="Nghỉ việc">Nghỉ việc</option>
                                            <option value="Nghỉ hưu">Nghỉ hưu</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="specialization">Chuyên khoa</label>
                                        <input type="text" name="specialization" id="specialization"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="working_time">Thời gian LV</label>
                                        <input type="text" name="working_time" id="working_time" class="form-control">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4 form-group">
                                        <label for="experience_years">Kinh nghiệm (năm)</label>
                                        <input type="number" name="experience_years" id="experience_years"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="phone">SĐT</label>
                                        <input type="tel" name="phone" id="phone" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="address">Địa chỉ</label>
                                    <textarea name="address" id="address" class="form-control" rows="2"
                                        required></textarea>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="introduction">Giới thiệu</label>
                                    <textarea name="introduction" id="introduction" class="form-control"
                                        rows="2"></textarea>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="avatar">Ảnh đại diện</label>
                                    <input type="file" name="avatar" id="avatar" class="form-control">
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="bg-white p-6 rounded-2xl shadow-md dark:bg-slate-800">
                            <!-- Bộ lọc nhân viên -->
                            <div class="mb-3 bg-light p-3 rounded shadow-sm">
                                <div class="row gx-2 gy-2 align-items-center">

                                    <div class="col-6 col-md">
                                        <input type="text" id="filterEmployeeName" class="form-control"
                                            placeholder="Tên nhân viên">
                                    </div>

                                    <div class="col-6 col-md">
                                        <select id="filterGender" class="form-select">
                                            <option value="">Giới tính</option>
                                            <option value="Nam">Nam</option>
                                            <option value="Nữ">Nữ</option>
                                            <option value="Chưa rõ">Chưa rõ</option>
                                        </select>
                                    </div>

                                    <div class="col-6 col-md">
                                        <select id="filterPosition" class="form-select">
                                            <option value="">Chức vụ</option>
                                            <option value="Bác sĩ">Bác sĩ</option>
                                            <option value="Lễ tân">Lễ tân</option>
                                            <option value="Quản trị viên">Quản trị viên</option>
                                        </select>
                                    </div>

                                    <div class="col-6 col-md">
                                        <select id="filterStatus" class="form-select">
                                            <option value="">Trạng thái</option>
                                            <option value="Đang làm việc">Đang làm việc</option>
                                            <option value="Nghỉ việc">Nghỉ việc</option>
                                            <option value="Nghỉ hưu">Nghỉ hưu</option>
                                        </select>
                                    </div>

                                    <div class="col-6 col-md-auto">
                                        <button id="resetEmployeeFilters" class="btn btn-outline-danger w-100">
                                            <i class="fa-solid fa-rotate"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <h6 class="mb-4 font-semibold text-lg dark:text-white">Danh sách nhân viên</h6>
                            <div class="overflow-x-auto">
                                <table
                                    class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-left">
                                    <thead
                                        class="bg-gray-100 dark:bg-gray-700 text-xs font-semibold uppercase text-gray-700 dark:text-gray-200">
                                        <tr>
                                            <th class="px-4 py-3">STT</th>
                                            <th class="px-4 py-3">Tên nhân viên</th>
                                            <th class="px-4 py-3">Năm sinh</th>
                                            <th class="px-4 py-3">Giới tính</th>
                                            <th class="px-4 py-3">Chức vụ</th>
                                            <th class="px-4 py-3">Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-white">
                                        <?php foreach ($employerData as $index => $doctor): ?>
                                        <tr class="employee-row border-b dark:border-white/40"
                                            data-username="<?= $doctor->username ?>" data-id="<?= $doctor->user_id ?>"
                                            data-name="<?= htmlspecialchars($doctor->name) ?>"
                                            data-gender="<?= $doctor->gender ?>"
                                            data-dob="<?= $doctor->date_of_birth  ?>"
                                            data-position="<?= $doctor->role ?>"
                                            data-department="<?= $doctor->specialization ?? '' ?>"
                                            data-phone="<?= $doctor->phone ?>" data-email="<?= $doctor->email ?>"
                                            data-address="<?= htmlspecialchars($doctor->address ?? '') ?>"
                                            data-working_time="<?= htmlspecialchars($doctor->working_time ?? '') ?>"
                                            data-experience_years="<?= htmlspecialchars($doctor->experience_years ?? '') ?>"
                                            data-introduction=" <?= htmlspecialchars($doctor->introduction ?? '') ?>">
                                            <td class=" px-4 py-3"><?= $index + 1 ?></td>
                                            <td class=" px-4 py-3"><?= htmlspecialchars($doctor->name) ?></td>
                                            <td class="px-4 py-3">
                                                <?= isset($doctor->date_of_birth) ? date('Y', strtotime($doctor->date_of_birth)) : 'N/A' ?>
                                            </td>
                                            <td class="px-4 py-3"><?= htmlspecialchars($doctor->gender ?? 'Chưa rõ') ?>
                                            </td>
                                            <td class="px-4 py-3">
                                                <?php
                                                    switch ($doctor->role) {
                                                        case 'doctor':
                                                            echo 'Bác sĩ';
                                                            break;
                                                        case 'reception':
                                                            echo 'Lễ tân';
                                                            break;
                                                        case 'admin':
                                                            echo 'Quản trị viên';
                                                            break;
                                                        default:
                                                            echo ucfirst($doctor->role);
                                                    }
                                                    ?>
                                            </td>
                                            <td class="px-4 py-3">
                                                <?php
                                                    $colors = [
                                                        'Đang làm việc' => 'bg-green-100 text-green-800',
                                                        'Nghỉ việc'     => 'bg-yellow-100 text-yellow-800',
                                                        'Nghỉ hưu'      => 'bg-red-100 text-red-800',
                                                    ];
                                                    $text = $doctor->work_status ?? 'Đang làm việc'; // giá trị mặc định nếu null
                                                    $badgeClass = $colors[$text] ?? 'bg-gray-100 text-gray-800';
                                                    ?>
                                                <span
                                                    class="inline-block px-2 py-1 text-xs font-medium rounded-full <?= $badgeClass ?>">
                                                    <?= $text ?>
                                                </span>

                                            </td>
                                        </tr>
                                        <?php endforeach; ?>

                                        <!-- Thêm dòng dữ liệu khác ở đây -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </section>
                <!-- cards row 5-->
                <section class="flex flex-wrap -mx-3 animate__animated animate__fadeInUp bg-transparent"
                    id="section-service-management">
                    <!-- Section Title -->
                    <div class="container section-title" data-aos="fade-up">
                        <div><span>Quản lý dịch vụ</span>
                        </div>
                    </div><!-- End Section Title -->
                    <!-- Bảng dịch vụ (7/12 cột) -->
                    <div class="w-full max-w-full px-3 mt-0 mb-6 lg:w-7/12 lg:flex-none">
                        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl 
                            dark:bg-slate-850 dark:shadow-dark-xl border-black-125 rounded-2xl bg-clip-border">
                            <!-- Bộ lọc dịch vụ -->
                            <div class="mb-3 bg-light p-3 rounded shadow-sm mt-2">
                                <div class="row gx-2 gy-2 align-items-center">

                                    <div class="col-6 col-md">
                                        <input type="text" id="filterServiceName" class="form-control"
                                            placeholder="Tên dịch vụ">
                                    </div>

                                    <div class="col-6 col-md">
                                        <select id="filterServiceStatus" class="form-select">
                                            <option value="">Trạng thái</option>
                                            <option value="Sẵn sàng">Sẵn sàng</option>
                                            <option value="Ngừng hoạt động">Ngừng hoạt động</option>
                                        </select>
                                    </div>

                                    <div class="col-6 col-md-auto">
                                        <button id="resetServiceFilters" class="btn btn-outline-danger w-100">
                                            <i class="fa-solid fa-rotate"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <div class="p-4 pb-0 mb-0 rounded-t-4">
                                <h6 class="mb-2 dark:text-white">Quản lý dịch vụ</h6>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="items-center w-full mb-4 align-top border-collapse border-gray-200 
                                    dark:border-white/40 text-sm text-left text-gray-700 dark:text-white">
                                    <thead
                                        class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-white uppercase text-xs">
                                        <tr>
                                            <th class="px-4 py-3">STT</th>
                                            <th class="px-4 py-3">Tên dịch vụ</th>
                                            <th class="px-4 py-3">Giá</th>
                                            <th class="px-4 py-3">Trạng thái</th>
                                            <th class="px-4 py-3">Ngừng dịch vụ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($serviceData as $index => $service): ?>
                                        <tr class="service-row border-b dark:border-white/40"
                                            data-id="<?= $service['id'] ?>"
                                            data-name="<?= htmlspecialchars($service['name'] ?? '') ?>"
                                            data-description="<?= htmlspecialchars($service['description'] ?? '') ?>"
                                            data-price="<?= $service['price'] ?>"
                                            data-is_active="<?= $service['is_active'] ?>">

                                            <td class="px-4 py-2"><?= $index + 1 ?></td>
                                            <td class="px-4 py-2"><?= htmlspecialchars($service['name'] ?? '') ?></td>
                                            <td class="px-4 py-2"><?= number_format($service['price'], 0, ',', '.') ?>đ
                                            </td>
                                            <td class="px-4 py-2 text-green-600">
                                                <?= $service['is_active'] ? 'Sẵn sàng' : 'Ngừng hoạt động' ?>
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <div class="flex justify-center items-center">
                                                    <form method="POST" action="/toggle-service/<?= $service['id'] ?>">
                                                        <button type="submit" class="btn btn-sm"
                                                            title="<?= $service['is_active'] ? 'Ngừng dịch vụ' : 'Kích hoạt lại' ?>">
                                                            <?php if ($service['is_active']): ?>
                                                            <i class="bi bi-check-circle fs-4 text-success"></i>
                                                            <?php else: ?>
                                                            <i class="bi bi-sign-stop fs-4 text-danger"></i>
                                                            <?php endif; ?>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <!-- Thêm dịch vụ khác nếu cần -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!--Quản lý lịch hẹn-->
                    <div class="col-lg-5">
                        <div class="contact-form-wrapper">
                            <!-- Thanh menu nút căn giữa -->
                            <div class="d-flex justify-content-center gap-3 mb-4">
                                <a href="javascript:void(0);" id="btnAddService"
                                    onclick="showFormService('formAddService')" class="text-dark fw-bold active">THÊM
                                    MỚI</a>
                                <a href="javascript:void(0);" id="btnEditService"
                                    onclick="showFormService('formEditService')" class="text-dark fw-bold">CẬP NHẬT</a>
                            </div>
                            <form action="forms/add-service" method="post" class="php-form" id="formAddService">

                                <div class="form-group">
                                    <label for="service_name">Tên dịch vụ</label>
                                    <input type="text" name="service_name" class="form-control" id="service_name"
                                        placeholder="Khám tai mũi họng" required>
                                </div>

                                <div class="form-group">
                                    <label for="service_description">Mô tả dịch vụ</label>
                                    <input type="text" name="description" class="form-control" id="service_description"
                                        placeholder="Khám tai mũi họng" required>
                                </div>


                                <div class="form-group mt-3">
                                    <label for="service_price">Giá dịch vụ (VNĐ)</label>
                                    <input type="number" name="service_price" class="form-control" id="service_price"
                                        placeholder="500000" required>
                                </div>

                                <div class="form-group">
                                    <label for="is_active">Trạng thái</label>
                                    <select name="is_active" id="is_active" class="form-control" required>
                                        <option value="1">Sẵn sàng</option>
                                        <option value="0">Ngừng dịch vụ</option>
                                    </select>
                                </div>


                                <div class="text-center mt-4">
                                    <button type="submit">Lưu dịch vụ</button>
                                </div>
                            </form>

                            <form action="forms/edit-service" method="post" class="php-form" id="formEditService">
                                <input type="hidden" name="service_id" id="edit_service_id">

                                <div class="form-group">
                                    <label for="edit_service_name">Tên dịch vụ</label>
                                    <input type="text" name="service_name" class="form-control" id="edit_service_name"
                                        placeholder="Khám tai mũi họng" required>
                                </div>

                                <div class="form-group">
                                    <label for="edit_service_description">Mô tả dịch vụ</label>
                                    <input type="text" name="description" class="form-control"
                                        id="edit_service_description" placeholder="Khám tổng quát" required>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="edit_service_price">Giá dịch vụ (VNĐ)</label>
                                    <input type="number" name="service_price" class="form-control"
                                        id="edit_service_price" placeholder="500000" required>
                                </div>

                                <div class="form-group">
                                    <label for="edit_is_active">Trạng thái</label>
                                    <select name="is_active" id="edit_is_active" class="form-control" required>
                                        <option value="1">Sẵn sàng</option>
                                        <option value="0">Ngừng dịch vụ</option>
                                    </select>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                <!-- cards row 6 -->
                <section class="flex flex-wrap -mx-3 row animate__animated animate__fadeInUp bg-transparent"
                    id="section-schedule-management">
                    <!-- Section Title -->
                    <div class="container section-title" data-aos="fade-up">
                        <div><span>Quản lý lịch làm việc</span>
                        </div>
                    </div><!-- End Section Title -->
                    <div class="col-lg-5 w-full max-w-full mt-0 mb-6 lg:w-7/12 lg:flex-none">
                        <div class="calendar shadow">
                            <div class="calendar-header d-flex justify-content-between align-items-center px-3">
                                <div class="calendar-nav prev">
                                    <i class="fas fa-chevron-left"></i>
                                </div>
                                <div class="text-center content">
                                    <h1>Tháng</h1>
                                    <p></p>
                                </div>
                                <div class="calendar-nav next">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                            <div class="weekdays border-top border-bottom bg-light">
                                <div>Hai</div>
                                <div>Ba</div>
                                <div>Tư</div>
                                <div>Năm</div>
                                <div>Sáu</div>
                                <div>Bảy</div>
                                <div>CN</div>
                            </div>
                            <div class="days"></div>
                        </div>

                        <div class="contact-form-wrapper mt-3">
                            <!-- Thanh menu nút căn giữa -->
                            <div class="d-flex justify-content-center gap-3 mb-4">
                                <a href="javascript:void(0);" id="btnAddSchedule"
                                    onclick="showFormSchedule('formAddSchedule')" class="text-dark fw-bold active">THÊM
                                    MỚI</a>
                                <a href="javascript:void(0);" id="btnEditSchedule"
                                    onclick="showFormSchedule('formEditSchedule')" class="text-dark fw-bold">CẬP
                                    NHẬT</a>
                            </div>

                            <!-- form thêm -->
                            <form action="/doctor/schedule/create" method="post" enctype="multipart/form-data"
                                class="php-form" id="formAddSchedule">
                                <input type="hidden" name="scheduleID" value="">
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="doctor_id">Bác sĩ</label>
                                        <select name="doctor_id" class="form-control" required>
                                            <option value="">-- Chọn bác sĩ --</option>
                                            <?php foreach ($employerData as $doctor): ?>
                                            <option value="<?= $doctor->user_id ?>">
                                                <?= "ID: {$doctor->user_id} - {$doctor->name}" ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="specific_date">Ngày làm việc</label>
                                        <input type="date" name="specific_date" class="form-control" required>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="session_id">Buổi làm việc:</label>
                                        <select name="session_id" id="session_id" class="form-control" required>
                                            <option value="">-- Chọn buổi làm việc --</option>
                                            <?php foreach ($work_sessions as $session): ?>
                                            <option value="<?= $session['id'] ?>">
                                                <?= htmlspecialchars($session['name']) ?>
                                                (<?= htmlspecialchars($session['start_time']) ?> -
                                                <?= htmlspecialchars($session['end_time']) ?>)
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="status">Trạng thái</label>
                                        <select name="status" class="form-control" required>
                                            <option value="">-- Chọn ca --</option>
                                            <option value="pending">Đang xử lý</option>
                                            <option value="approved">Xác nhận</option>
                                            <option value="cancelled">Hủy</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="notes">Ghi chú</label>
                                    <textarea name="notes" class="form-control" rows="2"
                                        placeholder="Ví dụ: nghỉ, bận, thay đổi giờ..."></textarea>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-success">Thêm lịch</button>
                                </div>
                            </form>
                            <!-- Form sủa -->
                            <form action="" method="post" enctype="multipart/form-data" class="php-form d-none"
                                id="formEditSchedule">
                                <input type="hidden" name="scheduleID" id="edit_schedule_id">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="doctor_info">Thông tin bác sĩ</label>
                                        <input type="text" id="edit_doctor_info" class="form-control" readonly>
                                        <input type="hidden" name="doctor_id" id="edit_doctor_id">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="specific_date">Ngày làm việc</label>
                                        <input type="date" name="specific_date" id="edit_specific_date"
                                            class="form-control" required>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="session_id">Buổi làm việc:</label>
                                        <select name="session_id" id="edit_session_id" class="form-control" required>
                                            <option value="">-- Chọn buổi làm việc --</option>
                                            <?php foreach ($work_sessions as $session): ?>
                                            <option value="<?= $session['id'] ?>">
                                                <?= htmlspecialchars($session['name']) ?>
                                                (<?= htmlspecialchars($session['start_time']) ?> -
                                                <?= htmlspecialchars($session['end_time']) ?>)
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="status">Trạng thái</label>
                                        <select name="status" id="edit_status" class="form-control" required>
                                            <option value="pending">Chờ duyệt</option>
                                            <option value="approved">Đã duyệt</option>
                                            <option value="rejected">Từ chối</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="notes">Ghi chú</label>
                                    <textarea name="notes" id="edit_notes" class="form-control" rows="2"
                                        placeholder="Cập nhật nội dung ghi chú..."></textarea>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary">Cập nhật lịch</button>
                                </div>
                            </form>

                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="bg-white p-6 rounded-2xl shadow-md dark:bg-slate-800">
                            <div class="mb-4 bg-light p-3 rounded shadow-sm">
                                <div class="row gx-2 gy-2 align-items-center">

                                    <div class="col-6 col-md">
                                        <input type="text" id="filterId" class="form-control" placeholder="Mã">
                                    </div>

                                    <div class="col-6 col-md">
                                        <input type="text" id="filterName" class="form-control" placeholder="Tên">
                                    </div>

                                    <div class="col-6 col-md">
                                        <input type="date" id="filterDate" class="form-control">
                                    </div>

                                    <div class="col-6 col-md">
                                        <select id="filterSession" class="form-select">
                                            <option value="">Buổi</option>
                                            <option value="Sáng">Sáng</option>
                                            <option value="Chiều">Chiều</option>
                                            <option value="Tối">Tối</option>
                                        </select>
                                    </div>

                                    <div class="col-6 col-md">
                                        <select id="filterStatus" class="form-select">
                                            <option value="">Trạng thái</option>
                                            <option value="approved">Approved</option>
                                            <option value="pending">Pending</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                    </div>

                                    <div class="col-6 col-md-auto">
                                        <button id="resetFilters" class="btn btn-outline-danger w-100">
                                            <i class="fa-solid fa-rotate"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>



                            <h6 class="mb-4 font-semibold text-lg dark:text-white">Danh sách lịch làm việc</h6>
                            <div class="overflow-x-auto" style="max-height: 525px;">
                                <table class=" min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm
                                text-left" id="scheduleTable">
                                    <thead
                                        class="bg-gray-100 dark:bg-gray-700 text-xs font-semibold uppercase text-gray-700 dark:text-gray-200">
                                        <tr>
                                            <th class="px-4 py-3">STT</th>
                                            <th class="px-4 py-3">Mã NV</th>
                                            <th class="px-4 py-3">Tên nhân viên</th>
                                            <th class="px-4 py-3">Ngày làm việc</th>
                                            <th class="px-4 py-3">Buổi làm</th>
                                            <th class="px-4 py-3">Trạng thái</th>
                                        </tr>
                                    </thead>

                                    <tbody
                                        class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-white">
                                        <?php foreach ($doctor_WorkSchedule as $index => $schedule): ?>
                                        <tr class="schedule-row border-b dark:border-white/40"
                                            data-schedule-id="<?= $schedule['id'] ?>"
                                            data-doctor-id="<?= $schedule['doctor_id'] ?>"
                                            data-name="<?= htmlspecialchars($schedule['doctor_name']) ?>"
                                            data-date="<?= $schedule['specific_date'] ?>"
                                            data-session="<?= $schedule['session_id'] ?>"
                                            data-status="<?= $schedule['status'] ?>"
                                            data-note="<?= htmlspecialchars($schedule['notes'] ?? '') ?>">
                                            <td class="px-4 py-3"><?= $index + 1 ?></td>
                                            <td class="px-4 py-3"><?= $schedule['doctor_id'] ?></td>
                                            <td class="px-4 py-3"><?= htmlspecialchars($schedule['doctor_name']) ?></td>
                                            <td class="px-4 py-3"><?= $schedule['specific_date'] ?></td>
                                            <td class="px-4 py-3"><?= $schedule['session_name'] ?></td>
                                            <td class="px-4 py-3">
                                                <?php
                                                    $colors = [
                                                        'approved' => 'bg-green-100 text-green-800',
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'rejected' => 'bg-red-100 text-red-800',
                                                    ];
                                                    $status = $schedule['status'];
                                                    $badgeClass = $colors[$status] ?? 'bg-gray-100 text-gray-800';
                                                    ?>
                                                <span
                                                    class="inline-block px-2 py-1 text-xs font-medium rounded-full <?= $badgeClass ?>">
                                                    <?= ucfirst($status) ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-md dark:bg-slate-800 mt-3">
                            <h6 class="mb-4 font-semibold text-lg dark:text-white">Số buổi làm việc</h6>
                            <div class="overflow-x-auto" style="max-height: 525px;">
                                <table class=" min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm
                                text-left">
                                    <thead
                                        class="bg-gray-100 dark:bg-gray-700 text-xs font-semibold uppercase text-gray-700 dark:text-gray-200">
                                        <tr>
                                            <th class="px-4 py-3">STT</th>
                                            <th class="px-4 py-3">Tên bác sĩ</th>
                                            <th class="px-4 py-3">Số ngày làm việc trong tháng</th>
                                        </tr>
                                    </thead>

                                    <tbody
                                        class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-white">
                                        <?php foreach ($doctor_dws as $index => $doctor): ?>
                                        <tr class="schedule-row border-b dark:border-white/40 text-center">
                                            <td class="px-4 py-3"><?= $index + 1 ?></td>
                                            <td class="px-4 py-3"><?= htmlspecialchars($doctor['user_name']) ?></td>
                                            <td class="px-4 py-3"><?= $doctor['work_sessions'] ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </section>
    </div>
    <!-- Modal xem lịch sử bệnh án -->
    <div class="modal fade" id="historyRecordModal" tabindex="-1" aria-labelledby="historyRecordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyRecordModalLabel">Lịch sử bệnh án</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body" id="historyContent">
                    <!-- Nội dung sẽ được đổ vào bằng JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal con hiển thị đơn thuốc -->
    <div class="modal fade" id="prescriptionModal" tabindex="-1" aria-labelledby="prescriptionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết đơn thuốc</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body" id="prescriptionContent">
                    <!-- Nội dung đơn thuốc sẽ hiển thị ở đây -->
                </div>
            </div>
        </div>
    </div>
</main>
<script>
const appointmentData = <?= json_encode($appointmentData) ?>;
const statusStats = <?= json_encode($statustData) ?>;
const followUpStats = <?= json_encode($folow_upData) ?>;
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
// chart-line
if (document.querySelector("#chart-line")) {
    const ctxLine = document.getElementById("chart-line").getContext("2d");

    const labels = appointmentData.map(item => 'Tháng ' + item.month);
    const totalData = appointmentData.map(item => item.total_appointments);

    const gradientStroke1 = ctxLine.createLinearGradient(0, 230, 0, 50);
    gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
    gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');

    new Chart(ctxLine, {
        type: "line",
        data: {
            labels: labels,
            datasets: [{
                label: "Tổng lịch hẹn",
                tension: 0.4,
                pointRadius: 0, // 👈 Ẩn chấm
                pointHoverRadius: 5, // 👈 Hiện khi rê chuột
                pointBackgroundColor: "#5e72e4",
                borderColor: "#5e72e4",
                backgroundColor: gradientStroke1,
                borderWidth: 3,
                fill: true,
                data: totalData,
                maxBarThickness: 6
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: "#333"
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        padding: 10,
                        color: '#555',
                        font: {
                            size: 11,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        color: '#999',
                        padding: 20,
                        font: {
                            size: 11,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        }
                    }
                }
            }
        }
    });
}
// statusChart
if (document.querySelector("#statusChart")) {
    const ctxStatus = document.getElementById("statusChart").getContext("2d");
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Đã hoàn thành', 'Đang chờ', 'Đã hủy'],
            datasets: [{
                label: 'Trạng thái lịch hẹn',
                data: [
                    statusStats.completed || 0,
                    statusStats.pending || 0,
                    statusStats.cancelled || 0,
                ],
                backgroundColor: ['#4ade80', '#38bdf8', '#f87171'],
                hoverOffset: 30,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#000'
                    }
                }
            }
        }
    });
}

// reexamChart
if (document.querySelector("#reexamChart")) {
    const ctxReexam = document.getElementById("reexamChart").getContext("2d");
    new Chart(ctxReexam, {
        type: 'doughnut',
        data: {
            labels: ['Khám mới', 'Tái khám'],
            datasets: [{
                label: 'Tỉ lệ tái khám / khám mới',
                data: [
                    followUpStats.new || 0,
                    followUpStats.follow_up || 0
                ],
                backgroundColor: ['#60a5fa', '#fbbf24'],
                hoverOffset: 30,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#000'
                    }
                }
            }
        }
    });
}
</script>
<script>
// Hiển thị form nhân viên: thêm hoặc sửa
function showForm(formId) {
    const formAdd = document.getElementById('formAdd');
    const formEdit = document.getElementById('formEdit');
    const btnAdd = document.getElementById('btnAdd');
    const btnEdit = document.getElementById('btnEdit');

    const isAdd = formId === 'formAdd';

    formAdd.classList.toggle('d-none', !isAdd);
    formEdit.classList.toggle('d-none', isAdd);

    btnAdd.classList.toggle('custom-active', isAdd);
    btnEdit.classList.toggle('custom-active', !isAdd);
}

// Hiển thị form dịch vụ: thêm hoặc sửa
function showFormService(formId) {
    const formAdd = document.getElementById('formAddService');
    const formEdit = document.getElementById('formEditService');
    const btnAdd = document.getElementById('btnAddService');
    const btnEdit = document.getElementById('btnEditService');

    const isAdd = formId === 'formAddService';

    formAdd.classList.toggle('d-none', !isAdd);
    formEdit.classList.toggle('d-none', isAdd);

    btnAdd.classList.toggle('custom-active', isAdd);
    btnEdit.classList.toggle('custom-active', !isAdd);
}

// Hiển thị form lịch làm việc: thêm hoặc sửa
function showFormSchedule(formId) {
    const formAdd = document.getElementById('formAddSchedule');
    const formEdit = document.getElementById('formEditSchedule');
    const btnAdd = document.getElementById('btnAddSchedule');
    const btnEdit = document.getElementById('btnEditSchedule');

    const isAdd = formId === 'formAddSchedule';

    formAdd.classList.toggle('d-none', !isAdd);
    formEdit.classList.toggle('d-none', isAdd);

    btnAdd.classList.toggle('custom-active', isAdd);
    btnEdit.classList.toggle('custom-active', !isAdd);
}

document.addEventListener('DOMContentLoaded', function() {
    // Gán dữ liệu khi chọn dòng nhân viên
    document.querySelectorAll('.employee-row').forEach(row => {
        row.addEventListener('click', function() {
            const form = document.getElementById('formEdit');
            const id = this.dataset.id;
            const role = this.dataset.position;

            form.action = `/update-doctor-info/${id}/${role}`;
            showForm('formEdit');

            form.querySelector('[name="user_id"]').value = id;
            form.querySelector('[name="username"]').value = this.dataset.username;
            form.querySelector('[name="name"]').value = this.dataset.name;
            form.querySelector('[name="dob"]').value = this.dataset.dob;
            form.querySelector('[name="gender"]').value = this.dataset.gender;
            form.querySelector('[name="position"]').value = this.dataset.position;
            form.querySelector('[name="status"]').value = this.dataset.status ||
                'Đang làm việc';
            form.querySelector('[name="specialization"]').value = this.dataset.department;
            form.querySelector('[name="phone"]').value = this.dataset.phone;
            form.querySelector('[name="email"]').value = this.dataset.email;
            form.querySelector('[name="address"]').value = this.dataset.address;
            form.querySelector('[name="working_time"]').value = this.dataset.working_time || '';
            form.querySelector('[name="experience_years"]').value = this.dataset
                .experience_years || '';
            form.querySelector('[name="introduction"]').value = this.dataset.introduction || '';

            document.querySelectorAll('.employee-row').forEach(r => r.classList.remove(
                'selected'));
            this.classList.add('selected');
        });
    });

    // Gán dữ liệu khi chọn dòng dịch vụ
    document.querySelectorAll('.service-row').forEach(row => {
        row.addEventListener('click', function() {
            document.querySelectorAll('.service-row').forEach(r => r.classList.remove(
                'selected'));
            this.classList.add('selected');

            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const description = this.getAttribute('data-description');
            const price = this.getAttribute('data-price');
            const isActive = this.getAttribute('data-is_active');

            showFormService('formEditService');

            const form = document.getElementById('formEditService');
            form.querySelector('[name="service_id"]').value = id;
            form.querySelector('[name="service_name"]').value = name;
            form.querySelector('[name="description"]').value = description;
            form.querySelector('[name="service_price"]').value = price;
            form.querySelector('[name="is_active"]').value = isActive;
        });
    });

    // Gán dữ liệu khi chọn dòng lịch hẹn
    document.querySelectorAll('.schedule-row').forEach(row => {
        row.addEventListener('click', function() {
            showFormSchedule('formEditSchedule');

            const scheduleId = this.dataset.scheduleId;
            const doctorId = this.dataset.doctorId;
            const doctorName = this.dataset.name;
            const specificDate = this.dataset.date;
            const sessionId = this.dataset.session;
            const status = this.dataset.status;
            const note = this.dataset.note;

            // Gán dữ liệu vào form
            document.getElementById('edit_schedule_id').value = scheduleId;
            document.getElementById('edit_doctor_info').value =
                `ID: ${doctorId} - ${doctorName}`;
            document.getElementById('edit_doctor_id').value = doctorId;
            document.getElementById('edit_specific_date').value = specificDate;
            document.getElementById('edit_session_id').value = sessionId;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_notes').value = note;

            // ✅ Gán đúng action cho form update
            document.getElementById('formEditSchedule').action =
                `/edit-doctor-schedule`;

            // Giao diện
            document.querySelectorAll('.schedule-row').forEach(r => r.classList.remove(
                'selected'));
            this.classList.add('selected');

            document.getElementById('formEditSchedule').scrollIntoView({
                behavior: 'smooth'
            });
        });
    });


    // Mặc định hiển thị form thêm mới
    showForm('formAdd'); // hoặc 'formEdit' tùy ý
    showFormService('formAddService'); // hoặc 'formEditService' tùy ý
    showFormSchedule('formAddSchedule');
});
</script>

<script>
const schedules = <?= json_encode($doctor_WorkSchedule ?? []) ?>;
</script>

<script>
const date = new Date();

const renderCalendar = () => {
    date.setDate(1);

    const monthDays = document.querySelector(".days");
    const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
    let firstDayIndex = date.getDay();
    firstDayIndex = firstDayIndex === 0 ? 7 : firstDayIndex;

    const prevLastDay = new Date(date.getFullYear(), date.getMonth(), 0).getDate();
    const lastDayIndex = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDay();
    const nextDays = 7 - (lastDayIndex === 0 ? 7 : lastDayIndex);

    const months = [
        "Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6",
        "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"
    ];

    document.querySelector('.content h1').innerHTML = months[date.getMonth()];
    document.querySelector('.content p').innerHTML = new Date().toLocaleDateString('vi-VN');

    let days = "";

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    // Ngày của tháng trước (nếu muốn hiển thị)
    for (let x = firstDayIndex - 1; x > 0; x--) {
        days += `<div class="text-muted">${prevLastDay - x + 1}</div>`;
    }

    // Ngày hiện tại của tháng
    for (let i = 1; i <= lastDay; i++) {
        const currentDate = new Date(date.getFullYear(), date.getMonth(), i);
        currentDate.setHours(0, 0, 0, 0);
        const currentDateStr = currentDate.getFullYear() + '-' +
            String(currentDate.getMonth() + 1).padStart(2, '0') + '-' +
            String(currentDate.getDate()).padStart(2, '0');
        const matched = schedules.find(s => s.specific_date === currentDateStr);
        const isToday = currentDate.getTime() === today.getTime();

        if (isToday && matched) {
            // Hôm nay có lịch
            days += `<div style="background:#018880;color:white;border-radius:50%;font-weight:bold;">${i}</div>`;
        } else if (isToday && !matched) {
            // Hôm nay không có lịch
            days += `<div style="background:#ccc;color:white;border-radius:50%;font-weight:bold;">${i}</div>`;
        } else if (matched) {
            // Có lịch nhưng không phải hôm nay
            let color = "#018880";
            if (currentDate < today) color = "#005e5e"; // Quá khứ
            else if (currentDate > today) color = "#90e0dd"; // Tương lai

            days += `<div style="background:${color};color:white;border-radius:50%;font-weight:bold;">${i}</div>`;
        } else {
            // Không có gì đặc biệt
            days += `<div>${i}</div>`;
        }
    }

    // Ngày của tháng sau (nếu muốn hiển thị)
    for (let j = 1; j <= nextDays; j++) {
        days += `<div class="text-muted">${j}</div>`;
    }

    monthDays.innerHTML = days;
};

// Nút điều hướng
document.querySelector('.prev').addEventListener('click', () => {
    date.setMonth(date.getMonth() - 1);
    renderCalendar();
});

document.querySelector('.next').addEventListener('click', () => {
    date.setMonth(date.getMonth() + 1);
    renderCalendar();
});

// Khởi tạo
renderCalendar();
</script>

<script>
// Chọn tất cả trường lọc
const inputs = document.querySelectorAll('#filterId, #filterName, #filterDate, #filterSession, #filterStatus');
const resetButton = document.getElementById('resetFilters');

// Gán sự kiện cho tất cả trường lọc
inputs.forEach(input => {
    input.addEventListener('input', filterTable);
    input.addEventListener('change', filterTable);
});

// Xử lý nút Xóa bộ lọc
resetButton.addEventListener('click', () => {
    inputs.forEach(input => input.value = '');
    filterTable();
});

function filterTable() {
    const filterId = document.getElementById('filterId').value.toLowerCase();
    const filterName = document.getElementById('filterName').value.toLowerCase();
    const filterDate = document.getElementById('filterDate').value;
    const filterSession = document.getElementById('filterSession').value;
    const filterStatus = document.getElementById('filterStatus').value;

    // Duyệt từng dòng bảng
    const rows = document.querySelectorAll('#scheduleTable tbody tr');

    rows.forEach(row => {
        const id = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const name = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const date = row.querySelector('td:nth-child(4)').textContent;
        const session = row.querySelector('td:nth-child(5)').textContent.trim();
        const status = row.querySelector('td:nth-child(6) span').textContent.trim().toLowerCase();

        const matchId = id.includes(filterId);
        const matchName = name.includes(filterName);
        const matchDate = !filterDate || date === filterDate;
        const matchSession = !filterSession || session === filterSession;
        const matchStatus = !filterStatus || status === filterStatus;

        // Hiển thị hoặc ẩn dòng tương ứng
        row.style.display = (matchId && matchName && matchDate && matchSession && matchStatus) ? '' : 'none';
    });
}
</script>

<script>
const serviceNameInput = document.getElementById('filterServiceName');
const serviceStatusSelect = document.getElementById('filterServiceStatus');
const resetServiceButton = document.getElementById('resetServiceFilters');

// Lắng nghe sự kiện thay đổi
serviceNameInput.addEventListener('input', filterServices);
serviceStatusSelect.addEventListener('change', filterServices);

resetServiceButton.addEventListener('click', () => {
    serviceNameInput.value = '';
    serviceStatusSelect.value = '';
    filterServices();
});

function filterServices() {
    const nameFilter = serviceNameInput.value.toLowerCase();
    const statusFilter = serviceStatusSelect.value;

    const rows = document.querySelectorAll('.service-row');

    rows.forEach(row => {
        const serviceName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const serviceStatus = row.querySelector('td:nth-child(4)').textContent.trim();

        const matchName = serviceName.includes(nameFilter);
        const matchStatus = !statusFilter || serviceStatus === statusFilter;

        row.style.display = (matchName && matchStatus) ? '' : 'none';
    });
}
</script>

<script>
const employeeNameInput = document.getElementById('filterEmployeeName');
const genderSelect = document.getElementById('filterGender');
const positionSelect = document.getElementById('filterPosition');
const statusSelect = document.getElementById('filterStatus');
const resetEmployeeButton = document.getElementById('resetEmployeeFilters');

employeeNameInput.addEventListener('input', filterEmployees);
genderSelect.addEventListener('change', filterEmployees);
positionSelect.addEventListener('change', filterEmployees);
statusSelect.addEventListener('change', filterEmployees);

resetEmployeeButton.addEventListener('click', () => {
    employeeNameInput.value = '';
    genderSelect.value = '';
    positionSelect.value = '';
    statusSelect.value = '';
    filterEmployees();
});

function filterEmployees() {
    const nameFilter = employeeNameInput.value.toLowerCase();
    const genderFilter = genderSelect.value;
    const positionFilter = positionSelect.value;
    const statusFilter = statusSelect.value;

    const rows = document.querySelectorAll('.employee-row');

    rows.forEach(row => {
        const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const gender = row.querySelector('td:nth-child(4)').textContent.trim();
        const position = row.querySelector('td:nth-child(5)').textContent.trim();
        const status = row.querySelector('td:nth-child(6) span').textContent.trim();

        const matchName = name.includes(nameFilter);
        const matchGender = !genderFilter || gender === genderFilter;
        const matchPosition = !positionFilter || position === positionFilter;
        const matchStatus = !statusFilter || status === statusFilter;

        row.style.display = (matchName && matchGender && matchPosition && matchStatus) ? '' : 'none';
    });
}
</script>

<script>
// Lấy dữ liệu từ PHP
const allMedicalHistory = <?= json_encode($medicalHistory, JSON_UNESCAPED_UNICODE) ?>;
const allPrescriptions = <?= json_encode($dataMedicalHistoryMedical ?? [], JSON_UNESCAPED_UNICODE) ?>;


document.querySelectorAll('.view-history-btn').forEach(button => {
    button.addEventListener('click', function() {
        const historyContainer = document.getElementById('historyContent'); // BỔ SUNG DÒNG NÀY
        const patientType = this.getAttribute('data-patient-type');
        const patientId = this.getAttribute('data-patient-id');
        const guestId = this.getAttribute('data-guest-id');

        let records = [];
        if (patientType === 'user') {
            records = allMedicalHistory.filter(item => item.patient_id == patientId);
        } else if (patientType === 'guest') {
            records = allMedicalHistory.filter(item => item.guest_id == guestId);
        }

        if (records.length === 0) {
            historyContainer.innerHTML =
                `<p class="text-muted">Không có bệnh án nào cho bệnh nhân này.</p>`;
            return;
        }

        let html = `
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>STT</th>
                            <th>Ngày tạo</th>
                            <th>Chẩn đoán</th>
                            <th>Kế hoạch điều trị</th>
                            <th>Ghi chú</th>
                            <th>Đơn thuốc</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        records.forEach((record, index) => {
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${record.created_at.split(' ')[0]}</td>
                    <td>${record.diagnosis}</td>
                    <td>${record.treatment_plan}</td>
                    <td>${record.notes}</td>
                    <td>
                        <button class="btn btn-info btn-sm view-prescription-btn"
                                data-history-id="${record.id}"
                                data-bs-toggle="modal"
                                data-bs-target="#prescriptionModal">
                            Xem đơn thuốc
                        </button>
                    </td>
                </tr>
            `;
        });

        html += `</tbody></table></div>`;
        historyContainer.innerHTML = html;
    });
});


// Bắt sự kiện hiển thị đơn thuốc trong modal con
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('view-prescription-btn')) {
        const historyId = e.target.getAttribute('data-history-id');
        const prescriptions = allPrescriptions.filter(p => p.medical_history_id == historyId);

        const container = document.getElementById('prescriptionContent');

        if (prescriptions.length === 0) {
            container.innerHTML = `<p class="text-muted">Không có đơn thuốc cho bệnh án này.</p>`;
            return;
        }

        let html = `
            <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Tên thuốc</th>
                        <th>Số lượng</th>
                        <th>Liều dùng</th>
                    </tr>
                </thead>
                <tbody>
        `;

        prescriptions.forEach(p => {
            html += `
                <tr>
                    <td>${p.medicine_name}</td>
                    <td>${p.quantity}</td>
                    <td>${p.dosage}</td>
                </tr>
            `;
        });

        html += `
                </tbody>
            </table>
            </div>
        `;

        container.innerHTML = html;
    }
});
</script>
<script>
function normalize(str) {
    return str.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function formatDate(inputDate) {
    const d = new Date(inputDate);
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    return `${day}/${month}/${year}`;
}

function filterAppointments() {
    const nameFilter = normalize(document.getElementById('filterPatientName').value.trim());
    const phoneFilter = normalize(document.getElementById('filterPhone').value.trim());
    const dateFilter = document.getElementById('filterDate').value;
    const sessionFilter = document.getElementById('filterSession').value;
    const statusFilter = document.getElementById('filterStatus').value;
    const addressFilter = document.getElementById("filterAddress").value.toLowerCase();

    document.querySelectorAll('tbody tr').forEach(row => {
        const nameCell = row.cells[0]?.innerText || '';
        const addressCol = row.cells[1]?.innerText.toLowerCase() || ''; // ✅ Lấy từ cột mới
        const name = normalize(nameCell.split('\n')[0]);
        const phone = normalize(nameCell.split('\n')[1] || '');
        const date = row.cells[2]?.innerText.trim() || '';
        const session = row.cells[3]?.innerText.trim() || '';
        const status = normalize(row.cells[4]?.innerText.trim() || '');

        const matchName = name.includes(nameFilter);
        const matchPhone = phone.includes(phoneFilter);
        const matchAddress = addressCol.includes(addressFilter); // ✅ So sánh địa chỉ
        const matchDate = !dateFilter || date === formatDate(dateFilter);
        const matchSession = !sessionFilter || session === sessionFilter;
        const matchStatus = !statusFilter || status.includes(statusFilter);

        row.style.display = (matchName && matchPhone && matchAddress && matchDate && matchSession &&
            matchStatus) ? '' : 'none';
    });
}


function resetFilters() {
    document.getElementById('filterPatientName').value = '';
    document.getElementById('filterPhone').value = '';
    document.getElementById('filterDate').value = '';
    document.getElementById('filterSession').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById("filterAddress").value = "";
    filterAppointments();
}

['filterPatientName', 'filterPhone', 'filterDate', 'filterSession', 'filterStatus'].forEach(id => {
    document.getElementById(id).addEventListener('input', filterAppointments);
    document.getElementById(id).addEventListener('change', filterAppointments);
});
</script>
<?php $this->stop() ?>