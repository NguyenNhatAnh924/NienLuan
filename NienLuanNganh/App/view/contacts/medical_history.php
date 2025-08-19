<?php $this->layout("layouts/default", ["title" => "Medical History List"]) ?>

<?php $this->start("page") ?>
<div class="container">
    <h2 class="text-center animate__animated animate__bounce">Medical Histories</h2>
    <div class="row">
        <div class="col-md-6 offset-md-3 text-center">
            <p class="animate__animated animate__fadeInLeft">List of all medical histories in the system.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <a href="/medical-history/create/" class="btn btn-primary mb-3">
                <i class="fa fa-plus"></i> New Medical History
            </a>

            <!-- Table Starts Here -->
            <table id="medicalHistory" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">STT</th>
                        <th scope="col">Tên bệnh nhân</th>
                        <th scope="col">Tên bác sĩ</th>
                        <th scope="col">Chẩn đoán</th>
                        <th scope="col">Phác đồ điều trị</th>
                        <th scope="col">Ngày tái khám</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
        $stt = 1;
        foreach ($medicalHistories as $history) :
        ?>
                    <tr>
                        <td><?= $stt++ ?></td>
                        <td><?= $this->e($history->patient_name) ?></td>
                        <td><?= $this->e($history->doctor_name) ?></td>
                        <td><?= $this->e($history->diagnosis) ?></td>
                        <td><?= $this->e($history->treatment_plan) ?></td>
                        <td><?= $this->e(date("d-m-Y H:i", strtotime($history->follow_up))) ?></td>
                        <td class="d-flex justify-content-center">
                            <!-- Xem chi tiết -->
                            <a href="/medical-history/view/<?= $this->e($history->history_id) ?>"
                                class="btn btn-xs btn-info">
                                <i class="fa fa-eye"></i> Xem
                            </a>
                            <!-- Chỉnh sửa -->
                            <a href="/medical-history/edit/<?= $this->e($history->history_id) ?>"
                                class="btn btn-xs btn-warning ms-1">
                                <i class="fa fa-edit"></i> Sửa
                            </a>
                            <!-- Xóa -->
                            <form class="ms-1 delete-form"
                                action="/medical-history/delete/<?= $this->e($history->history_id) ?>" method="POST">
                                <button type="button" class="btn btn-xs btn-danger delete-btn">
                                    <i class="fa fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Table Ends Here -->
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Confirm delete action
    $(".delete-btn").on("click", function() {
        let form = $(this).closest("form");
        if (confirm("Are you sure you want to delete this medical history?")) {
            form.submit();
        }
    });
});
</script>

<?php $this->stop() ?>