<ul>
    <li>$user- <?= var_dump($user); ?></li>
    <li>$title- <?= var_dump($title); ?></li>
    <li>$user'image'- <?= $user['image'] ?></li>
    <li>url$user.'image'- <?= base_url('assets/img/profile/') . $user['image']; ?></li>
    <li>url$user.'image'- <?= base_url('assets/$menufile/') . $user['image']; ?></li>
    <li>Func, getSubMenu- <?= var_dump($subMenu); ?></li>
    <li>$menu- <?= var_dump($menu); ?></li>
</ul>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <!-- content -->
    <div class="row">
        <div class="col-lg">
            <?php if (validation_errors()): ?>
            <?php endif; ?>
            <?= form_error('menu', '<div class="alert alert-danger" role="alert">', '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>'); ?>

            <?= $this->session->flashdata('message'); ?>
            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newSubMenuModal">Add New
                Submenu</a>
            <table class="table table-hover">

                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Menu - ID</th>
                        <th scope="col">URL</th>
                        <th scope="col">Icon</th>
                        <th scope="col">Active</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($subMenu as $sm): ?>
                        <tr>
                            <th scope="row"><?= $i; ?></th>
                            <td><?= $sm['id'] ?></td>
                            <td><?= $sm['title'] ?></td>
                            <td><?= $sm['menu'] ?> - <?= $sm['menu_id'] ?></td>
                            <td><?= $sm['url'] ?></td>
                            <td><?= $sm['icon'] ?></td>
                            <td><?= $sm['is_active'] ?></td>
                            <td>
                                <a data-toggle="modal" data-target="#modal-edit<?= $sm['id'] ?>"
                                    class="btn btn-success  "><i class="fa fa-pencil-alt"></i></a>
                                <a href="<?= base_url(); ?>menu/submenuhapus/<?= $sm['id']; ?>"
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
<div class="modal fade" id="newSubMenuModal" tabindex="-1" role="dialog" aria-labelledby="newSubMenuModal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newSubMenuModalLabel">Add New Sub Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('menu/submenu'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="title" name="title" placeholder="Title Name">
                    </div>
                    <div class="form-group">
                        <select name="menu_id" id="menu_id" class="form-control">
                            <option value="">--Select Menu--</option>
                            <?php foreach ($menu as $sm): ?>
                                <option value="<?= $sm['id']; ?>"><?= $sm['menu']; ?> - <?= $sm['id'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="url" name="url" placeholder="Submenu URL">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="icon" name="icon" placeholder="Submenu Icon">
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active"
                                checked>
                            <label class="form-check-label" for="is_active">
                                Active?
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $no = 0;
foreach ($subMenu as $sm):
    $no++; ?>
    <div class="row">
        <div id="modal-edit<?= $sm['id'] ?>" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="modal-edit<?= $sm['id'] ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-edit<?= $sm['id'] ?>Label">Edit Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="<?= base_url('menu/submenuedit'); ?>" method="post">
                        <div class="modal-body">
                            <input type="hidden" readonly value="<?= $sm['id']; ?>" name="id" class="form-control">
                            <div class="form-group">
                                <input type="text" class="form-control" id="title" name="title" placeholder="Title Name"
                                    autocomplete="off" value="<?= $sm['title'] ?>">
                            </div>
                            <div class="form-group">
                                <select name="menu_id" id="menu_id" class="form-control">
                                    <option value="<?= $sm['menu_id'] ?>"><?= $sm['menu'] ?> - <?= $sm['menu_id'] ?>
                                    </option>
                                    <?php foreach ($menu as $smenu): ?>
                                        <option value="<?= $smenu['id']; ?>"><?= $smenu['menu']; ?> - <?= $smenu['id'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="url" name="url" placeholder="Submenu URL"
                                    autocomplete="off" value="<?= $sm['url'] ?>">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="icon" name="icon" placeholder="Submenu Icon"
                                    autocomplete="off" value="<?= $sm['icon'] ?>">
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" name="is_active"
                                        id="is_active<?= $sm['id'] ?>" <?= $sm['is_active'] == 1 ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="is_active<?= $sm['id'] ?>">
                                        Active?
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>