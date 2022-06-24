        <!DOCTYPE html>
        <?php
        include './db.php';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name       = $_POST['name'];
            $email      = $_POST['email'];
            $address    = $_POST['address'];
            $phone      = $_POST['phone'];
            $photo      = $_FILES['photo']['name'];
            $IsPermanent = "";
            echo "<pre>";
            print_r($_FILES);
            echo "</pre>";
            exit;
            $t = time();
            if ($_POST['IsPermanent']) {
                $IsPermanent = $_POST['IsPermanent'];
            } else {
                $IsPermanent = "off";
            }
            $imagesArr = [];
            foreach ($_FILES['photo']['name'] as $key => $iNAme) {
                $imgNewName = $t . "_" . $iNAme;
                echo $imgNewName;
                array_push($imagesArr, $imgNewName);
            }
            foreach ($_FILES['photo']['tmp_name'] as $key => $tempName) {
                $ele = $imagesArr[$key];
                move_uploaded_file($tempName, 'uploads/' . $ele);
            }
            $photo = implode(',', $imagesArr);
            $insertItem = "INSERT INTO reg_table (`name`, `email`, `address`, `phone`, `photo`, `IsPermanent`) VALUES ( '$name', '$email', '$address', $phone, '$photo','$IsPermanent')";
            $INSresult  = mysqli_query($conn, $insertItem);
            if ($INSresult) {
                $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                header('location: ' . $_SERVER['PHP_SELF']);
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
        ?>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Registration Form</title>
            <link rel="stylesheet" href="./css/bootstrap.min.css">
            <link rel="stylesheet" href="./css/bootstrap-icons.css">
            <link rel="stylesheet" href="./css/style.css">
        </head>

        <body>
            <div class="container main">
                <div>
                    <form name="myform" class="addForm form clearfix" method="POST" action="index.php" enctype="multipart/form-data">
                        <div class="border-bottom border-1 d-flex align-items-center py-2 mb-4">
                            <h1 class="title  flex-grow-1">Registration Form</h1>
                            <a href="./list.php" class="fBtn black">See List</a>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12 inputWrapper valid">
                                <div>
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="_name">
                                </div>
                            </div>
                            <div class="col-md-6 col-12 inputWrapper valid">
                                <div>
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="_email">
                                </div>
                            </div>
                            <div class="col-md-6 col-12 inputWrapper valid">
                                <div>
                                    <label for="name">Address</label>
                                    <textarea name="address" id="_address" cols="30" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 col-12 inputWrapper valid">
                                <div>
                                    <label for="phone">phone</label>
                                    <input type="text" name="phone" id="_phone">
                                </div>
                            </div>
                            <div class="col-12 inputWrapper d-flex">
                                <label for="photo" class="flex-grow-0" style="width:100px;">photo</label>
                                <!-- <input type="file" name="photo" class="border-0 rounded-0" id="photo" accept="image/png, image/jpeg,image/jpg" required multiple> -->
                                <input type="file" name="photo[]" class="border-0 rounded-0 d-none" id="photo" accept="image/jpeg,image/jpg" multiple="multiple">
                                <div class="imgWrapper">
                                    <div class="selectedCount"><span id="imagecount">0</span>&nbsp; selected</div>
                                    <label for="photo" class="img addimageLabel previewLabel">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                                            <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                            <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z" />
                                        </svg>
                                        <span>add image</span>
                                    </label>
                                    <!-- <img src="./uploads/beach-7262493__340.jpg" class="img" alt="img"> -->
                                    <!-- <img src="./uploads/bird-7053753__340.jpg" class="img" alt="img"> -->
                                    <div class="img removeimagesLabel previewLabel">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                        <span>remove images</span>
                                    </div>
                                </div>
                                <label class="imgError">
                                    some of the images you've selected is not valid
                                </label>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="IsPermanent">
                                    <input type="checkbox" name="IsPermanent" id="IsPermanent" class="position-absolute ">
                                    <span class="customCheckbox">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z" />
                                        </svg>
                                    </span>
                                    <span>save permanently</span>
                                </label>
                            </div>
                        </div>
                        <input type="submit" value="submit" class="fBtn black float-end addsubmitBtn">
                    </form>
                </div>
            </div>
            <script src="./js/jquery-3.6.0.min.js"></script>
            <script src="./js/jquery.validate.min.js"></script>
            <script src="./js/custom.js"></script>
            <script src="./js/validation.js"></script>
        </body>

        </html>