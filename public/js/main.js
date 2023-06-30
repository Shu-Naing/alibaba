// const { start, end } = require("@popperjs/core");

// const { start } = require("@popperjs/core");

$("#table_id").DataTable({
  lengthChange: false, // Disable "Show entries"
});

$(document).ready(function () {
  let submenu = document.querySelectorAll(".submenuParent");
  submenu.forEach(function (element) {
    let submenuLi = element.querySelectorAll(
      ".sidebar-item .sidebar-link.active"
    );
    if (submenuLi.length > 0) {
      element.classList.add("show");
    }
  });

  $(".number").focusout(function () {
    let disPdID = $(this).attr("data-id");
    $.ajax({
      url: "/update-product-qty/" + disPdID,
      type: "GET",
      data: {
        qty: this.value,
      },
      success: function (response) {
        location.reload();
      },
    });
  });

  $(document).on("change", ".counterMachine", function () {
    if ($(this).val() === "1") {
      $(".counter").attr("disabled", false);
      $(".machine").attr("disabled", true);
    } else if ($(this).val() === "2") {
      $(".counter").attr("disabled", true);
      $(".machine").attr("disabled", false);
    }
  });

  // outletstockoverview
  $(document).on("change", ".outlet", function () {
    // console.log("hello");
    let outlet_id = $(this).val();
    console.log(outlet_id);
  });

  if ($(".counterMachine").find(":selected").val() == 1) {
    $(".counter").attr("disabled", false);
  } else if ($(".counterMachine").find(":selected").val() == 2) {
    $(".machine").attr("disabled", false);
  }
});

// product start
function addField() {
  var container = document.getElementById("formFieldsContainer");
  // Create a new group container
  var newGroup = document.createElement("div");
  newGroup.className =
    "form-group p-4 rounded border shadow-sm mb-5 d-flex flex-wrap gap-3 add_var justify-content-between";

  // Create input elements for name, email, and image
  var nameField = document.createElement("input");
  nameField.type = "text";
  nameField.name = "name[]";
  nameField.className = "form-control";
  nameField.placeholder = "Name";

  var emailField = document.createElement("input");
  emailField.type = "text";
  emailField.name = "email[]";
  emailField.className = "form-control";
  emailField.placeholder = "Email";

  var imageField = document.createElement("input");
  imageField.type = "file";
  imageField.name = "image[]";
  imageField.className = "form-control";
  imageField.accept = "image/*";

  // Create remove button
  var removeButton = document.createElement("button");
  removeButton.type = "button";
  removeButton.className = "btn btn-danger";
  removeButton.innerHTML = "Remove";
  removeButton.onclick = function () {
    removeField(newGroup);
  };

  // Append the input fields and remove button to the group container
  newGroup.appendChild(nameField);
  newGroup.appendChild(emailField);
  newGroup.appendChild(imageField);
  newGroup.appendChild(removeButton);

  // Append the new group container to the main container
  container.appendChild(newGroup);
}

function removeField(group) {
  var container = document.getElementById("formFieldsContainer");
  container.removeChild(group);
}
// product end

// function increaseValue() {
//   var value = parseInt(document.getElementById("number").value, 10);
//   value = isNaN(value) ? 0 : value;
//   value++;
//   document.getElementById("number").value = value;
// }

// function decreaseValue() {
//   var value = parseInt(document.getElementById("number").value, 10);
//   value = isNaN(value) ? 0 : value;
//   value < 1 ? (value = 1) : "";
//   value--;
//   document.getElementById("number").value = value;
// }

// function stepperDecrement(btn) {
//   const inputEl = btn.nextElementSibling;
//   const calcStep = inputEl.step * -1;
//   const newValue = parseInt(inputEl.value) + calcStep;
//   if (newValue >= inputEl.min && newValue <= inputEl.max) {
//     inputEl.value = newValue;
//   }
// }
// function stepperIncrement(btn) {
//   const inputEl = btn.previousElementSibling;
//   const calcStep = inputEl.step * 1;
//   const newValue = parseInt(inputEl.value) + calcStep;
//   if (newValue >= inputEl.min && newValue <= inputEl.max) {
//     inputEl.value = newValue;
//   }
// }

// start distribute product
function increaseValue(button, disPdID, variantID, variant_qty) {
  var input = button.parentNode.parentNode.querySelector(".number");
  var value = parseInt(input.value, 10);
  if (value < variant_qty) {
    input.value = isNaN(value) ? 0 : value + 1;
  } else {
    input.value = variant_qty;
  }
  // input.value = isNaN(value) ? 0 : value + 1;
  // console.log("distribute product id", disPdID);

  // console.log("variant_id", variantID);
  // console.log("qty", input.value);

  $.ajax({
    url: "/update-product-qty/" + disPdID + "/" + variantID,
    type: "GET",
    data: {
      qty: input.value,
    },
    beforeSend: function () {},
    success: function (response) {
      location.reload();
    },
    complete: function () {
      // Hide the loading indicator
      Notiflix.Loading.Remove();
    },
  });
}

