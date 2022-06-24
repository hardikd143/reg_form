$(document).ready(function () {
  function checkSpecialChar(str) {
    var speChar = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
    return speChar.test(str);
  }

  function checkNumbers(str) {
    var numbers = /[0-9]/;
    return numbers.test(str);
  }
  $(document).on("keydown", ".nameInp", function (e) {
    var hasNumbers = checkNumbers(e.key);
    var hasSpecialChar = checkSpecialChar(e.key);
    if (hasNumbers || hasSpecialChar) {
      return false;
    } else {
      return;
    }
  });
  $(document).on("click", ".addsubmitBtn", function () {
    $(".addForm").validate({
      rules: {
        name: "required",
        address: "required",
        email: {
          required: true,
          email: true,
        },
        phone: {
          required: true,
          minlength: 10,
          maxlength: 14,
          digits: true,
        },
      },
      messages: {
        name: "name is requied",
        address: "address is required",
        email: {
          required: "email is required",
          email: "enter valid email",
        },
        phone: {
          required: "mobile number is required",
          minlength: "length must be greater than 10",
          maxlength: "length must be greater than 14",
          digits: "enter number only",
        },
      },
    });
  });
  $(document).on("click", ".submitBtn", function () {
    $(".editform").validate({
      rules: {
        name: "required",
        address: "required",
        email: {
          required: true,
          email: true,
        },
        phone: {
          required: true,
          minlength: 10,
          maxlength: 14,
          digits: true,
        },
      },
      messages: {
        name: "name is requied",
        address: "address is required",
        email: {
          required: "email is required",
          email: "enter valid email",
        },
        phone: {
          required: "mobile number is required",
          minlength: "length must be greater than 10",
          maxlength: "length must be less than 14",
          digits: "enter number only",
        },
      },
    });
  });
});
