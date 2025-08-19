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
            <?php if (!empty($_SESSION['errors'])): ?>
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

            <!-- PHẦN BẢNG DANH SÁCH LỊCH HẸN -->
            <div class="row justify-content-center animate__animated animate__fadeInUp">
                <div class="col-lg-10">
                    <div class="bg-white rounded shadow p-3">
                        <h5 class="mb-3"><strong>Lịch sử khám bệnh</strong></h5>
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
                                            <th>Lịch sử bệnh án</th>
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
                                            <td>
                                                <?= htmlspecialchars($appointment->doctor_name ?? 'Chưa phân công') ?>
                                            </td>

                                            <!-- LỊCH SỬ BỆNH ÁN -->
                                            <td class="text-center align-middle">
                                                <?php if (($appointment->status ?? '') === 'completed'): ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-secondary rounded-circle view-history-btn"
                                                    data-bs-toggle="modal" data-bs-target="#historyRecordModal"
                                                    data-patient-id="<?= $appointment->patient_id ?>"
                                                    data-guest-id="<?= $appointment->guest_id ?>"
                                                    data-patient-type="<?= $appointment->patient_type ?>"
                                                    data-appointment-id="<?= $appointment->appointment_id ?>">
                                                    <i class="bi bi-clock-history fs-5"></i>
                                                </button>
                                                <?php else: ?>
                                                <i class="bi bi-clock-history fs-5 text-muted"
                                                    title="Chưa có lịch sử"></i>
                                                <?php endif; ?>
                                            </td>

                                            <!-- NÚT SỬA -->
                                            <td class="text-center align-middle">
                                                <?php if ($appointment->status !== 'completed'): ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-warning rounded-circle edit-appointment-btn"
                                                    data-bs-toggle="modal" data-bs-target="#editAppointmentModal"
                                                    data-appointment-id="<?= $appointment->appointment_id ?>"
                                                    data-patient-type="<?= $appointment->patient_type ?>"
                                                    data-doctor-id="<?= $appointment->doctor_id ?>"
                                                    data-phone="<?= $appointment->phone ?>"
                                                    data-appointment-date="<?= $appointment->appointment_date ?>"
                                                    data-session="<?= $appointment->session ?>"
                                                    data-symptoms="<?= $appointment->symptoms ?>">
                                                    <i class="bi bi-pencil-square fs-5"></i>
                                                </button>
                                                <?php else: ?>
                                                <i class="bi bi-pencil-square fs-5 text-muted"
                                                    title="Không thể sửa"></i>
                                                <?php endif; ?>
                                            </td>

                                            <!-- NÚT HỦY / PHỤC HỒI -->
                                            <td class="text-center align-middle">
                                                <?php if ($appointment->status === 'cancelled'): ?>
                                                <form method="post"
                                                    action="/restore_appointment/<?= $this->e($appointment->appointment_id) ?>">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-info rounded-circle">
                                                        <i class="bi bi-calendar2-event fs-5"></i>
                                                    </button>
                                                </form>
                                                <?php elseif ($appointment->status === 'pending'): ?>
                                                <form method="post"
                                                    action="/canceled_appointment/<?= $this->e($appointment->appointment_id) ?>"
                                                    onsubmit="return confirm('Bạn có chắc muốn hủy lịch hẹn này?');">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger rounded-circle">
                                                        <i class="bi bi-calendar-x fs-5"></i>
                                                    </button>
                                                </form>
                                                <?php else: ?>
                                                <i class="bi bi-calendar-check fs-5 text-success"></i>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-muted">Không có lịch hẹn nào.</td>
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
                        <select name="doctor_id" id="edit_doctor_id" class="form-select">
                            <option value="0">-- Chọn bác sĩ --</option>
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
<script>
const allMedicalHistory = <?= json_encode($medicalHistory, JSON_UNESCAPED_UNICODE) ?>;
const allPrescriptions = <?= json_encode($dataMedicalHistoryMedical ?? [], JSON_UNESCAPED_UNICODE) ?>;

document.querySelectorAll('.view-history-btn').forEach(button => {
    button.addEventListener('click', function() {
        const historyContainer = document.getElementById('historyContent');

        const appointmentId = this.getAttribute('data-appointment-id');
        console.log("Tất cả lịch sử:", allMedicalHistory);
        console.log("ID lịch hẹn đang bấm:", appointmentId);
        const records = allMedicalHistory.filter(item => item.appointment_id == appointmentId);

        if (!records || records.length === 0) {
            historyContainer.innerHTML =
                `<p class="text-muted">Không có bệnh án nào cho lịch hẹn này.</p>`;
            return;
        }

        let html = `
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>STT</th>
                            <th>Ngày khám</th>
                            <th>Chẩn đoán</th>
                            <th>Kế hoạch điều trị</th>
                            <th>Ghi chú</th>
                            <th>Bác sĩ</th>
                            <th>Đơn thuốc</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        records.forEach((record, index) => {
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${record.appointment_date || ''}</td>
                    <td>${record.diagnosis || ''}</td>
                    <td>${record.treatment_plan || ''}</td>
                    <td>${record.notes || ''}</td>
                    <td>${record.doctor_name || ''}</td>
                    <td>
                        <button class="btn btn-sm btn-info view-prescription-btn"
                                data-history-id="${record.history_id}"
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

// Phần xem đơn thuốc giữ nguyên như cũ
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

        html += `</tbody></table></div>`;
        container.innerHTML = html;
    }
});
</script>

<?php $this->stop() ?>