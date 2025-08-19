<?php $this->layout("layouts/default", ["title" => "Lịch tháng bác sĩ"]) ?>
<?php $this->start("page") ?>
<style>
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
                        <section id="consultation" class="consultation section light-background">
                            <div class="row justify-content-center animate__animated animate__fadeInUp">
                                <div class="col-lg-10">

                                    <div class="rounded shadow bg-white p-3">
                                        <div class="mb-4 bg-light p-3 rounded shadow-sm">
                                            <div class="row gx-2 gy-2 align-items-center">

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

                                                <div class="col-6 col-md-auto">
                                                    <button onclick="resetFilters()"
                                                        class="btn btn-outline-danger w-100">
                                                        <i class="fa-solid fa-rotate"></i>
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                        <h5 class="mb-3"><strong>Lịch làm việc</strong></h5>
                                        <div class="overflow-y-auto ">
                                            <div class="table-responsive" style="width: 100%;">
                                                <table class="table text-center mb-0 w-100" id="workScheduleTable">
                                                    <thead class="table-light">
                                                        <tr class="text-center">
                                                            <th class="p-2 border text-center">Ngày</th>
                                                            <th class="p-2 border text-center">Buổi</th>
                                                            <th class="p-2 border text-center">Ghi chú</th>
                                                            <th class="p-2 border text-center">Hành động</th>
                                                            <!-- THÊM -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($doctor_WorkSchedule as $item): ?>
                                                        <tr class="hover:bg-gray-50">
                                                            <!-- Ngày -->
                                                            <td class="p-2 border">
                                                                <?php if (!empty($item['specific_date'])): ?>
                                                                <?= date('d/m/Y', strtotime($item['specific_date'])) ?>
                                                                <?php elseif (!empty($item['weekday'])): ?>
                                                                <?= htmlspecialchars($item['weekday']) ?>
                                                                <?php else: ?>
                                                                <span class="text-gray-400 italic">Không rõ</span>
                                                                <?php endif; ?>
                                                            </td>

                                                            <!-- Buổi -->
                                                            <td class="p-2 border">
                                                                <?= htmlspecialchars($item['session_name'] ?? '---') ?>
                                                            </td>

                                                            <!-- Ghi chú -->
                                                            <td class="p-2 border">
                                                                <?php if (!empty($item['start_time']) && !empty($item['end_time'])): ?>
                                                                <?= htmlspecialchars($item['start_time'] . ' - ' . $item['end_time']) ?>
                                                                <?php else: ?>
                                                                <span class="text-gray-400 italic">Chưa rõ thời
                                                                    gian</span>
                                                                <?php endif; ?>
                                                            </td>

                                                            <!-- Hành động -->
                                                            <td class="p-2 border">
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-sm btn-outline-warning me-1 open-edit-modal"
                                                                    title="Sửa" data-bs-toggle="modal"
                                                                    data-bs-target="#editScheduleModal"
                                                                    data-id="<?= $item['id'] ?>"
                                                                    data-date="<?= $item['specific_date'] ?? '' ?>"
                                                                    data-session-id="<?= $item['session_id'] ?>"
                                                                    data-notes="<?= htmlspecialchars($item['notes'] ?? '') ?>"
                                                                    data-status="<?= $item['status'] ?>">
                                                                    <i class="bi bi-pencil-square"></i>
                                                                </a>

                                                                <form method="POST"
                                                                    action="/doctor/schedule/delete/<?= $item['id'] ?>"
                                                                    style="display:inline;"
                                                                    onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch này?');">
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-outline-danger"
                                                                        title="Hủy">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </form>
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
                            <!-- Section Title -->

                            <div class="container mt-4" data-aos="fade-up" data-aos-delay="100">
                                <div class="cta-wrapper">
                                    <div class="row align-items-center">
                                        <!-- Form đặt lịch -->
                                        <div class="" data-aos="fade-up" data-aos-delay="300">
                                            <div class="cta-form">

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

                                                <?php if (isset($_SESSION['success'])): ?>
                                                <div class="alert alert-success">
                                                    <?= htmlspecialchars($_SESSION['success']) ?>
                                                </div>
                                                <?php unset($_SESSION['success']); ?>
                                                <?php endif; ?>

                                                <h3>Đăng ký lịch làm việc</h3>
                                                <form action="/doctor/schedule/create" method="POST">
                                                    <!-- Hidden: ID bác sĩ -->
                                                    <input type="hidden" name="doctor_id"
                                                        value="<?= htmlspecialchars(AUTHGUARD()->user()->user_id) ?>">

                                                    <!-- Ngày làm việc -->
                                                    <div class="form-group">
                                                        <label for="specific_date">Ngày làm việc:</label>
                                                        <input type="date" name="specific_date" id="specific_date"
                                                            class="form-control" required>
                                                    </div>

                                                    <!-- Buổi làm việc -->
                                                    <div class="form-group mt-3">
                                                        <label for="session_id">Buổi làm việc:</label>
                                                        <select name="session_id" id="session_id" class="form-control"
                                                            required>
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

                                                    <!-- Ghi chú -->
                                                    <div class="form-group mt-3">
                                                        <label for="notes">Ghi chú (nếu có):</label>
                                                        <textarea name="notes" id="notes" class="form-control" rows="3"
                                                            placeholder="Ghi chú thêm..."></textarea>
                                                    </div>

                                                    <input type="hidden" name="status" value="pending">


                                                    <button type="submit" class="btn btn-primary mt-4">Đăng ký
                                                        lịch</button>
                                                </form>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </section><!-- /Consultation Section -->
                    </div>
                </div>

                <!-- Phần profile bên phải -->
                <div
                    class="w-full max-w-full px-3 mt-6 shrink-0 md:w-4/12 md:flex-0 md:mt-0 animate__animated animate__fadeInUp">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
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
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal sửa lịch làm việc -->
    <div class="modal fade" id="editScheduleModal" tabindex="-1" aria-labelledby="editScheduleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded shadow">
                <div class="modal-body p-0">
                    <!-- Giao diện giống hệt phần form đăng ký -->
                    <section id="consultation" class="consultation section light-background">
                        <div class="p-4">
                            <div class="cta-wrapper">
                                <div class="row align-items-center">
                                    <div class="">
                                        <div class="cta-form">
                                            <h3>Sửa lịch làm việc</h3>
                                            <form method="POST" id="editScheduleForm">
                                                <!-- Ngày -->
                                                <div class="form-group">
                                                    <label for="edit_date">Ngày làm việc:</label>
                                                    <input type="date" name="specific_date" id="edit_date"
                                                        class="form-control" required>
                                                </div>
                                                <!-- Buổi -->
                                                <div class="form-group mt-3">
                                                    <label for="edit_session">Buổi làm việc:</label>
                                                    <select name="session_id" id="edit_session" class="form-control"
                                                        required>
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

                                                <!-- Ghi chú -->
                                                <div class="form-group mt-3">
                                                    <label for="edit_notes">Ghi chú:</label>
                                                    <textarea name="notes" id="edit_notes" class="form-control"
                                                        rows="3"></textarea>
                                                </div>

                                                <!-- Trạng thái -->
                                                <div class="form-group mt-3">
                                                    <label for="edit_status">Trạng thái:</label>
                                                    <select name="status" id="edit_status" class="form-control"
                                                        required>
                                                        <option value="pending">Đang chờ duyệt</option>
                                                        <option value="approved">Đã duyệt</option>
                                                        <option value="cancelled">Đã huỷ</option>
                                                    </select>
                                                </div>

                                                <div class="mt-4 d-flex justify-content-end">
                                                    <button type="button" class="btn btn-secondary me-2"
                                                        data-bs-dismiss="modal">Hủy</button>
                                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section><!-- /Consultation Section -->

                </div>
            </div>
        </div>
    </div>




