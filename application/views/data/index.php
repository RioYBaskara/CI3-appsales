<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flashswal') ?>"></div>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>

        <div class="d-flex flex-column">
            <!-- Tombol Export Semua Data -->
            <form class="justify-content-end d-flex" method="post"
                action="<?= base_url('export/excell/exportnasabah'); ?>">
                <button class="btn btn-primary mb-3" name="export_all" type="submit">
                    <i class="fas fa-download fa-sm text-white-50"></i> Export Semua Data
                </button>
            </form>

            <!-- Form Export Berdasarkan Tanggal -->
            <form method="post" action="<?= base_url('export/excell/exportnasabah'); ?>">
                <div class="input-group">
                    <input type="date" name="selected_date" class="form-control" required>
                    <select name="export_option" class="custom-select" id="exportOption" required>
                        <option value="week">Export Data Minggu Ini (berdasarkan tanggal)</option>
                        <option value="month">Export Data Bulan Ini (berdasarkan tanggal)</option>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i
                                class="fas fa-download fa-sm text-white-50"></i>
                            Generate Report</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- content -->
    <div class="row">
        <div class="col-lg">
            <?php if (validation_errors()): ?>
                <div class="alert alert-danger" role="alert">
                    <?= validation_errors() ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('pesan')): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata('pesan'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newMenuNasabah">Add New
                Nasabah</a>

            <!-- Tabel Nasabah Pinned -->
            <?php if (!empty($pinned_nasabah)): ?>
                <h4>Pinned Data</h4>
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Sales</th>
                            <th scope="col">Nama Nasabah</th>
                            <th scope="col">Tanggal Daftar</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pinned_nasabah as $pnndnsb): ?>
                            <tr>
                                <td><?= $pnndnsb['id_nasabah']; ?></td>
                                <td><?= $pnndnsb['nama_sales']; ?></td>
                                <td><?= $pnndnsb['nama_nasabah']; ?></td>
                                <td><?= $pnndnsb['created_at']; ?></td>
                                <td>
                                    <a data-toggle="modal" data-target="#modal-edit<?= $pnndnsb['id_nasabah'] ?>"
                                        class="btn btn-success"><i class="fa fa-pencil-alt"></i></a>
                                    <a href="<?= base_url(); ?>data/nasabahhapus/<?= $pnndnsb['id_nasabah']; ?>"
                                        class="btn btn-danger tombol-hapus"><i class="fa fa-trash"></i></a>
                                    <button class="btn btn-primary btn-unpin"
                                        data-id="<?php echo $pnndnsb['id_nasabah']; ?>">Unpin</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <h4>Data Nasabah</h4>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Sales</th>
                        <th scope="col">Nama Nasabah</th>
                        <th scope="col">Tanggal Daftar</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($nasabah as $nsb): ?>
                        <tr>
                            <th><?= ++$start; ?></th>
                            <td><?= $nsb['id_nasabah']; ?></td>
                            <td><?= $nsb['nama_sales']; ?></td>
                            <td><?= $nsb['nama_nasabah']; ?></td>
                            <td><?= $nsb['created_at']; ?></td>
                            <td>
                                <a data-toggle="modal" data-target="#modal-edit<?= $nsb['id_nasabah'] ?>"
                                    class="btn btn-success"><i class="fa fa-pencil-alt"></i></a>
                                <a href="<?= base_url(); ?>data/nasabahhapus/<?= $nsb['id_nasabah']; ?>"
                                    class="btn btn-danger tombol-hapus"><i class="fa fa-trash"></i></a>
                                <button class="btn btn-primary btn-pin"
                                    data-id="<?php echo $nsb['id_nasabah']; ?>">Pin</button>
                            </td>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?= $this->pagination->create_links(); ?>

        </div>
    </div>

</div>
<!-- /.container-fluid -->


<!-- End of Main Content -->


