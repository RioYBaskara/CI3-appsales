<div class="container m-3">
    <h3 class="mt-3">Daftar Aktivitas</h3>
    <div class="row mt-3">
        <div class="col-md-12">
            <a href="<?= base_url(); ?>sales/tambah" class="btn btn-primary">Tambah Data Aktivitas</a>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Nama Sales</th>
                        <th>Hari</th>
                        <th>Tanggal</th>
                        <th>Nasabah</th>
                        <th>Aktivitas</th>
                        <th>Status Nasabah</th>
                        <th>Keterangan</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sales)): ?>
                        <tr>
                            <td colspan="11">
                                <div class="alert alert-danger" role="alert">
                                    Data Tidak Ditemukan!
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php $i = 1;
                    foreach ($sales as $ktg): ?>
                        <tr style="text-transform: uppercase;">
                            <th><?= $i++; ?></th>
                            <td><?= $ktg['id']; ?></td>
                            <td><?= $ktg['nama_sales']; ?></td>
                            <td><?= $ktg['hari']; ?></td>
                            <td><?= $ktg['tanggal']; ?></td>
                            <td><?= $ktg['nasabah']; ?></td>
                            <td><?= $ktg['kegiatan']; ?></td>
                            <td><?= $ktg['status_nasabah']; ?></td>
                            <td><?= $ktg['keterangan']; ?></td>
                            <td><img src="<?= base_url('uploads/' . $ktg['foto']); ?>" alt="<?= $ktg['nama_sales']; ?>"
                                    width="100"></td>
                            <td>
                                <a href="<?= base_url(); ?>sales/detail/<?= $ktg['id']; ?>" class="btn btn-primary"><i
                                        class="fa fa-eye"></i></a>
                                <a href="<?= base_url(); ?>sales/ubah/<?= $ktg['id']; ?>" class="btn btn-success"><i
                                        class="fa fa-pencil-alt"></i></a>
                                <a href="<?= base_url(); ?>sales/hapus/<?= $ktg['id']; ?>"
                                    class="btn btn-danger tombol-hapus"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>