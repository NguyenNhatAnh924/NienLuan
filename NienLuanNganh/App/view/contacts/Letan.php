<?php $this->layout("layouts/default", ["title" => CONGNGHE]) ?>

<?php $this->start("page") ?>

<main>
    <div
        class="absolute w-full top-0 bg-[url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg')] bg-cover bg-center min-h-75 dark-background">
        <!-- Lớp phủ dùng màu --background-color -->
        <span class="absolute top-0 left-0 w-full h-full"
            style="background-color: var(--background-color); opacity: 0.5;"></span>
    </div>

    <section class="contact section bg-light">
        <div class="container">
            <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            <!-- FORM THÊM LỊCH HẸN -->
            <div class="row justify-content-center mb-5 animate__animated animate__fadeInUp">
                <div class="col-lg-10">
                    <div class="bg-white rounded shadow p-4">
                        <h3 class="text-center text-dark mb-4"><strong>Thêm lịch hẹn</strong></h3>
                        <div class="contact-form-wrapper">
                            <form action="/appointments/create" method="post" class="php-form" id="appointmentForm">

                                <input type="hidden" name="patient_type" value="guest">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="patient_name" class="form-label">Họ và tên</label>
                                        <input type="text" name="patient_name" class="form-control" id="patient_name"
                                            placeholder="Tên bệnh nhân" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                        <input type="text" class="form-control" name="phone" id="phone"
                                            placeholder="0123456789" required>
                                    </div>

                                    <div class="col-9 mb-3">
                                        <label for="address" class="form-label">Địa chỉ</label>
                                        <input type="text" class="form-control" name="address" id="address"
                                            placeholder="123 Đường ABC, Quận XYZ" required>
                                    </div>

                                    <div class="col-3 mb-3">
                                        <label class="form-label d-block">Giới tính</label>
                                        <div class="form-check form-check-inline py-1">
                                            <input class="form-check-input" type="radio" name="gender" id="male"
                                                value="Nam" checked>
                                            <label class="form-check-label" for="male">Nam</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="female"
                                                value="Nữ">
                                            <label class="form-check-label" for="female">Nữ</label>
                                        </div>
                                    </div>

                                    <!-- Mã số thẻ bảo hiểm -->
                                    <div class="col-md-9 mb-3">
                                        <label for="insurance_number" class="form-label">Mã số thẻ bảo hiểm</label>
                                        <input type="text" class="form-control" name="insurance_number"
                                            id="insurance_number" placeholder="Nhập mã số thẻ bảo hiểm nếu có">
                                    </div>

                                    <!-- Tái khám checkbox -->
                                    <div class="col-md-3 mb-3 d-flex align-items-center">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="follow_up"
                                                name="follow_up" value="1">
                                            <label class="form-check-label ms-2 mb-0" for="follow_up">
                                                Tái khám (đã từng khám)
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="appointment_date" class="form-label">Ngày khám</label>
                                        <input type="date" class="form-control" name="appointment_date"
                                            id="appointment_date" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="session" class="form-label">Buổi khám</label>
                                        <select class="form-select" id="session" name="session" required>
                                            <option value="">-- Chọn buổi khám --</option>
                                            <option value="Sáng">Sáng</option>
                                            <option value="Chiều">Chiều</option>
                                        </select>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="symptoms" class="form-label">Triệu chứng</label>
                                        <textarea class="form-control" name="symptoms" id="symptoms" rows="5"
                                            placeholder="Mô tả triệu chứng..." required></textarea>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="">Đặt lịch hẹn</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PHẦN BẢNG DANH SÁCH LỊCH HẸN -->
            <div class="row justify-content-center animate__animated animate__fadeInUp">
                <div class="col-lg-10">
                    <div class="bg-white rounded shadow p-3">
                        <h5 class="mb-3"><strong>Danh sách lịch hẹn khám bệnh</strong></h5>
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
                                            <button onclick="resetFilters()" class="btn btn-outline-danger w-100">
                                                <i class="fa-solid fa-rotate"></i>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                                <table class="table table-bordered text-center mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Bệnh nhân</th>
                                            <th>Ngày khám</th>
                                            <th>Buổi khám</th>
                                            <th>Trạng thái</th>
                                            <th>Tên bác sĩ</th>
                                            <th>Sửa</th>
                                            <th>Hủy</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($appointments)): ?>
                                        <?php foreach ($appointments as $appointment): ?>
                                        <tr>
                                            <td>
                                                <?= htmlspecialchars($appointment->patient_name) ?><br>
                                                <small><?= htmlspecialchars($appointment->phone) ?></small>
                                            </td>
                                            <td>
                                                <?= date('d/m/Y', strtotime($appointment->appointment_date)) ?>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($appointment->session) ?>
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
                                            <td><?= htmlspecialchars($appointment->doctor_name ?? 'Chưa phân công') ?>
                                            </td>
                                            <td>
                                                <?php if ($appointment->status !== 'completed'): ?>
                                                <!-- Chỉ hiển thị nút sửa nếu chưa hoàn thành -->
                                                <button type="button" class="btn btn-link p-0 edit-appointment-btn"
                                                    data-bs-toggle="modal" data-bs-target="#editAppointmentModal"
                                                    data-appointment-id="<?= $appointment->appointment_id ?>"
                                                    data-patient-type="<?= $appointment->patient_type ?>"
                                                    data-doctor-id="<?= $appointment->doctor_id ?>"
                                                    data-phone="<?= $appointment->phone ?>"
                                                    data-appointment-date="<?= $appointment->appointment_date ?>"
                                                    data-session="<?= $appointment->session ?>"
                                                    data-symptoms="<?= $appointment->symptoms ?>">
                                                    <i class="bi bi-pencil-square fs-4 text-warning"></i>
                                                </button>
                                                <?php else: ?>
                                                <!-- Có thể để biểu tượng mờ nếu muốn -->
                                                <i class="bi bi-pencil-square fs-4 text-muted"
                                                    title="Không thể sửa"></i>
                                                <?php endif; ?>
                                            </td>

                                            <td>
                                                <?php if ($appointment->status === 'cancelled'): ?>
                                                <!-- Nút PHỤC HỒI nếu lịch hẹn đã bị hủy -->
                                                <form method="post"
                                                    action="/restore_appointment/<?= $this->e($appointment->appointment_id) ?>">
                                                    <button type="submit"
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 bg-yellow-600 rounded px-2 py-1 hover:bg-yellow-700">
                                                        <i class="bi bi-calendar2-event fs-4 text-info"></i>
                                                        <!-- Biểu tượng phục hồi -->
                                                    </button>
                                                </form>
                                                <?php elseif ($appointment->status === 'pending'): ?>
                                                <!-- Nút HỦY nếu lịch hẹn chưa bị hủy -->
                                                <form method="post"
                                                    action="/canceled_appointment/<?= $this->e($appointment->appointment_id) ?>"
                                                    onsubmit="return confirm('Bạn có chắc muốn hủy lịch hẹn này?');">
                                                    <button type="submit"
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 bg-red-600 rounded px-2 py-1 hover:bg-red-700">
                                                        <i class="bi bi-calendar-x fs-4 text-danger"></i>
                                                        <!-- Biểu tượng hủy -->
                                                    </button>
                                                </form>
                                                <?php else: ?>
                                                <i class="bi bi-calendar-check fs-4 text-success"></i>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-muted">Không có lịch hẹn nào.</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal Sửa lịch hẹn -->
    <div class="modal fade" id="editAppointmentModal" tabindex="-1" aria-labelledby="editAppointmentLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="/appointments/update" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAppointmentLabel">Sửa lịch hẹn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="appointment_id" id="edit_appointment_id">
                    <input type="hidden" name="patient_type" id="edit_patient_type">
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_appointment_date" class="form-label">Ngày khám</label>
                        <input type="date" name="appointment_date" id="edit_appointment_date" class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_session" class="form-label">Buổi khám</label>
                        <select name="session" id="edit_session" class="form-select" required>
                            <option value="Sáng">Sáng</option>
                            <option value="Chiều">Chiều</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_doctor_id" class="form-label">Bác sĩ</label>
                        <select name="doctor_id" id="edit_doctor_id" class="form-select" required>
                            <option value="">-- Chọn bác sĩ --</option>
                            <?php foreach ($doctors as $doctor): ?>
                            <option value="<?= $doctor->user_id ?>"><?= $doctor->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_symptoms" class="form-label">Triệu chứng</label>
                        <textarea name="symptoms" id="edit_symptoms" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Cập nhật</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </form>
        </div>
    </div>

