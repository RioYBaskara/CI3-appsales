<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
        <p>Default Password Setelah Membuat User Baru: "123".</p>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>

    <!-- content -->
    <div class="row">
        <div class="col-12">
            <h5>Data Personel</h5>
            <?php if (validation_errors()): ?>
                <div class="alert alert-danger" role="alert">
                    <?= validation_errors() ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="flash-data" data-flashdata="<?= $this->session->flashdata('flashswal') ?>"></div>
        </div>
        <!-- Pending Requests Card Example -->
        <div class="col-xl col-md-6 mb-4">
            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newUsersModal">Tambah User
                Baru</a>
            <a href="<?= base_url('admin/sales'); ?>" class="btn btn-info mb-3">Ke Page Sales
            </a>
            <table class="table table-hover text-center">

                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Nama User</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role - ID Role</th>
                        <th scope="col">Sales - ID Sales</th>
                        <th scope="col">User Aktif</th>
                        <th scope="col">Date Created</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <th scope="row"><?= $i; ?></th>
                            <td><?= $u['id']; ?></td>
                            <td><?= $u['name']; ?></td>
                            <td><?= $u['email']; ?></td>
                            <td><?= $u['nama_role']; ?> - <?= $u['role_id']; ?></td>
                            <td><?= $u['nama_sales']; ?> - <?= $u['id_sales']; ?></td>
                            <td>
                                <input class="form-check-input m-auto" type="checkbox" value="1"
                                    id="is_active<?= $u['id'] ?>" <?= $u['is_active'] == 1 ? 'checked' : '' ?> disabled>
                            </td>
                            <td><?= date('d F Y', $u['date_created']); ?></td>
                            <td>
                                <a data-toggle="modal" data-target="#modal-edit<?= $u['id'] ?>" class="btn btn-success  "><i
                                        class="fa fa-pencil-alt"></i></a>
                                <a href="<?= base_url(); ?>admin/usershapus/<?= $u['id']; ?>"
                                    class="btn btn-danger tombol-hapus"><i class="fa fa-trash"></i></a>
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


<!-- Modal -->
<div class="modal fade" id="newUsersModal" tabindex="-1" role="dialog" aria-labelledby="newUsersModal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newUsersModalLabel">Tambah Users Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/users'); ?>" method="post">
                <div class="modal-body">
                    <?php if (validation_errors()): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= validation_errors(); ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <input autocomplete="off" type="text" class="form-control" id="name" name="name"
                            placeholder="Nama User">
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" type="email" class="form-control" id="email" name="email"
                            placeholder="Email">
                    </div>
                    <div class="form-group d-none">
                        <input autocomplete="off" disabled value="123" type="text" class="form-control" id="password"
                            name="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <select name="role_id" id="role_id" class="form-control">
                            <option value="">--Pilih Role - ID Role--</option>
                            <?php foreach ($role as $rl): ?>
                                <option value="<?= $rl['id']; ?>"><?= $rl['role']; ?> - <?= $rl['id'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="id_sales" id="id_sales" class="form-control">
                            <option value="">--Pilih Sales - ID Sales--</option>
                            <?php foreach ($sales as $sl): ?>
                                <option value="<?= $sl['id_sales']; ?>"><?= $sl['nama_sales']; ?> - <?= $sl['id_sales'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input autocomplete="off" class="form-check-input" type="checkbox" value="1"
                                name="is_active" id="is_active" checked>
                            <label class="form-check-label" for="is_active">
                                Active?
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" type="hidden" class="form-control" id="date_created"
                            name="date_created" value="<?= date('Y-m-d H:i:s'); ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $no = 0;
foreach ($users as $u):
    $no++; ?>
    <div class="row">
        <div id="modal-edit<?= $u['id'] ?>" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="modal-edit<?= $u['id'] ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-edit<?= $u['id'] ?>Label">Edit Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="<?= base_url('admin/usersedit'); ?>" method="post">
                        <div class="modal-body">
                            <input type="hidden" readonly value="<?= $u['id']; ?>" name="id" class="form-control">
                            <input type="hidden" name="current_email" value="<?= $u['email']; ?>">

                            <div class="form-group">
                                <label for="users<?= $u['name'] ?>" class="col-form-label">Nama User:</label>
                                <input type="text" class="form-control" id="users<?= $u['name'] ?>" name="name"
                                    value="<?= $u['name'] ?>" placeholder="Masukkan Nama User" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="users<?= $u['email'] ?>" class="col-form-label">Nama Email:</label>
                                <input type="text" class="form-control" id="users<?= $u['email'] ?>" name="email"
                                    value="<?= $u['email'] ?>" placeholder="Masukkan Email User" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="users<?= $u['password'] ?>" class="col-form-label">Password Baru User:</label>
                                <input type="text" class="form-control" id="users<?= $u['password'] ?>" name="password"
                                    placeholder="Masukkan Password Baru User" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="users<?= $u['role_id'] ?>" class="col-form-label">Role User:</label>
                                <select name="role_id" id="role_id" class="form-control">
                                    <option value="<?= $u['role_id'] ?>"><?= $u['nama_role'] ?> - <?= $u['role_id'] ?>
                                    </option>
                                    <option value="">--Pilih Role - ID Role--</option>
                                    <?php foreach ($role as $rl): ?>
                                        <option value="<?= $rl['id']; ?>"><?= $rl['role']; ?> - <?= $rl['id'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="users<?= $u['id_sales'] ?>" class="col-form-label">Sales User:</label>
                                <select name="id_sales" id="id_sales" class="form-control">
                                    <option value="<?= $u['id_sales'] ?>"><?= $u['nama_sales'] ?> - <?= $u['id_sales'] ?>
                                    </option>
                                    <option value="">--Pilih Sales - ID Sales--</option>
                                    <?php foreach ($sales as $sl): ?>
                                        <option value="<?= $sl['id_sales']; ?>"><?= $sl['nama_sales']; ?> -
                                            <?= $sl['id_sales'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" name="is_active"
                                        id="is_active<?= $u['is_active'] ?>" <?= $u['is_active'] == 1 ? 'checked' : '' ?>>
                                    <label for="users<?= $u['is_active'] ?>" class="col-form-label">Active?</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>