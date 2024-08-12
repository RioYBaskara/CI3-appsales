<ul>
    <li>$user- <?= var_dump($user); ?></li>
    <li>$title- <?= var_dump($title); ?></li>
    <li>$user'image'- <?= $user['image'] ?></li>
    <li>url$user.'image'- <?= base_url('assets/img/profile/') . $user['image']; ?></li>
</ul>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <h1 class="h3 mb-4 text-gray-800">ouit kitn rcrm eqyv</h1>

    <!-- content -->
    <div class="col-xl-3 col-md-6 mb-4" onclick="location.href='<?= base_url(); ?>admin';" style="cursor: pointer;">
        <div class="card border-left-success shadow h-100 py-2 card-hover">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Jumlah User
                        </div>
                        <div class="h1 mb-0 font-weight-bold text-gray-800 text-center"><?= $jumlahuser ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-list-ol fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->