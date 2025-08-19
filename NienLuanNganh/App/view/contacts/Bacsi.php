<?php $this->layout("layouts/default", ["title" => CONGNGHE]) ?>

<?php $this->start("page") ?>
<main>
    <div
        class="absolute w-full top-0 bg-[url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg')] bg-cover bg-center min-h-75 dark-background">
        <span class="absolute top-0 left-0 w-full h-full"
            style="background-color: var(--background-color); opacity: 0.5;"></span>
    </div>

    <section class="vh-100 d-flex align-items-center justify-content-center bg-light">
        <div class="container">
            <div class="flex flex-wrap -mx-3">
                <div class="flex-none w-full max-w-full px-3 animate__animated animate__fadeInUp">
                    <?php if (isset($_SESSION['errors'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['errors']; ?>
                    </div>
                    <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success_message']; ?>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>
                    <div
                        class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                            <h6 class="dark:text-white"><strong>Danh sách lịch hẹn khám bệnh</strong></h6>
                        </div>
                        <div class="flex-auto px-0 pt-0 pb-2">
                            <div class="p-0 overflow-x-auto" style="max-height: 420px; overflow-y: auto;">

                                <table
                                    class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                    <div class="mb-4 bg-light p-3 rounded shadow-sm">
                                        <div class="row gx-2 gy-2 align-items-center">

                                            <div class="col-6 col-md">
                                                <input type="text" id="filterPatientName" class="form-control"
                                                    placeholder="Tên bệnh nhân">
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

                                            <div class="col-6 col-md-auto">
                                                <button onclick="resetFilters()" class="btn btn-outline-danger w-100">
                                                    <i class="fa-solid fa-rotate me-1"></i> Đặt lại
                                                </button>
                                            </div>

                                        </div>
                                    </div>

                                    <thead class="align-bottom">
                                        <tr>
                                            <th
                                                class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                STT
                                            </th>
                                            <th
                                                class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Bệnh nhân</th>
                                            <th
                                                class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Ngày khám</th>
                                            <th
                                                class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Buổi khám
                                            </th>
                                            <th
                                                class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Trạng thái</th>
                                            <th
                                                class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Mô tả
                                            </th>
                                            <th
                                                class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Thêm bệnh án</th>
                                            <th
                                                class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Lịch sử bệnh án</th>
                                            <th
                                                class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Hoàn tất
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($appointments as $index => $appointment): ?>
                                        <tr>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <?= $this->e($index + 1) ?>
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <div class="flex px-2 py-1">
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-sm leading-normal dark:text-white">
                                                            <?= $this->e($appointment->patient_name) ?>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </td>

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
                                                <span
                                                    class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                                                    <?= $this->e($appointment->status) ?>
                                                </span>
                                            </td>
                                            <td
                                                class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <?= $this->e($appointment->symptoms) ?>
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <button type="button"
                                                    class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 hover:text-blue-500 underline add-record-btn"
                                                    data-bs-toggle="modal" data-bs-target="#addMedicalHistoryModal"
                                                    data-appointment-id="<?= $this->e($appointment->appointment_id) ?>"
                                                    data-patient-id="<?= $this->e($appointment->patient_id) ?>"
                                                    data-patient-name="<?= $this->e($appointment->patient_name) ?>"
                                                    data-doctor-id="<?= $this->e($appointment->doctor_id) ?>"
                                                    data-guest-id="<?= $this->e($appointment->guest_id) ?>"
                                                    data-patient-type="<?= $this->e($appointment->patient_type) ?>"
                                                    data-doctor-name="<?= $this->e($appointment->doctor_name) ?>">
                                                    <i class="bi bi-plus-circle-fill fs-3"></i>
                                                </button>
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
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <form method="post"
                                                    action="/completed_appointment/<?= $this->e($appointment->appointment_id) ?>">
                                                    <button type="submit"
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 bg-green-600 rounded px-2 py-1 hover:bg-green-700 text-success">
                                                        <i class="bi bi-calendar2-check fs-3"></i>
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
        </div>

        <!-- Modal Thêm bệnh án dùng chung -->
        <div class="modal fade" id="addMedicalHistoryModal" tabindex="-1" aria-labelledby="addMedicalHistoryLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <form method="post" action="/medical_history/add" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMedicalHistoryLabel">Thêm bệnh án</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <!-- Thông tin cơ bản -->
                        <input type="hidden" name="appointment_id" id="appointment_id">
                        <input type="hidden" name="patient_id" id="patient_id">
                        <input type="hidden" name="guest_id" id="guest_id">
                        <input type="hidden" name="patient_type" id="patient_type">
                        <input type="hidden" name="doctor_id" id="doctor_id"
                            value="<?= AUTHGUARD()->user() ? htmlspecialchars(AUTHGUARD()->user()->user_id) : '' ?>">

                        <div class="mb-3">
                            <label for="diagnosis" class="form-label">Chẩn đoán <span
                                    class="text-danger">*</span></label>
                            <textarea id="diagnosis" name="diagnosis" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="treatment_plan" class="form-label">Kế hoạch điều trị</label>
                            <textarea id="treatment_plan" name="treatment_plan" class="form-control"
                                rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú</label>
                            <textarea id="notes" name="notes" class="form-control" rows="3"></textarea>
                        </div>

                        <!-- Đơn thuốc -->
                        <div class="mb-3">
                            <label class="form-label">Đơn thuốc</label>
                            <table class="table table-bordered" id="prescriptionTable">
                                <thead>
                                    <tr>
                                        <th>Tên thuốc</th>
                                        <th>Số lượng</th>
                                        <th>Liều dùng</th>
                                        <th>Cách dùng</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="medicines[0][medicine_id]" class="form-select" required>
                                                <option value="">-- Chọn thuốc --</option>
                                                <?php foreach ($medicals as $medicine): ?>
                                                <option value="<?= $medicine['id'] ?>">
                                                    <?= $medicine['id'] ?> - <?= $medicine['name'] ?> -
                                                    <?= $medicine['strength'] ?><?= $medicine['unit'] ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td><input type="number" name="medicines[0][quantity]" class="form-control"
                                                min="1" required></td>
                                        <td><input type="text" name="medicines[0][dosage]" class="form-control"></td>
                                        <td><input type="text" name="medicines[0][usage]" class="form-control"></td>
                                        <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-primary btn-sm" id="addMedicineRow">Thêm thuốc</button>
                        </div>

                        <!-- Dịch vụ -->
                        <div class="mb-3">
                            <label class="form-label">Dịch vụ</label>
                            <table class="table table-bordered" id="serviceTable">
                                <thead>
                                    <tr>
                                        <th>Dịch vụ</th>
                                        <th>Số lượng</th>
                                        <th>Ghi chú</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="services[0][service_id]" class="form-select" required>
                                                <option value="">-- Chọn dịch vụ --</option>
                                                <?php foreach ($services as $service): ?>
                                                <option value="<?= $service['id'] ?>">
                                                    <?= $service['name'] ?> (<?= number_format($service['price']) ?>đ)
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td><input type="number" name="services[0][quantity]" class="form-control"
                                                min="1" required></td>
                                        <td><input type="text" name="services[0][note]" class="form-control"></td>
                                        <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-primary btn-sm" id="addServiceRow">Thêm dịch
                                vụ</button>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Lưu bệnh án</button>
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

    </section>
</main>

<script>
// Khi mở modal Thêm bệnh án, lấy dữ liệu từ nút bấm và điền vào form
document.querySelectorAll('.add-record-btn').forEach(button => {
    button.addEventListener('click', function() {
        const appointmentId = this.getAttribute('data-appointment-id');
        const patientId = this.getAttribute('data-patient-id');
        const guestId = this.getAttribute('data-guest-id'); // sửa tên đúng: guest-id
        const doctorId = this.getAttribute('data-doctor-id');
        const patientType = this.getAttribute('data-patient-type'); // thêm dòng này
        const patientName = this.getAttribute('data-patient-name');
        const doctorName = this.getAttribute('data-doctor-name');

        document.getElementById('appointment_id').value = appointmentId;
        document.getElementById('patient_id').value = patientId;
        document.getElementById('guest_id').value = guestId;
        document.getElementById('patient_type').value = patientType;
        document.getElementById('doctor_id').value = doctorId;

        // Optional: nếu có input hiển thị tên
        if (document.getElementById('patient_name'))
            document.getElementById('patient_name').value = patientName;
        if (document.getElementById('doctor_name'))
            document.getElementById('doctor_name').value = doctorName;

        // Reset các trường nhập liệu
        document.getElementById('diagnosis').value = '';
        document.getElementById('treatment_plan').value = '';
        document.getElementById('notes').value = '';
    });
});
</script>
<script>
// Dữ liệu PHP truyền qua biến JS
const medicineOptions = `<?php foreach ($medicals as $medicine): ?>
    <option value="<?= $medicine['id'] ?>">
      <?= $medicine['id'] ?> - <?= $medicine['name'] ?> - <?= $medicine['strength'] ?><?= $medicine['unit'] ?>
    </option>
  <?php endforeach; ?>`;

let medicineIndex = document.querySelectorAll('#prescriptionTable tbody tr').length;
let serviceIndex = document.querySelectorAll('#prescriptionTable tbody tr').length;

document.getElementById('addMedicineRow').addEventListener('click', function() {
    const table = document.querySelector('#prescriptionTable tbody');
    const newRow = `
      <tr>
        <td>
          <select name="medicines[${medicineIndex}][medicine_id]" class="form-select" required>
            <option value="">-- Chọn thuốc --</option>
            ${medicineOptions}
          </select>
        </td>
        <td><input type="number" name="medicines[${medicineIndex}][quantity]" class="form-control" min="1" required></td>
        <td><input type="text" name="medicines[${medicineIndex}][dosage]" class="form-control"></td>
        <td><input type="text" name="medicines[${medicineIndex}][usage]" class="form-control"></td>
        <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
      </tr>`;
    table.insertAdjacentHTML('beforeend', newRow);
    medicineIndex++;
});

document.getElementById('addServiceRow').addEventListener('click', function() {
    const table = document.querySelector('#serviceTable tbody');
    const newRow = `
      <tr>
        <td>
          <select name="services[${serviceIndex}][service_id]" class="form-select" required>
            <option value="">-- Chọn dịch vụ --</option>
            <?php foreach ($services as $service): ?>
              <option value="<?= $service['id'] ?>">
                <?= $service['name'] ?> (<?= number_format($service['price']) ?>đ)
              </option>
            <?php endforeach; ?>
          </select>
        </td>
        <td><input type="number" name="services[${serviceIndex}][quantity]" class="form-control" min="1" required></td>
        <td><input type="text" name="services[${serviceIndex}][note]" class="form-control"></td>
        <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
      </tr>`;
    table.insertAdjacentHTML('beforeend', newRow);
    serviceIndex++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('removeRow')) {
        e.target.closest('tr').remove();
    }
});
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
    return `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`;
}

function filterTable() {
    const name = normalize(document.getElementById('filterPatientName').value.trim());
    const date = document.getElementById('filterDate').value;
    const session = document.getElementById('filterSession').value;

    document.querySelectorAll("tbody tr").forEach(row => {
        const patientName = normalize(row.cells[1]?.innerText || '');
        const appointmentDate = row.cells[2]?.innerText.trim();
        const sessionText = row.cells[3]?.innerText.trim();

        const matchName = patientName.includes(name);
        const matchDate = !date || appointmentDate === formatDate(date);
        const matchSession = !session || sessionText === session;

        row.style.display = (matchName && matchDate && matchSession) ? '' : 'none';
    });
}

function resetFilters() {
    document.getElementById('filterPatientName').value = '';
    document.getElementById('filterDate').value = '';
    document.getElementById('filterSession').value = '';
    filterTable(); // Hiện lại tất cả dòng
}

['filterPatientName', 'filterDate', 'filterSession'].forEach(id => {
    document.getElementById(id).addEventListener('input', filterTable);
    document.getElementById(id).addEventListener('change', filterTable);
});
</script>

<?php $this->stop() ?>