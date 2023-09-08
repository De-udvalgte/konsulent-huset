<?php include 'view/components/header.php'; ?>
<?php $result = file_get_contents('http://localhost/konsulent-huset/api/users/' . $_SESSION["userId"]) ?>
<main role="main" class="container">
    <div class="row">
        <div class="col">
            <h1>Profile</h1>

            <button type="button" class="btn btn-primary" id="editBtn">Edit</button>
            <table class="table">

                <tr>
                    <th>Id</th>
                    <th>Type</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Email</th>
                    <th>Created</th>
                    <th>Last modified</th>
                </tr>

                <?php foreach (json_decode($result, true) as $user) { ?>

                    <tr>
                        <td>
                            <?php echo htmlspecialchars($user["userId"]) ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($user["rolesId"] === 1 ? "User" : "Admin") ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($user["firstName"]) ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($user["lastName"]) ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($user["email"]) ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($user["created"]) ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($user["modified"]) ?>
                        </td>
                    </tr>
                <?php
                };
                ?>
            </table>
        </div>
    </div>
</main>
<script>
    var btn = document.getElementById('editBtn');
    btn.addEventListener('click', function() {
        document.location.href = 'http://localhost/konsulent-huset/profile/edit';
    });
</script>
<?php include 'view/components/footer.php'; ?>