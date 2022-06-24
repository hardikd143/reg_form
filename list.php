<?php
include './db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id       = $_POST['eid'];
    $name       = $_POST['name'];
    $email      = $_POST['email'];
    $address    = $_POST['address'];
    $phone      = $_POST['phone'];
    $photo      = $_FILES['newPhoto']['name'];
    $photoSTR = "";
    $imagesArr = [];
    $t = time();
    $last_updated = date("m-d-Y h:i:s", $t);
    // delete old photos if new photos was added 
    function deleteOldPhotos($arr)
    {
        foreach ($arr as $val) {
            unlink('./uploads/' . $val);
        }
    }
    // get photos from database if new photos was added 
    function getPhotos($id)
    {
        include './db.php';
        $photoquery = "SELECT * from `reg_table` WHERE `id` = $id";
        $photoData = mysqli_query($conn, $photoquery);
        // getting photo string 
        $phD = $photoData->fetch_assoc();
        // converting string to array
        $photo2update = explode(',', $phD['photo']);
        // return photos array from database
        deleteOldPhotos($photo2update);
        return $photo2update;
    }
    if ($photo[0]) {
        $photoArr = getPhotos($id);
        foreach ($_FILES['newPhoto']['name'] as $key => $iNAme) {
            $imgNewName = $t . "_" . $iNAme;
            array_push($imagesArr, $imgNewName);
        }
        foreach ($_FILES['newPhoto']['tmp_name'] as $key => $tempName) {
            $ele = $imagesArr[$key];
            move_uploaded_file($tempName, 'uploads/' . $ele);
        }
        $photoSTR = implode(',', $imagesArr);
    }
    // Sql query to be executed 
    if ($photo[0]) {
        $insertItem = "UPDATE `reg_table` SET `name` = '$name', `email` = '$email', `address` = '$address', `phone` = $phone , `photo` = '$photoSTR' , `last_updated` = CURRENT_TIMESTAMP() WHERE `id` = $id";
    } else {
        $insertItem = "UPDATE `reg_table` SET `name` = '$name', `email` = '$email', `address` = '$address', `phone` = $phone , `last_updated` = CURRENT_TIMESTAMP() WHERE `id` = $id";
    }
    $INSresult  = mysqli_query($conn, $insertItem);
    if ($INSresult) {
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        header('location: ' . $_SERVER['PHP_SELF']);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="editModal hide modal largeModal" data-dismiss="true" data-form="editform">
        <form class="editform animateModal form clearfix" action="list.php" method="POST" data-id="" enctype="multipart/form-data">
            <div class="border-bottom border-1 mb-2 pb-2">
                <h2>Edit Info</h2>
                <button class="closeEdit fBtn closeBtn black" type="button" data-target-modal="editModal" data-form="editform">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                    </svg>
                </button>
            </div>
            <div class="row">
                <div class="col-md-6 col-12 inputWrapper">
                    <div>
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="nameInp editInput compareInp">
                        <input type="text" name="eid" id="id" hidden>
                    </div>
                </div>
                <div class="col-md-6 col-12 inputWrapper">
                    <div>
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="editInput compareInp">
                    </div>
                </div>
                <div class="col-md-6 col-12 inputWrapper">
                    <div>
                        <label for="name">Address</label>
                        <textarea name="address" id="address" cols="30" rows="2" class="editInput compareInp"></textarea>
                    </div>
                </div>
                <div class="col-md-6 col-12 inputWrapper">
                    <div>
                        <label for="phone">phone</label>
                        <input type="text" name="phone" id="phone" class="editInput compareInp">
                    </div>
                </div>
                <div class="col-12 inputWrapper mb-4">
                    <label for="photo" class="flex-grow-0">photo</label>
                    <input type="file" name="newPhoto[]" class="border-0 rounded-0 d-none" id="newPhoto" accept="image/jpeg,image/jpg" multiple="multiple">
                    <div class="imgWrapper">
                        <label for="newPhoto" class="img addimageLabel previewLabel">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z" />
                            </svg>
                            <span>update images</span>
                        </label>
                        <!-- <img src="./uploads/Mailshot _ Candidate Mailshot Copy.jpg" class="img" alt=""> -->
                    </div>
                    <label class="imgError">
                        input jpeg files only
                    </label>
                </div>
            </div>
            <p id="last_updated" class="mt-2 last_updated"></p>
            <input type="submit" value="update" class="fBtn black float-end submitBtn">
        </form>
    </div>
    <div class="viewModal modal hide largeModal" data-dismiss="true">
        <div class="viewform animateModal form clearfix">
            <div class="border-bottom border-1 mb-2 pb-2">
                <h2>View info</h2>
                <button class="closeView fBtn closeBtn black" type="button" data-target-modal="viewModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                    </svg>
                </button>
            </div>
            <div class="row">
                <div class="col-md-6 col-12 inputWrapper">
                    <div>
                        <label for="name">Name</label>
                        <input type="text" id="v_name" class="nameInp" readonly>
                    </div>
                </div>
                <div class="col-md-6 col-12 inputWrapper">
                    <div>
                        <label for="email">Email</label>
                        <input type="email" id="v_email" readonly>
                    </div>
                </div>
                <div class="col-md-6 col-12 inputWrapper">
                    <div>
                        <label for="name">Address</label>
                        <textarea id="v_address" cols="30" rows="2" readonly></textarea>
                    </div>
                </div>
                <div class="col-md-6 col-12 inputWrapper">
                    <div>
                        <label for="phone">phone</label>
                        <input type="text" id="v_phone" readonly>
                    </div>
                </div>
                <div class="col-12 inputWrapper mb-4">
                    <label for="photo" class="flex-grow-0">photo</label>
                    <div class="imgWrapper">
                    </div>
                </div>
            </div>
            <p id="v_last_updated" class="mt-2 last_updated"></p>
        </div>
    </div>
    <div class="deleteModal modal hide smallModal" data-dismiss="false">
        <div class="deleteform form clearfix max-w-500">
            <h3>Are you sure you want to delete this item ?</h3>
            <div class="d-flex pt-2 justify-content-end">
                <button class="fBtn black cancelDelete" type="button">Cancel</button>
                <button class="fBtn red ms-2 confirmDelete" type="button" data-id="">Delete</button>
            </div>
        </div>
    </div>
    <div class="discardModal modal hide smallModal" data-dismiss="false">
        <div class="discardform form clearfix max-w-500">
            <h3>Are you sure you want to discard the changes ?</h3>
            <div class="d-flex pt-2 justify-content-end">
                <button class="fBtn black  closeBtn closeDiscard" type="button" data-target-modal="discardModal">Cancel</button>
                <button class="fBtn red ms-2 discardChanges" type="button">Discard</button>
            </div>
        </div>
    </div>
    <div class="container ">
        <div class="border-bottom border-1 d-flex align-items-center py-2
                ">
            <h1 class="title  flex-grow-1">List</h1>
            <a href="./index.php" class="fBtn black">Home</a>
        </div>
        <?php
        include 'db.php';
        $selectAll = "SELECT * FROM `reg_table`";
        $SELECTresult = mysqli_query($conn, $selectAll);
        ?>
        <?php
        if (mysqli_num_rows($SELECTresult) == 0) {
            echo "<h5 class='mt-4'>No items available</h5>";
        }
        ?>
        <div id="listItems">
            <ui class="listItems">
                <?php while ($row = mysqli_fetch_assoc($SELECTresult)) : ?>
                    <li class="listWrapper">
                        <h5 class="viewItem" data-id="<?php echo $row['id']; ?>"><?php echo $row['name'] ?></h5>
                        <?php if ($row['IsPermanent'] !== "on") { ?>
                            <!-- <button class="viewItem listActionBtn green" data-id="<?php echo $row['id']; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                </svg>
                            </button> -->
                            <button class="editItem listActionBtn green" data-id="<?php echo $row['id']; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                </svg>
                            </button>
                        <?php }  ?>
                        <button class="deleteItem listActionBtn red" data-id="<?php echo $row['id']; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                            </svg>
                        </button>
                    </li>
                <?php endwhile; ?>
            </ui>
        </div>
    </div>
    <!--  -->
    </div>
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/jquery.validate.min.js"></script>
    <script src="./js/pagination.min.js"></script>
    <script src="./js/custom.js"></script>
    <script src="./js/validation.js"></script>
    <script>
        $(document).ready(function() {
            var currentlist = $('.listItems').children()
            list = Object.values(currentlist)
            listN = list.slice(0, -2);
            if ($('.listItems').children().length > 0) {
                $('#listItems').pagination({
                    dataSource: listN,
                    pageSize: 8,
                    callback: function(data, pagination) {
                        $('.listItems').html(data)
                    }
                })
            }
        })
    </script>
</body>

</html>