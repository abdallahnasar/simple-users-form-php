<html lang="en">
<head>
    <title>RES Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<style>
    .thumbnail {
        max-width: 200px;
        max-height: 200px;
        margin-top: 10px;
    }
</style>
<?php
session_start();

function getCSRFToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
?>


<body>
<div class="container">
    <h2>Create User</h2>
    <form action="submit.php" method="post" enctype="multipart/form-data">
        <div class="row">

            <input type="hidden" name="csrf_token" value="<?php echo getCSRFToken(); ?>">

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" required>
                    <?php
                        if(isset($_SESSION['errors']['first_name']))
                        {
                            echo '<small class="error" style="color: red">' . $_SESSION['errors']['first_name'] . '</small>';
                        }
                    ?>

                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" required>
                    <?php
                    if(isset($_SESSION['errors']['last_name']))
                    {
                        echo '<small class="error" style="color: red">' . $_SESSION['errors']['last_name'] . '</small>';
                    }
                    ?>

                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="Image">Image</label>
                    <input type="file" class="form-control" id="image" name="image" placeholder="Image" required>
                    <?php
                    if(isset($_SESSION['errors']['image']))
                    {
                        echo '<small class="error" style="color: red">' . $_SESSION['errors']['image'] . '</small>';
                    }
                    ?>

                </div>
            </div>


            <div class="col-sm-4 thumb">
                <div class="form-group">
                    <label for="Image">Thumbnail</label>
                    <img src="" alt="Uploaded Image" class="thumbnail" id="thumbnail">
                </div>
            </div>



        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>

<script>
    $("#image").change(function () {
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                alert("Invalid image file format");
            }

            var files = this.files;
            if (files && files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#thumbnail').attr('src', e.target.result);
                }
                reader.readAsDataURL(files[0]);

            }
        }
    );

</script>
</html>