function decreaseValue(button, disPdID, variantID) {
  var input = button.parentNode.parentNode.querySelector(".number");
  var value = parseInt(input.value, 10);
  input.value = isNaN(value) || value < 1 ? 0 : value - 1;
  // input.value = isNaN(value) || value < 1 ? 0 : value - 1;
  // console.log("decre", disPdID);

  $.ajax({
    url: "/update-product-qty/" + disPdID + "/" + variantID,
    type: "GET",
    data: {
      qty: input.value,
    },
    success: function (response) {
      location.reload();
    },
  });

  // ajax
}

function increaseOutletdisValue(button, disPdID, variantID, variant_qty) {
  var input = button.parentNode.parentNode.querySelector(".number");
  var value = parseInt(input.value, 10);

  if (value < variant_qty) {
    input.value = isNaN(value) ? 0 : value + 1;
  } else {
    input.value = variant_qty;
  }

  $.ajax({
    url: "/update-outdis-product-qty/" + disPdID + "/" + variantID,
    type: "GET",
    data: {
      qty: input.value,
    },
    success: function (response) {
      location.reload();
    },
  });
  // console.log("incre", disPdID);

  // console.log("input.value", input.value);
}

function decreaseOutletdisValue(button, disPdID, variantID) {
  var input = button.parentNode.parentNode.querySelector(".number");
  var value = parseInt(input.value, 10);
  input.value = isNaN(value) || value < 1 ? 0 : value - 1;
  console.log("decre", disPdID);

  $.ajax({
    url: "/update-outdis-product-qty/" + disPdID + "/" + variantID,
    type: "GET",
    data: {
      qty: input.value,
    },
    success: function (response) {
      location.reload();
    },
  });

  // ajax
}

// for distribute product
function deleteDisValue(disPdID) {
  // console.log(disPdID);
  $.ajax({
    url: "/delete-dis-product/" + disPdID,
    type: "GET",
    success: function (response) {
      location.reload();
    },
  });
}

// for outletdistribute product
function deleteOutDisValue(disPdID) {
  // console.log(disPdID);
  $.ajax({
    url: "/delete-outdis-product/" + disPdID,
    type: "GET",
    success: function (response) {
      location.reload();
    },
  });
}