</main>
<script>
document.querySelectorAll('.edit-appointment-btn').forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('edit_appointment_id').value = this.dataset.appointmentId;
        document.getElementById('edit_patient_type').value = this.dataset.patientType;
        document.getElementById('edit_doctor_id').value = this.dataset.doctorId;
        document.getElementById('edit_phone').value = this.dataset.phone;
        document.getElementById('edit_appointment_date').value = this.dataset.appointmentDate;
        document.getElementById('edit_session').value = this.dataset.session;
        document.getElementById('edit_symptoms').value = this.dataset.symptoms;
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

function filterAppointments() {
    const nameFilter = normalize(document.getElementById('filterPatientName').value.trim());
    const phoneFilter = normalize(document.getElementById('filterPhone').value.trim());
    const dateFilter = document.getElementById('filterDate').value;
    const sessionFilter = document.getElementById('filterSession').value;
    const statusFilter = document.getElementById('filterStatus').value;

    document.querySelectorAll('tbody tr').forEach(row => {
        const nameCell = row.cells[0]?.innerText || '';
        const name = normalize(nameCell.split('\n')[0]);
        const phone = normalize(nameCell.split('\n')[1] || '');
        const date = row.cells[1]?.innerText.trim() || '';
        const session = row.cells[2]?.innerText.trim() || '';
        const status = normalize(row.cells[3]?.innerText.trim() || '');

        const matchName = name.includes(nameFilter);
        const matchPhone = phone.includes(phoneFilter);
        const matchDate = !dateFilter || date === formatDate(dateFilter);
        const matchSession = !sessionFilter || session === sessionFilter;
        const matchStatus = !statusFilter || status.includes(statusFilter);

        row.style.display = (matchName && matchPhone && matchDate && matchSession && matchStatus) ? '' : 'none';
    });
}

function resetFilters() {
    document.getElementById('filterPatientName').value = '';
    document.getElementById('filterPhone').value = '';
    document.getElementById('filterDate').value = '';
    document.getElementById('filterSession').value = '';
    document.getElementById('filterStatus').value = '';
    filterAppointments();
}

['filterPatientName', 'filterPhone', 'filterDate', 'filterSession', 'filterStatus'].forEach(id => {
    document.getElementById(id).addEventListener('input', filterAppointments);
    document.getElementById(id).addEventListener('change', filterAppointments);
});
</script>


<?php $this->stop() ?>