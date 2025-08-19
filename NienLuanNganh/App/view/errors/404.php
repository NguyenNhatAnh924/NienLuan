<?php $this->layout("layouts/default", ["title" => CONGNGHE]) ?>

<?php $this->start("page") ?>
<div class="container">
    <?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger">
        <!-- Kiểm tra nếu error_message là mảng, nếu có thì chuyển thành chuỗi -->
        <?= is_array($_SESSION['error_message']) ? implode('<br>', $_SESSION['error_message']) : $_SESSION['error_message']; ?>
    </div>
    <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="text-center py-3 px-1">
                <h1>Page Not Found</h1>
                <div class="text-muted my-3">
                    Sorry, an error has occured.
                    The page you requested could not be found.
                </div>
                <div class="my-1">
                    <a href="/" class="btn btn-primary btn-lg me-1">
                        <i class="fa fa-home"></i> Take Me Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->stop() ?>