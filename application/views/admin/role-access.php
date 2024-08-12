<ul>
    <li>$user- <?= var_dump($user); ?></li>
    <li>$role- <?= var_dump($role); ?></li>
    <li>$title- <?= var_dump($title); ?></li>
    <li>$user'image'- <?= $user['image'] ?></li>
    <li>url$user.'image'- <?= base_url('assets/img/profile/') . $user['image']; ?></li>
    <li>error fvalid- <?= validation_errors() ?></li>
    <li>flash data- <?= $this->session->flashdata('akses'); ?></li>
</ul>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <!-- content -->
    <div class="row">
        <div class="col-lg">

            <?= $this->session->flashdata('message'); ?>

            <h5>Role : <?= $role['role']; ?></h5>

            <table class="table table-hover">

                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Menu</th>
                        <th scope="col">Access</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($menu as $m): ?>
                        <tr>
                            <th scope="row"><?= $i; ?></th>
                            <td><?= $m['menu']; ?></td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" <?= check_acces($role['id'], $m['id']); ?> data-role="<?= $role['id']; ?>" data-menu="<?= $m['id']; ?>">
                                </div>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->