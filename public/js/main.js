// const { start, end } = require("@popperjs/core");

// const { start } = require("@popperjs/core");

$("#table_id").DataTable({
  lengthChange: false, // Disable "Show entries"
  // dom: '<"d-flex"<"form-select w-25"l><"ml-auto"f>>t',
  buttons: [
    {
      text: "My Button",
      action: function (e, dt, node, config) {
        alert("Button activated");
      },
    },
  ],
});

$("#table_lsdd").DataTable({
  lengthChange: false, // Disable "Show entries"
  searching: false,
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
function increaseValue(button, purchasedPrice, variant_qty) {
  var input = button.parentNode.parentNode.querySelector(".number");
  var value = parseInt(input.value, 10);
 
  if (value < variant_qty) {
    input.value = isNaN(value) ? 0 : value + 1;
    var subtotal = input.value * purchasedPrice;
    var priceTotal =
      button.parentNode.parentNode.parentNode.parentNode.parentNode.querySelector(
        ".subtotal"
      );

    priceTotal.textContent = subtotal;
  } else {
    input.value = variant_qty;

  }
  var total = calculateTotal();
  $("#total").html(total);
}

function decreaseValue(button, purchasedPrice, variant_qty) {
  var input = button.parentNode.parentNode.querySelector(".number");
  var value = parseInt(input.value, 10);
  input.value = isNaN(value) || value < 1 ? 0 : value;
  if (input.value > 0) {
    input.value = input.value - 1;
  }
  var subtotal = input.value * purchasedPrice;
  var priceTotal =
    button.parentNode.parentNode.parentNode.parentNode.parentNode.querySelector(
      ".subtotal"
    );

  priceTotal.textContent = subtotal;
  var total = calculateTotal();
  $("#total").html(total);
}

$(document).on("focusout", ".number-box", function () {
  var value = $(this).val();
  var dataId_arr = $(this).data("id");
  var purchasedPrice = dataId_arr[0];
  var variant_qty = dataId_arr[1];

  if (value < 0) {
    $(this).val(1);
    value = 1;
  } else if (value > variant_qty) {
    $(this).val(variant_qty);
    value = variant_qty;
  }
  var subtotal = value * purchasedPrice;
  $(this).closest("tr").find("td:eq(4)").text(subtotal);
  var total = calculateTotal();
  $("#total").html(total);
});

function calculateTotal() {
  var subtotal_arr = $(".subtotal");
  var total = 0;
  if (subtotal_arr.length > 0) {
    subtotal_arr.each(function () {
      var subtotal = parseInt($(this).text());
      total += subtotal; // Add subtotal to the sum
    });
  }

  return total;
}

var dateInput = $("#date");
var referenceInput = $("#reference");
var statusInput = $("#status");
var fromOutletInput = $("#fromOutlet");
var toOutletInput = $("#toOutlet");
var tableValue = $("#show_dsProduct table tbody tr");
var searchInput = $("#searchInput");
var errorBox = $(".errorbox");
// console.log(tableValue.html());

// Select submit button
var dsButton = $("#dsbutton");

dsButton.on("click", function (event) {
  // Reset previous validation feedback
  $(".is-invalid").removeClass("is-invalid");

  if (
    dateInput.val() &&
    referenceInput.val() &&
    statusInput.val() &&
    fromOutletInput.val() &&
    toOutletInput.val() &&
    tableValue.length > 0
  ) {
    $(this).submit();
  } else {
    event.preventDefault();

    if (errorBox.html() === "") {
      errorBox
        .append(
          "<strong>Whoops!</strong> There were some problems with your input.<br><br>"
        )
        .addClass("alert alert-danger");
    } else {
      errorBox.html("");
      errorBox
        .append(
          "<strong>Whoops!</strong> There were some problems with your input.<br><br>"
        )
        .addClass("alert alert-danger");
    }

    if (dateInput.val() === "") {
      // dateInput.addClass("is-invalid");
      errorBox.append("The date field is required.<br/>");
    }
    if (referenceInput.val() === "") {
      // referenceInput.addClass("is-invalid");
    }
    if (statusInput.val() === "") {
      // statusInput.addClass("is-invalid");
      errorBox.append("The status field is required.<br/>");
    }
    if (fromOutletInput.val() === "") {
      // fromOutletInput.addClass("is-invalid");
      errorBox.append("From outlet field is required.<br/>");
    }
    if (toOutletInput.val() === "") {
      // toOutletInput.addClass("is-invalid");
      errorBox.append("To outlet field is required.<br/>");
    }
    if (tableValue.length === 0) {
      // searchInput.addClass("is-invalid");
      errorBox.append("Product item is required.<br/>");
    }
  }

  // $(searchInput).focusout(function () {
  //   searchInput.removeClass("is-invalid");
  // });
});

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
  $("#deleteModal").modal("show");
  $(".confirmButton").on("click", function () {
    disPdID.parentNode.parentNode.remove();
    $("#deleteModal").modal("hide");
  });
  // var deletebutton = $(".deleteBox");
  // alert(deletebutton);
  // $.ajax({
  //   url: "/delete-dis-product/" + disPdID,
  //   type: "GET",
  //   success: function (response) {
  //     location.reload();
  //   },
  // });
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

// end

// var typingTimer;
// var doneTypingInterval = 500; // milliseconds

// $("#searchInput").on("input", function () {
//   clearTimeout(typingTimer);
//   typingTimer = setTimeout(doneTyping, doneTypingInterval);
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