<!-- Modal -->
<div class="modal fade" id="newMenuNasabah" tabindex="-1" role="dialog" aria-labelledby="newMenuNasabah"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newNasabahModalLabel">Add New Nasabah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('data'); ?>" method="post">
                <div class="modal-body">
                    <?php if ($role_id == 1): ?>
                        <!-- Jika Admin, tampilkan opsi select untuk memilih sales -->
                        <div class="form-group">
                            <select name="id_sales" id="id_sales" class="form-control">
                                <option value="">--Pilih Sales--</option>
                                <?php foreach ($sales as $sl): ?>
                                    <option value="<?= $sl['id_sales']; ?>"><?= $sl['nama_sales']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <!-- Jika bukan Admin, tampilkan sales user otomatis -->
                        <input type="hidden" name="id_sales" value="<?= $id_sales ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <input autocomplete="off" type="text" class="form-control" id="nama_nasabah" name="nama_nasabah"
                            placeholder="Nama Nasabah">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
        </div>
        </form>
    </div>
</div>
</div>

<?php $no = 0;
foreach ($nasabah as $nsb):
    $no++; ?>
    <div class="row">
        <div id="modal-edit<?= $nsb['id_nasabah'] ?>" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="modal-edit<?= $nsb['id_nasabah'] ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-edit<?= $nsb['id_nasabah'] ?>Label">Edit Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="<?= base_url('data/nasabahedit'); ?>" method="post">
                        <div class="modal-body">
                            <input type="hidden" readonly value="<?= $nsb['id_nasabah']; ?>" name="id_nasabah"
                                class="form-control">
                            <?php if ($role_id == 1): ?>
                                <!-- Jika Admin, tampilkan opsi select untuk memilih sales -->
                                <div class="form-group">
                                    <label for="nasabah<?= $nsb['id_nasabah'] ?>" class="col-form-label">Sales:</label>
                                    <select name="id_sales" id="id_sales" class="form-control">
                                        <option value="<?= $nsb['id_sales'] ?>"><?= $nsb['nama_sales'] ?>
                                        </option>
                                        <option value="">--Pilih Sales--</option>
                                        <?php foreach ($sales as $sl): ?>
                                            <option value="<?= $sl['id_sales']; ?>"><?= $sl['nama_sales']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php else: ?>
                                <!-- Jika bukan Admin, tampilkan sales user otomatis -->
                                <input type="hidden" name="id_sales" value="<?= $id_sales ?>">
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="nasabah<?= $nsb['nama_nasabah'] ?>" class="col-form-label">Nama Nasabah:</label>
                                <input type="text" class="form-control" id="nasabah<?= $nsb['nama_nasabah'] ?>"
                                    name="nama_nasabah" value="<?= $nsb['nama_nasabah'] ?>"
                                    placeholder="Masukkan Nama Nasabah" autocomplete="off">
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

<?php $no = 0;
foreach ($pinned_nasabah as $pnndnsb):
    $no++; ?>
    <div class="row">
        <div id="modal-edit<?= $pnndnsb['id_nasabah'] ?>" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="modal-edit<?= $pnndnsb['id_nasabah'] ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-edit<?= $pnndnsb['id_nasabah'] ?>Label">Edit Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="<?= base_url('data/nasabahedit'); ?>" method="post">
                        <div class="modal-body">
                            <input type="hidden" readonly value="<?= $pnndnsb['id_nasabah']; ?>" name="id_nasabah"
                                class="form-control">
                            <?php if ($role_id == 1): ?>
                                <!-- Jika Admin, tampilkan opsi select untuk memilih sales -->
                                <div class="form-group">
                                    <label for="nasabah<?= $pnndnsb['id_nasabah'] ?>" class="col-form-label">Sales:</label>
                                    <select name="id_sales" id="id_sales" class="form-control">
                                        <option value="<?= $pnndnsb['id_sales'] ?>"><?= $pnndnsb['nama_sales'] ?>
                                        </option>
                                        <option value="">--Pilih Sales--</option>
                                        <?php foreach ($sales as $sl): ?>
                                            <option value="<?= $sl['id_sales']; ?>"><?= $sl['nama_sales']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php else: ?>
                                <!-- Jika bukan Admin, tampilkan sales user otomatis -->
                                <input type="hidden" name="id_sales" value="<?= $id_sales ?>">
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="nasabah<?= $pnndnsb['nama_nasabah'] ?>" class="col-form-label">Nama
                                    Nasabah:</label>
                                <input type="text" class="form-control" id="nasabah<?= $pnndnsb['nama_nasabah'] ?>"
                                    name="nama_nasabah" value="<?= $pnndnsb['nama_nasabah'] ?>"
                                    placeholder="Masukkan Nama Nasabah" autocomplete="off">
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