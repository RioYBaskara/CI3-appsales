<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flashswal') ?>"></div>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>

        <div class="d-flex flex-column">
            <!-- Tombol Export Semua Data -->
            <form class="justify-content-end d-flex" method="post"
                action="<?= base_url('export/excell/exportClosing'); ?>">
                <button class="btn btn-primary mb-3" name="export_all" type="submit">
                    <i class="fas fa-download fa-sm text-white-50"></i> Export Semua Data
                </button>
            </form>

            <!-- Form Export Berdasarkan Tanggal -->
            <form method="post" action="<?= base_url('export/excell/exportClosing'); ?>">
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

            <?= $this->session->flashdata('message'); ?>

            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newClosing">Add New
                Closing</a>
            <table class="table table-hover">

                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Sales</th>
                        <th scope="col">Nasabah</th>
                        <th scope="col">Hari</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">No Rekening</th>
                        <th scope="col">Nominal</th>
                        <th scope="col">Foto</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($closing as $cls): ?>
                        <tr>
                            <th><?= ++$start; ?></th>
                            <td><?= $cls['id_closing']; ?></td>
                            <td><?= $cls['nama_sales']; ?></td>
                            <td><?= $cls['nama_nasabah']; ?></td>
                            <td><?= $cls['hari']; ?></td>
                            <td><?= date("j F Y", strtotime($cls['tanggal'])); ?></td>
                            <td><?= $cls['no_rekening']; ?></td>
                            <td><?= 'Rp ' . number_format($cls['nominal_closing'], 2, ',', '.'); ?></td>
                            <td>
                                <img src="<?= base_url('assets/img/closing/') . $cls['upload_foto']; ?>" alt="Foto Closing"
                                    class="img-thumbnail" style="width: 100px; height: auto;">
                            </td>
                            <td>
                                <a data-toggle="modal" data-target="#modal-edit<?= $cls['id_closing'] ?>"
                                    class="btn btn-success  "><i class="fa fa-pencil-alt"></i></a>
                                <a href="<?= base_url(); ?>data/closinghapus/<?= $cls['id_closing']; ?>"
                                    class="btn btn-danger tombol-hapus"><i class="fa fa-trash"></i></a>
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
<div class="modal fade" id="newClosing" tabindex="-1" role="dialog" aria-labelledby="newClosing" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newClosingModalLabel">Add New Closing</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open_multipart('data/closing'); ?>
            <form action="<?= base_url('data/closing'); ?>" method="post">
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
                        <select name="id_nasabah" id="id_nasabah" class="form-control">
                            <option value="">--Pilih Nasabah--</option>
                            <?php foreach ($nasabah as $nsb): ?>
                                <option value="<?= $nsb['id_nasabah']; ?>"><?= $nsb['nama_nasabah']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" type="date" class="form-control" id="tanggal" name="tanggal">
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" type="text" class="form-control" id="no_rekening" name="no_rekening"
                            placeholder="Nomor Rekening">
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" type="text" class="form-control" id="nominal_closing"
                            name="nominal_closing" placeholder="Nominal Closing">
                    </div>
                    <div class="form-group row">
                        <div class="col-sm">Foto</div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-5">
                                    <img src="<?= base_url('assets/img/closing/'); ?>default.jpg" alt=""
                                        class="img-thumbnail">
                                </div>
                                <div class="col-sm-7">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="image" name="image">
                                        <label class="custom-file-label" for="image">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
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
foreach ($closing as $cls):
    $no++; ?>
    <div class="row">
        <div id="modal-edit<?= $cls['id_closing'] ?>" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="modal-edit<?= $cls['id_closing'] ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-edit<?= $cls['id_closing'] ?>Label">Edit Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?= form_open_multipart('data/closingedit'); ?>
                    <form action="<?= base_url('data/closingedit'); ?>" method="post">
                        <div class="modal-body">
                            <input type="hidden" readonly value="<?= $cls['id_closing']; ?>" name="id_closing"
                                class="form-control">
                            <?php if ($role_id == 1): ?>
                                <!-- Jika Admin, tampilkan opsi select untuk memilih sales -->
                                <div class="form-group">
                                    <label for="closing<?= $cls['id_closing'] ?>" class="col-form-label">Sales:</label>
                                    <select name="id_sales" id="id_sales" class="form-control">
                                        <option value="<?= $cls['id_sales'] ?>"><?= $cls['nama_sales'] ?>
                                        </option>
                                        <option value="">--Pilih Sales--</option>
                                        <?php foreach ($sales as $sl): ?>
                                            <option value=" <?= $sl['id_sales']; ?>"><?= $sl['nama_sales']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php else: ?>
                                <!-- Jika bukan Admin, tampilkan sales user otomatis -->
                                <input type="hidden" name="id_sales" value="<?= $id_sales ?>">
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="closing<?= $cls['id_nasabah'] ?>" class="col-form-label">Nama
                                    Nasabah:</label>
                                <select name="id_nasabah" id="closing<?= $cls['id_nasabah'] ?>" class="form-control">
                                    <option value="<?= $cls['id_nasabah'] ?>"><?= $cls['nama_nasabah'] ?>
                                    </option>
                                    <option value="">--Pilih Nasabah--</option>
                                    <?php foreach ($nasabah as $nsb): ?>
                                        <option value=" <?= $nsb['id_nasabah']; ?>"><?= $nsb['nama_nasabah']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="closing<?= $cls['tanggal'] ?>" class="col-form-label">Tanggal
                                    Closing:</label>
                                <input type="date" class="form-control" id="closing<?= $cls['tanggal'] ?>" name="tanggal"
                                    value="<?= $cls['tanggal'] ?>" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="closing<?= $cls['no_rekening'] ?>" class="col-form-label">Nomor
                                    Rekening:</label>
                                <input type="text" class="form-control" id="closing<?= $cls['no_rekening'] ?>"
                                    name="no_rekening" value="<?= $cls['no_rekening'] ?>"
                                    placeholder="Masukkan Nomor Rekening" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="closing<?= $cls['nominal_closing'] ?>" class="col-form-label">Nominal:</label>
                                <input type="text" class="form-control" id="closing<?= $cls['nominal_closing'] ?>"
                                    name="nominal_closing" value="<?= $cls['nominal_closing'] ?>"
                                    placeholder="Masukkan Nominal" autocomplete="off">
                            </div>
                            <div class="form-group row">
                                <div class="col-sm">Foto</div>
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <img src="<?= base_url('assets/img/closing/') . $cls['upload_foto']; ?>" alt=""
                                                class="img-thumbnail">
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="image" name="image">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
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