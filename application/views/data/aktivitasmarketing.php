<!-- Begin Page Content -->
<div class="container-fluid">
    <?= var_dump($aktivitas_marketing); ?>
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

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

            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newAktivitas">Add New
                Aktivitas</a>
            <table class="table table-hover">

                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Sales - ID Sales</th>
                        <th scope="col">Nasabah - ID Nasabah</th>
                        <th scope="col">Hari</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Aktivitas</th>
                        <th scope="col">Status</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">Foto</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($aktivitas_marketing as $akm): ?>
                        <tr>
                            <th scope="row"><?= $i; ?></th>
                            <td><?= $akm['id_aktivitas']; ?></td>
                            <td><?= $akm['nama_sales']; ?> - <?= $akm['id_sales']; ?></td>
                            <td><?= $akm['nama_nasabah']; ?> - <?= $akm['id_nasabah']; ?></td>
                            <td><?= $akm['hari']; ?></td>
                            <td><?= $akm['tanggal']; ?></td>
                            <td><?= $akm['aktivitas']; ?></td>
                            <td><?= $akm['status']; ?></td>
                            <td><?= $akm['keterangan']; ?></td>
                            <td>
                                <img src="<?= base_url('assets/img/aktivitas/') . $akm['upload_foto']; ?>"
                                    alt="Foto Aktivitas" class="img-thumbnail" style="width: 100px; height: auto;">
                            </td>
                            <td>
                                <a data-toggle="modal" data-target="#modal-edit<?= $akm['id_aktivitas'] ?>"
                                    class="btn btn-success  "><i class="fa fa-pencil-alt"></i></a>
                                <a href="<?= base_url(); ?>data/aktivitasmarketing/hapus/<?= $akm['id_aktivitas']; ?>"
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


<!-- End of Main Content -->


<!-- Modal -->
<div class="modal fade" id="newAktivitas" tabindex="-1" role="dialog" aria-labelledby="newAktivitas" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newAktivitasModalLabel">Add New Aktivitas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('data/aktivitasmarketing'); ?>" method="post">
                <div class="modal-body">
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
                        <select name="id_nasabah" id="id_nasabah" class="form-control">
                            <option value="">--Pilih Nasabah - ID Nasabah--</option>
                            <?php foreach ($nasabah as $nsb): ?>
                                <option value="<?= $nsb['id_nasabah']; ?>"><?= $nsb['nama_nasabah']; ?> -
                                    <?= $nsb['id_nasabah'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" type="date" class="form-control" id="tanggal" name="tanggal">
                    </div>
                    <div class="form-group">
                        <select name="aktivitas" id="aktivitas" class="form-control">
                            <option value="">--Pilih Aktivitas--</option>
                            <option value="ADMINISTRASI">ADMINISTRASI
                            </option>
                            <option value="CALL">CALL
                            </option>
                            <option value="VISIT">VISIT
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="status" id="status" class="form-control">
                            <option value="">--Pilih Status--</option>
                            <option value="NTB">NTB
                            </option>
                            <option value="ETB">ETB
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" type="text" class="form-control" id="keterangan" name="keterangan"
                            placeholder="Keterangan">
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2">Foto</div>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-3">
                                    <img src="<?= base_url('assets/img/aktivitas/') . $akm['upload_foto']; ?>" alt=""
                                        class="img-thumbnail">
                                </div>
                                <div class="col-sm-9">
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
foreach ($aktivitas_marketing as $akm):
    $no++; ?>
    <div class="row">
        <div id="modal-edit<?= $akm['id_aktivitas'] ?>" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="modal-edit<?= $akm['id_aktivitas'] ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-edit<?= $akm['id_aktivitas'] ?>Label">Edit Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="<?= base_url('data/aktivitasmarketingedit'); ?>" method="post">
                        <div class="modal-body">
                            <input type="hidden" readonly value="<?= $akm['id_aktivitas']; ?>" name="id_nasabah"
                                class="form-control">
                            <div class="form-group">
                                <label for="nasabah<?= $akm['id_aktivitas'] ?>" class="col-form-label">Sales:</label>
                                <select name="id_sales" id="id_sales" class="form-control">
                                    <option value="<?= $akm['id_sales'] ?>"><?= $akm['nama_sales'] ?> -
                                        <?= $akm['id_sales'] ?>
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
                                <label for="nasabah<?= $akm['nama_nasabah'] ?>" class="col-form-label">Nama Nasabah:</label>
                                <input type="text" class="form-control" id="nasabah<?= $akm['nama_nasabah'] ?>"
                                    name="nama_nasabah" value="<?= $akm['nama_nasabah'] ?>"
                                    placeholder="Masukkan Nama Nasabah" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="nasabah<?= $akm['no_rekening'] ?>" class="col-form-label">No Rekening:</label>
                                <input type="text" class="form-control" id="nasabah<?= $akm['no_rekening'] ?>"
                                    name="no_rekening" value="<?= $akm['no_rekening'] ?>" placeholder="Masukkan No Rekening"
                                    autocomplete="off">
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