$("#machine").on("change", function () {
  var machineId = $(this).val();
  // console.log(machineId);
  // Make an AJAX request to retrieve the item code data
  $.ajax({
    url: "outlet-machine-item", // Replace with the appropriate Laravel route
    method: "GET",
    data: {
      machineId: machineId,
    },
    success: function (response) {
      var itemCodeSelect = $("#item_code");
      itemCodeSelect.empty(); // Clear existing options

      if (response.itemCodes && response.itemCodes.length > 0) {
        $.each(response.itemCodes, function (index, itemCode) {
          itemCodeSelect.append(
            $("<option>", {
              value: itemCode,
              text: itemCode,
            })
          );
        });
      }
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
});

// function addOpeiningItemCode() {
//   var item_code = $("#item_code").find(":selected").val();

//   console.log("addOpeiningItemCode", item_codes);
//   $.ajax({
//     url: "/search-item-code",
//     type: "GET",
//     data: {
//       item_code: item_code,
//     },
//     success: function (response) {
//       let result = "";
//       // response.forEach((data) => {
//       //   result += `<div><a href='edit/${data.id}'>${data.product_name}</a></div>`;
//       // });
//       console.log(response);
//       $("#opening_items_div").html(response);
//       // $("#searchResults").append(result);
//     },
//   });
// }

// $(".number").focusout(() => {
//   console.log("val", $(this).val());
// });

// end

// var typingTimer;
// var doneTypingInterval = 500; // milliseconds

// $("#searchInput").on("input", function () {
//   clearTimeout(typingTimer);
//   typingTimer = setTimeout(doneTyping, doneTypingInterval);
// });

// $("#searchInput").focusout("input", function () {
//   var keyword = $("#searchInput").val();
//   var distributedId = $("#distributedId").val();
//   // console.log(distributedId);
//   if (keyword.length >= 3) {
//     // Perform the search only if keyword length is at least 3 characters
// $.ajax({
//       url: "/search",
//       type: "GET",
//       data: {
//         keyword: keyword,
//         distributed_id: distributedId,
//       },
//       success: function (response) {
//         let result = "";
//         // response.forEach((data) => {
//         //   result += `<div><a href='edit/${data.id}'>${data.product_name}</a></div>`;
//         // });
//         console.log(response);
//         $("#show_dsProduct").html(response);
//         // $("#searchResults").append(result);
//       },
//  });
//   }
// });

// hamburger menu
$(".hamburger").on("click", function () {
  $(".left-sidebar").toggleClass("sidebar-close");
  var hamburger = $(".left-sidebar").hasClass("sidebar-close");
  // hamburger.hasClass("sidebar-close");
  // console.log(hamburger);
  if (hamburger) {
    console.log("has");
    $(".body-wrapper").css("margin-left", "0px");
    $(".app-header").css("width", "100%");
  } else {
    $(".body-wrapper").css("margin-left", "270px");
    $(".app-header").css("width", "calc(100% - 270px)");
  }

  // $(".body-wrapper").css("margin-left", "0px");
});

$(".number-box").on("focusout", function () {
  var dataId = $(this).data("id");
  var disPdID = dataId[0];
  var variantID = dataId[1];
  var variant_qty = dataId[2];

  var inputvalue = parseInt($(this).val(), 10);
  if (inputvalue <= variant_qty) {
    $.ajax({
      url: "/update-product-qty/" + disPdID + "/" + variantID,
      type: "GET",
      data: {
        qty: inputvalue,
      },
      success: function (response) {
        // console.log(response);
        location.reload();
      },
    });
  } else {
    $(this).val(variant_qty);
    $.ajax({
      url: "/update-product-qty/" + disPdID + "/" + variantID,
      type: "GET",
      data: {
        qty: variant_qty,
      },
      success: function (response) {
        // console.log(response);
        location.reload();
      },
    });
  }
});

$(".outlet-number-box").on("focusout", function () {
  var dataId = $(this).data("id");
  var disPdID = dataId[0];
  var variantID = dataId[1];
  var variant_qty = dataId[2];

  var inputvalue = parseInt($(this).val(), 10);
  if (inputvalue <= variant_qty) {
    $.ajax({
      url: "/update-outdis-product-qty/" + disPdID + "/" + variantID,
      type: "GET",
      data: {
        qty: inputvalue,
      },
      success: function (response) {
        // console.log(response);
        location.reload();
      },
    });
  } else {
    $(this).val(variant_qty);
    $.ajax({
      url: "/update-outdis-product-qty/" + disPdID + "/" + variantID,
      type: "GET",
      data: {
        qty: variant_qty,
      },
      success: function (response) {
        // console.log(response);
        location.reload();
      },
    });
  }
});

// outlet stock histories start
$(".outletstockhistory-check").on("change", function () {
  var isChecked = $(this).is(":checked");
  var outletstockhistory_id = $(this).val();
  // console.log("Checkbox value:", isChecked);
  // console.log("Checkbox value:", outletstockhistory_id);

  $.ajax({
    url: "/checkoutletstockhistory/",
    type: "GET",
    data: {
      check: isChecked,
      id: outletstockhistory_id,
    },
    success: function (response) {
      console.log(response);
      // location.reload();
    },
  });

  // Perform additional actions based on the checkbox value
  // if (isChecked) {
  // Checkbox is checked
  // Perform some action
  // } else {
  // Checkbox is unchecked
  // Perform some other action
  //   }
});

// outlet stock histories end

// outlet stock overview reprot for check column start
$(".outletstockoverview-check").on("change", function () {
  var isChecked = $(this).is(":checked");
  var outletstockoverview_id = $(this).val();
  // console.log("Checkbox value:", isChecked);
  // console.log("Checkbox value:", outletstockoverview_id);

  $.ajax({
    url: "/checkoutletstockoverview/",
    type: "GET",
    data: {
      check: isChecked,
      id: outletstockoverview_id,
    },
    success: function (response) {
      console.log(response);
      // location.reload();
    },
  });
});
// outlet stock overview reprot for check column end

// outlet stock overview reprot for physical column start
$(".physical-qty").on("focusout", function () {
  var physical_qty = $(this).val();
  var balance_qty = $(".balance-qty").text();
  var outletstockoverview_id = $(this).data("id");
  // console.log("Checkbox value:", outletstockoverview_id);
  // console.log("Checkbox value:", physical_qty);
  // console.log("Checkbox value:", balance_qty);

  $.ajax({
    url: "/updatephysicalqty/",
    type: "GET",
    data: {
      id: outletstockoverview_id,
      physical_qty: physical_qty,
      balance_qty: balance_qty,
    },
    success: function (response) {
      // console.log(response);
      location.reload();
    },
  });
});

// outlet stock overview reprot for physical column end
