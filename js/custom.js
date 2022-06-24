$(document).ready(function () {
  $(document).on("click", ".removeimagesLabel", function () {
    $(".imgWrapper").find("img.img").remove();
    $(this).css("display", "none");
    $("#photo").val("");
    $(".imgError").hide();
    $('[type="submit"]').prop("disabled", false);
    $("#imagecount").text("0");
    $(".imgWrapper").removeClass("haveSelected");
  });

  var compareObj = {};
  const addCompareValues = (res) => {
    compareObj = {
      name: res.name,
      email: res.email,
      address: res.address,
      phone: res.phone,
    };
  };
  $(document).on("input", ".compareInp", function () {
    subObj = {
      name: $(".compareInp#name").val(),
      email: $(".compareInp#email").val(),
      address: $(".compareInp#address").val(),
      phone: $(".compareInp#phone").val(),
    };
    isDataEqual = JSON.stringify(subObj) === JSON.stringify(compareObj);
    if (!isDataEqual) {
      $(".editform").attr("data-changed", "true");
    } else {
      $(".editform").attr("data-changed", "false");
    }
  });
  // set data in edit form
  const setEditFormValues = (res) => {
    $(".editform").attr("data-changed", "false");
    $("#id").val(res.id);
    $("#name").val(res.name);
    $(".editform").attr("data-id", res.id);
    $("#email").val(res.email);
    $("#address").text(res.address);
    $("#phone").val(res.phone);
    if (res.last_updated != "0000-00-00 00:00:00") {
      var lastUpdate = `last updated : <span> ${res.last_updated}</span>`;
      $("#last_updated").html(lastUpdate);
    }
    addCompareValues(res);
  };
  // get info for edit form
  $(document).on("click", ".editItem", function (e) {
    var id = $(this).data("id");
    // ajax
    $.ajax({
      type: "POST",
      url: "getedit.php",
      data: {
        id: id,
      },
      dataType: "json",
      success: function (res) {
        setEditFormValues(res);
        $(".imgWrapper").find("img.img").remove();
        var str = "";
        let photoArr = res.photo;
        photoArr = photoArr.split(",");
        if (res.photo) {
          photoArr.forEach((ele) => {
            if (ele.charAt(0) === " ") {
              ele = ele.substring(1);
            }
            str += `<img src="./uploads/${ele}" class="img" alt="img" data-img="${ele}"></img>`;
          });
          $(".imgWrapper").append(str);
        }
        // show editmodal
        $(".editModal").removeClass("hide");
      },
    });
  });
  // set data in view form
  const setViewFormValues = (res) => {
    $("#v_id").val(res.id);
    $("#v_name").val(res.name);
    $("#v_email").val(res.email);
    $("#v_address").text(res.address);
    $("#v_phone").val(res.phone);
    if (res.last_updated != "0000-00-00 00:00:00") {
      var lastUpdate = `last updated : <span> ${res.last_updated}</span>`;
      $("#v_last_updated").html(lastUpdate);
    }
  };
  // view modal
  $(document).on("click", ".viewItem", function (e) {
    var id = $(this).data("id");
    // console.log(id);
    // ajax
    $.ajax({
      type: "POST",
      url: "getedit.php",
      data: {
        id: id,
      },
      dataType: "json",
      success: function (res) {
        // console.log(res.photo);
        setViewFormValues(res);
        var str = "";
        let photoArr = res.photo;
        photoArr = photoArr.split(",");
        if (res.photo) {
          photoArr.forEach((ele) => {
            if (ele.charAt(0) === " ") {
              ele = ele.substring(1);
            }
            str += `<img src="./uploads/${ele}" class="img" alt="img" data-img="${ele}"></img>`;
          });
          $(".viewModal .imgWrapper").html(str);
        }
        // show editmodal
        $(".viewModal").removeClass("hide");
      },
    });
  });
  // update PreviewImages on input file change
  const updatePreviewImages = (imgArr) => {
    var str = "";
    $(".imgWrapper").find("img.img").remove();
    imgArr.forEach((ele, index) => {
      str += `<img src="${URL.createObjectURL(ele)}" class="img" alt="img">`;
    });
    return str;
  };
  // file validation function
  const validateFile = (filesArr) => {
    let sizeFiles = filesArr.filter((ele) => ele.size > 1000000);
    let exteFiles = filesArr.filter(
      (ele) => ele.type != "image/jpeg" && ele.type != "image/png"
    );
    // array of files which have not jpeg and png extension
    let exLen = exteFiles.length;
    // array of files which have more size than 25kb
    let szLen = sizeFiles.length;
    if (exLen > 0 || szLen > 0) {
      if (szLen > 0 && exLen > 0) {
        $(".imgError").text(
          "image type must be jpeg or size must be less than 25kb"
        );
      } else if (szLen > 0) {
        $(".imgError").text("image size must be less than 100kb");
      } else {
        $(".imgError").text("image extension is not valid");
      }
      $(".imgError").show();
      $('[type="submit"]').prop("disabled", true);
    } else {
      $(".imgError").hide();
      $('[type="submit"]').prop("disabled", false);
    }
  };
  const updateFiles = (selectedFiles) => {
    console.log(selectedFiles);
    var fd = new FormData();
    fd.append('file',selectedFiles)
    console.log(fd);
  };
  // update preview and validate files in registration form
  $(document).on("change", "#photo", function (e) {
    let selectedFiles = e.target.files;
    updateFiles(selectedFiles)
    selectedFiles = Object.values(selectedFiles);
    var imgs = updatePreviewImages(selectedFiles);
    $(imgs).insertBefore($(".removeimagesLabel"));
    isSelectedImage = $(".imgWrapper img.img");
    if (isSelectedImage) {
      $(".removeimagesLabel").css("display", "flex");
    }
    return;
    validateFile(selectedFiles);
    // show number of selected images if any image is selected
    if (selectedFiles.length > 0) {
      $(".imgWrapper").addClass("haveSelected");
    } else {
      $(".imgWrapper").removeClass("haveSelected");
    }
    $("#imagecount").text(selectedFiles.length);
  });
  // update preview and validate files in edit form
  $(document).on("change", "#newPhoto", function (e) {
    let selectedFiles = e.target.files;
    selectedFiles = Object.values(selectedFiles);
    var imgs = updatePreviewImages(selectedFiles);
    $(".imgWrapper").append(imgs);
    validateFile(selectedFiles);
    // bytes to kb
  });
  // open delete modal
  $(document).on("click", ".deleteItem", function (e) {
    var id = $(this).data("id");
    $(".confirmDelete").attr("data-id", id);
    $(".deleteModal").removeClass("hide");
  });
  // cancel confirm delete modal
  $(document).on("click", ".cancelDelete", function () {
    $(".deleteModal").addClass("hide");
  });
  // delete item
  $(document).on("click", ".confirmDelete", function () {
    var id = $(this).data("id");
    $.ajax({
      type: "POST",
      url: "delete.php",
      data: {
        id: id,
      },
      dataType: "json",
      success: function (res) {
        // console.log(res);
        window.location.reload();
      },
    });
  });
  // check if data was changed in edit form
  const isEditFormChanged = () => {
    isChanged = $(".editform").attr("data-changed");
    if (isChanged === "true") {
      $(".discardModal").removeClass("hide");
      // $(".editform").attr("data-changed", "false");
    } else {
      $(".editModal").addClass("hide");
    }
  };
  // hide modal on clicking outside the modal-dialog
  $(document).on("click", '[data-dismiss="true"]', function (e) {
    if (e.target == $(this)[0]) {
      if ($(e.target).hasClass("editModal")) {
        isEditFormChanged();
      } else {
        $(this).addClass("hide");
      }
    }
  });
  $(document).on("click", '[data-dismiss="false"]', function (e) {
    if ($(e.target).hasClass("modal")) {
      $(this).find(".form").addClass("shake");
      setTimeout(() => {
        $(this).find(".form").removeClass("shake");
      }, 400);
    }
  });
  // close modal button
  $(document).on("click", ".closeBtn", function () {
    var targetModal = $(`.${$(this).attr("data-target-modal")}`);
    // if modal is edit then it will check for changes
    if ($(this).attr("data-form")) {
      isEditFormChanged();
    } else {
      $(targetModal).addClass("hide");
    }
  });
  // hide discardmodal and edit modal
  $(document).on("click", ".discardChanges", function () {
    $(".editModal").addClass("hide");
    $(".discardModal").addClass("hide");
  });
});
