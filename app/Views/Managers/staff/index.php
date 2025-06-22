<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Staff Members</h1>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($staff)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staff as $member): ?>
                        <tr>
                            <td><?= esc($member['staff_id']) ?></td>
                            <td><?= esc($member['full_name']) ?></td>
                            <td><?= esc($member['role']) ?></td>
                            <td><?= esc($member['email']) ?></td>
                            <td>
                                <a href="<?= base_url('manager/staff/show/' . $member['staff_id']) ?>" class="btn btn-info btn-sm">View</a>
                                <a href="<?= base_url('manager/staff/edit/' . $member['staff_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <form action="<?= base_url('manager/staff/destroy/' . $member['staff_id']) ?>" method="post" style="display:inline;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No staff members found.</p>
        <?php endif; ?>
        
        <a href="<?= base_url('manager/staff/create') ?>" class="btn btn-success">Add New Staff</a>
    </div>
</body>
</html>
