<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flashswal') ?>"></div>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
    </div>

    <div class="row contoh-surat">
        <div class="col">
            <h4>Contoh Surat</h4>
            <div class="row justify-content-center">
                <div class="col-md-3 mb-3">
                    <img src="<?= base_url('assets/img/surat/1.jpg'); ?>" alt="Contoh Surat 1"
                        class="img-fluid img-thumbnail">
                </div>
                <div class="col-md-3 mb-3">
                    <img src="<?= base_url('assets/img/surat/2.jpg'); ?>" alt="Contoh Surat 2"
                        class="img-fluid img-thumbnail">
                </div>
            </div>
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


            <!-- nanti di tengah sini ada foto contoh surat -->

            <?= $this->session->flashdata('message'); ?>

            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newSurat">Add New
                Surat Audiensi</a>
            <table class="table table-hover">

                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Nama Tujuan</th>
                        <th scope="col">Alamat Tujuan</th>
                        <th scope="col">Perihal</th>
                        <th scope="col">Nama Institusi</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($surataudiensi as $sra): ?>
                        <tr>
                            <th><?= ++$start; ?></th>
                            <td><?= $sra['id']; ?></td>
                            <!-- <td><?= date("j F Y", strtotime($sra['tanggal'])); ?></td> -->
                            <td><?= $sra['tanggal']; ?></td>
                            <td><?= $sra['nama_tujuan']; ?></td>
                            <td><?= $sra['alamat_tujuan']; ?></td>
                            <td><?= $sra['perihal']; ?></td>
                            <td><?= $sra['nama_institusi']; ?></td>
                            <td>
                                <a data-toggle="modal" data-target="#modal-edit<?= $sra['id']; ?>"
                                    class="btn btn-success mt-1"><i class="fa fa-pencil-alt"></i></a>
                                <a href="<?= base_url(); ?>surat/surathapus/<?= $sra['id']; ?>"
                                    class="btn btn-danger tombol-hapus mt-1"><i class="fa fa-trash"></i></a>
                                <a href="<?= base_url(); ?>surat/surataudiensiexport/<?= $sra['id']; ?>"
                                    class="btn btn-info mt-1"><i class="fas fa-envelope-open-text"></i></a>
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
<div class="modal fade" id="newSurat" tabindex="-1" role="dialog" aria-labelledby="newSurat" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newSuratModalLabel">Add New Surat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('surat'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <input autocomplete="off" type="date" class="form-control" id="tanggal" name="tanggal">
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" type="text" class="form-control" id="nama_tujuan" name="nama_tujuan"
                            placeholder="Nama Tujuan">
                        <small>Contoh: Wakil Rektor II Universitas Sanata Dharma</small>
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" type="text" class="form-control" id="alamat_tujuan"
                            name="alamat_tujuan" placeholder="Alamat Tujuan">
                        <small>Contoh: Jl. Affandi, Mrican, Caturtunggal, Depok Sleman, DI Yogyakarta 55281</small>
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" type="text" class="form-control" id="perihal" name="perihal"
                            placeholder="Perihal">
                        <small>Contoh: Permohonan Silaturahmi (Audiensi)</small>
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" type="text" class="form-control" id="nama_institusi"
                            name="nama_institusi" placeholder="Nama Institusi">
                        <small>Contoh: Universitas Sanata Dharma</small>
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
foreach ($surataudiensi as $sra):
    $no++; ?>
    <div class="row">
        <div id="modal-edit<?= $sra['id'] ?>" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="modal-edit<?= $sra['id'] ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-edit<?= $sra['id'] ?>Label">Edit Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="<?= base_url('surat/suratedit'); ?>" method="post">
                        <div class="modal-body">
                            <input type="hidden" readonly value="<?= $sra['id']; ?>" name="id" class="form-control">
                            <div class="form-group">
                                <label for="surat<?= $sra['tanggal'] ?>" class="col-form-label">Tanggal
                                    Surat:</label>
                                <input type="date" class="form-control" id="surat<?= $sra['tanggal'] ?>" name="tanggal"
                                    value="<?= $sra['tanggal'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="surat<?= $sra['nama_tujuan'] ?>" class="col-form-label">Nama Tujuan:</label>
                                <input type="text" class="form-control" id="surat<?= $sra['nama_tujuan'] ?>"
                                    name="nama_tujuan" value="<?= $sra['nama_tujuan'] ?>" placeholder="Masukkan Nama Tujuan"
                                    autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="surat<?= $sra['alamat_tujuan'] ?>" class="col-form-label">Alamat Tujuan:</label>
                                <input type="text" class="form-control" id="surat<?= $sra['alamat_tujuan'] ?>"
                                    name="alamat_tujuan" value="<?= $sra['alamat_tujuan'] ?>"
                                    placeholder="Masukkan Alamat Tujuan" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="surat<?= $sra['perihal'] ?>" class="col-form-label">Perihal:</label>
                                <input type="text" class="form-control" id="surat<?= $sra['perihal'] ?>" name="perihal"
                                    value="<?= $sra['perihal'] ?>" placeholder="Masukkan Perihal" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="surat<?= $sra['nama_institusi'] ?>" class="col-form-label">Nama
                                    Institusi:</label>
                                <input type="text" class="form-control" id="surat<?= $sra['nama_institusi'] ?>"
                                    name="nama_institusi" value="<?= $sra['nama_institusi'] ?>"
                                    placeholder="Masukkan Nama Institusi" autocomplete="off">
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