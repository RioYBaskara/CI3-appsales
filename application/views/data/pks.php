<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flashswal') ?>"></div>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>

        <div class="d-flex flex-column">
            <!-- Tombol Export Semua Data -->
            <form class="justify-content-end d-flex" method="post" action="<?= base_url('export/excell/exportPKS'); ?>">
                <button class="btn btn-primary mb-3" name="export_all" type="submit">
                    <i class="fas fa-download fa-sm text-white-50"></i> Export Semua Data
                </button>
            </form>

            <!-- Form Export Berdasarkan Tanggal -->
            <form method="post" action="<?= base_url('export/excell/exportPKS'); ?>">
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

            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newPKS">Add New
                PKS</a>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Sales</th>
                        <th scope="col">Nasabah</th>
                        <th scope="col">No PKS</th>
                        <th scope="col">Tanggal Awal PKS</th>
                        <th scope="col">Tanggal Akhir PKS</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">Foto</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pks as $pk): ?>
                        <tr>
                            <th><?= ++$start; ?></th>
                            <td><?= $pk['id_pks']; ?></td>
                            <td><?= $pk['nama_sales']; ?></td>
                            <td><?= $pk['nama_nasabah']; ?></td>
                            <td><?= $pk['no_pks']; ?></td>
                            <td><?= date("j F Y", strtotime($pk['tanggal_awal_pks'])); ?></td>
                            <td><?= date("j F Y", strtotime($pk['tanggal_akhir_pks'])); ?>
                            <td><?= $pk['keterangan']; ?></td>
                            <td>
                                <img src="<?= base_url('assets/img/pks/') . $pk['upload_foto']; ?>" alt="Foto Aktivitas"
                                    class="img-thumbnail" style="width: 100px; height: auto;">
                            </td>
                            <td>
                                <a data-toggle="modal" data-target="#modal-edit<?= $pk['id_pks'] ?>"
                                    class="btn btn-success  "><i class="fa fa-pencil-alt"></i></a>
                                <a href="<?= base_url(); ?>data/pkshapus/<?= $pk['id_pks']; ?>"
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
<div class="modal fade" id="newPKS" tabindex="-1" role="dialog" aria-labelledby="newPKS" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newPKSModalLabel">Add New PKS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open_multipart('data/pks'); ?>
            <form action="<?= base_url('data/pks'); ?>" method="post">
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
                        <input autocomplete="off" type="text" class="form-control" id="no_pks" name="no_pks"
                            placeholder="Nomor PKS">
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" type="date" class="form-control" id="tanggal_awal_pks"
                            name="tanggal_awal_pks">
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" type="date" class="form-control" id="tanggal_akhir_pks"
                            name="tanggal_akhir_pks">
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" type="text" class="form-control" id="keterangan" name="keterangan"
                            placeholder="Keterangan">
                    </div>
                    <div class="form-group row">
                        <div class="col-sm">Foto</div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-5">
                                    <img src="<?= base_url('assets/img/pks/'); ?>default.jpg" alt=""
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
foreach ($pks as $pk):
    $no++; ?>
    <div class="row">
        <div id="modal-edit<?= $pk['id_pks'] ?>" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="modal-edit<?= $pk['id_pks'] ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-edit<?= $pk['id_pks'] ?>Label">Edit Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?= form_open_multipart('data/pksedit'); ?>
                    <form action="<?= base_url('data/pksedit'); ?>" method="post">
                        <div class="modal-body">
                            <input type="hidden" readonly value="<?= $pk['id_pks']; ?>" name="id_pks" class="form-control">
                            <input type="hidden" name="current_pks" value="<?= $pk['no_pks']; ?>">
                            <?php if ($role_id == 1): ?>
                                <!-- Jika Admin, tampilkan opsi select untuk memilih sales -->
                                <div class="form-group">
                                    <label for="aktivitas<?= $pk['id_pks'] ?>" class="col-form-label">Sales:</label>
                                    <select name="id_sales" id="id_sales" class="form-control">
                                        <option value="<?= $pk['id_sales'] ?>"><?= $pk['nama_sales'] ?>
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
                                <label for="pks<?= $pk['id_nasabah'] ?>" class="col-form-label">Nama
                                    Nasabah:</label>
                                <select name="id_nasabah" id="pks<?= $pk['id_nasabah'] ?>" class="form-control">
                                    <option value="<?= $pk['id_nasabah'] ?>"><?= $pk['nama_nasabah'] ?>
                                    </option>
                                    <option value="">--Pilih Nasabah--</option>
                                    <?php foreach ($nasabah as $nsb): ?>
                                        <option value="<?= $nsb['id_nasabah']; ?>"><?= $nsb['nama_nasabah']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="pks<?= $pk['no_pks'] ?>" class="col-form-label">No PKS:</label>
                                <input type="text" class="form-control" id="pks<?= $pk['no_pks'] ?>" name="no_pks"
                                    value="<?= $pk['no_pks'] ?>" placeholder="Masukkan Nomor PKS" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="pks<?= $pk['tanggal_awal_pks'] ?>" class="col-form-label">Tanggal
                                    Awal PKS:</label>
                                <input type="date" class="form-control" id="pks<?= $pk['tanggal_awal_pks'] ?>"
                                    name="tanggal_awal_pks" value="<?= $pk['tanggal_awal_pks'] ?>" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="pks<?= $pk['tanggal_akhir_pks'] ?>" class="col-form-label">Tanggal
                                    Akhir PKS:</label>
                                <input type="date" class="form-control" id="pks<?= $pk['tanggal_akhir_pks'] ?>"
                                    name="tanggal_akhir_pks" value="<?= $pk['tanggal_akhir_pks'] ?>" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="pks<?= $pk['keterangan'] ?>" class="col-form-label">Keterangan:</label>
                                <input type="text" class="form-control" id="pks<?= $pk['keterangan'] ?>" name="keterangan"
                                    value="<?= $pk['keterangan'] ?>" placeholder="Masukkan Keterangan" autocomplete="off">
                            </div>
                            <div class="form-group row">
                                <div class="col-sm">Foto</div>
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <img src="<?= base_url('assets/img/pks/') . $pk['upload_foto']; ?>" alt=""
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