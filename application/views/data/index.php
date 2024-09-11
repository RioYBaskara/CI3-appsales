<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flashswal') ?>"></div>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
        <a href="<?= base_url('export/excell/exportnasabah'); ?>"
            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
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
            <table class="table table-hover">

                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Sales - ID Sales</th>
                        <th scope="col">Nama Nasabah</th>
                        <th scope="col">No Rekening</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($nasabah as $nsb): ?>
                        <tr>
                            <th><?= ++$start; ?></th>
                            <td><?= $nsb['id_nasabah']; ?></td>
                            <td><?= $nsb['nama_sales']; ?> - <?= $nsb['id_sales']; ?></td>
                            <td><?= $nsb['nama_nasabah']; ?></td>
                            <td><?= $nsb['no_rekening']; ?></td>
                            <td>
                                <a data-toggle="modal" data-target="#modal-edit<?= $nsb['id_nasabah'] ?>"
                                    class="btn btn-success  "><i class="fa fa-pencil-alt"></i></a>
                                <a href="<?= base_url(); ?>data/nasabahhapus/<?= $nsb['id_nasabah']; ?>"
                                    class="btn btn-danger tombol-hapus"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?= $pagination; ?>
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
                    <div class="form-group">
                        <select name="id_sales" id="id_sales" class="form-control">
                            <option value="">--Pilih Sales - ID Sales--</option>
                            <?php foreach ($sales as $sl): ?>
                                <option value="<?= $sl['id_sales']; ?>"><?= $sl['nama_sales']; ?> -
                                    <?= $sl['id_sales'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" type="text" class="form-control" id="nama_nasabah" name="nama_nasabah"
                            placeholder="Nama Nasabah">
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" type="text" class="form-control" id="no_rekening" name="no_rekening"
                            placeholder="No Rekening">
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
                            <div class="form-group">
                                <label for="nasabah<?= $nsb['id_nasabah'] ?>" class="col-form-label">Sales:</label>
                                <select name="id_sales" id="id_sales" class="form-control">
                                    <option value="<?= $nsb['id_sales'] ?>"><?= $nsb['nama_sales'] ?> -
                                        <?= $nsb['id_sales'] ?>
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
                                <label for="nasabah<?= $nsb['nama_nasabah'] ?>" class="col-form-label">Nama Nasabah:</label>
                                <input type="text" class="form-control" id="nasabah<?= $nsb['nama_nasabah'] ?>"
                                    name="nama_nasabah" value="<?= $nsb['nama_nasabah'] ?>"
                                    placeholder="Masukkan Nama Nasabah" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="nasabah<?= $nsb['no_rekening'] ?>" class="col-form-label">No Rekening:</label>
                                <input type="text" class="form-control" id="nasabah<?= $nsb['no_rekening'] ?>"
                                    name="no_rekening" value="<?= $nsb['no_rekening'] ?>" placeholder="Masukkan No Rekening"
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