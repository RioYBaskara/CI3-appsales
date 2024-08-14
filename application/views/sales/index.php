<ul>
    <li>sales- <?= var_dump($sales); ?></li>
</ul>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $judul; ?></h1>
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
            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newSalesModal">Tambah Sales
                Baru</a>
            <table class="table table-hover">

                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Nama Sales</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($sales as $s): ?>
                        <tr>
                            <th scope="row"><?= $i; ?></th>
                            <td><?= $s['id_sales']; ?></td>
                            <td><?= $s['nama_sales']; ?></td>
                            <td>
                                <a data-toggle="modal" data-target="#modal-edit<?= $s['id_sales'] ?>"
                                    class="btn btn-success  "><i class="fa fa-pencil-alt"></i></a>
                                <a href="<?= base_url(); ?>sales/hapus/<?= $s['id_sales']; ?>"
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
<div class="modal fade" id="newSalesModal" tabindex="-1" role="dialog" aria-labelledby="newSalesModal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newSalesModalLabel">Tambah Sales Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('sales'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="sales" name="sales" placeholder="Nama Sales">
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
foreach ($sales as $s):
    $no++; ?>
    <div class="row">
        <div id="modal-edit<?= $s['id_sales'] ?>" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="modal-edit<?= $s['id_sales'] ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-edit<?= $s['id_sales'] ?>Label">Edit Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="<?= base_url('sales/edit'); ?>" method="post">
                        <div class="modal-body">
                            <input type="hidden" readonly value="<?= $s['id_sales']; ?>" name="id" class="form-control">
                            <div class="form-group">
                                <label for="sales<?= $s['id_sales'] ?>" class="col-form-label">Nama Sales:</label>
                                <input type="text" class="form-control" id="sales<?= $s['id_sales'] ?>" name="sales"
                                    value="<?= $s['nama_sales'] ?>" placeholder="Masukkan Modal" autocomplete="off">
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