</main>

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
document.querySelectorAll('.open-edit-modal').forEach(button => {
    button.addEventListener('click', function() {
        const modal = document.getElementById('editScheduleModal');

        // Lấy dữ liệu từ data-* của nút
        const scheduleId = this.dataset.id;
        const date = this.dataset.date || '';
        const sessionId = this.dataset['sessionId'];
        const notes = this.dataset.notes || '';
        const status = this.dataset.status || '';

        // Điền vào form
        modal.querySelector('#edit_date').value = date;
        modal.querySelector('#edit_session').value = sessionId;
        modal.querySelector('#edit_notes').value = notes;
        modal.querySelector('#edit_status').value = status;

        // Gán action động cho form
        const form = modal.querySelector('#editScheduleForm');
        form.action = `/doctor/schedule/update/${scheduleId}`;
    });
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

function filterSchedule() {
    const filterDate = document.getElementById('filterDate').value;
    const filterSession = document.getElementById('filterSession').value;

    const rows = document.querySelectorAll('#workScheduleTable tbody tr');

    rows.forEach(row => {
        const dateCell = row.cells[0]?.innerText.trim();
        const sessionCell = row.cells[1]?.innerText.trim();

        const matchDate = !filterDate || dateCell === formatDate(filterDate);
        const matchSession = !filterSession || sessionCell === filterSession;

        row.style.display = (matchDate && matchSession) ? '' : 'none';
    });
}

function resetFilters() {
    document.getElementById('filterDate').value = '';
    document.getElementById('filterSession').value = '';
    filterSchedule();
}

document.getElementById('filterDate').addEventListener('change', filterSchedule);
document.getElementById('filterSession').addEventListener('change', filterSchedule);
</script>

<?php $this->stop() ?>