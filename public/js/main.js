$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

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
      $(".machine").val("");
    } else if ($(this).val() === "2") {
      $(".machine").attr("disabled", false);
      $(".counter").attr("disabled", true);
      $(".counter").val("");
    }
  });

  // outletstockoverview
  $(document).on("change", ".outlet", function () {
    // console.log("hello");
    let outlet_id = $(this).val();
    console.log(outlet_id);
  });

  // if ($(".counterMachine").find(":selected").val() == 1) {
  //   $(".counter").attr("disabled", false);
  // } else if ($(".counterMachine").find(":selected").val() == 2) {
  //   $(".machine").attr("disabled", false);
  // }
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

    priceTotal.textContent = subtotal.toLocaleString();
  } else {
    input.value = variant_qty;
  }
  var total = calculateTotal();
  var numberformattotal = total.toLocaleString();
  $("#total").html(numberformattotal);
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

  priceTotal.textContent = subtotal.toLocaleString();
  var total = calculateTotal();
  var numberformattotal = total.toLocaleString();
  $("#total").html(numberformattotal);
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
      // var subtotal = parseInt();
      var subtotal = parseFloat($(this).text().replace(/,/g, ""));
      total += subtotal; // Add subtotal to the sum
    });
  }

  return total;
}

// console.log(tableValue.html());

// distribute create submit button
var dsButton = $("#dsbutton");

dsButton.on("click", function (event) {
  // Reset previous validation feedback
  // $(".is-invalid").removeClass("is-invalid");
  var dateInput = $("#date");
  var referenceInput = $("#reference");
  var fromOutletInput = $("#fromOutlet");
  var toOutletInput = $("#toOutlet");
  var tableValue = $("#show_Product .dstable tbody tr");
  var searchInput = $("#searchInput");
  var errorBox = $(".errorbox");

  if (
    dateInput.val() &&
    referenceInput.val() &&
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
    $(window).scrollTop(0);
  }
  // $(searchInput).focusout(function () {
  //   searchInput.removeClass("is-invalid");
  // });
});

// outletdistribute create submit button
var outletdsButton = $("#outletDsbutton");

outletdsButton.on("click", function (event) {
  // event.preventDefault();
  // console.log("hello");
  // Reset previous validation feedback
  // $(".is-invalid").removeClass("is-invalid");
  var dateInput = $("#date");
  var referenceInput = $("#reference");
  var fromOutletInput = $("#fromOutlet");
  var counterMachineInput = $("#counterMachine");
  var counterInput = $("#counter");
  var machineInput = $("#machine");
  var tableValue = $("#show_Product .outdstable tbody tr");
  var searchInput = $("#searchInput");
  var errorBox = $(".errorbox");
  // console.log(dateInput.val(), "date");
  // console.log(referenceInput.val(), "ref");
  // console.log(fromOutletInput.val(), "from");
  // console.log(counterMachineInput.val(), "CM");
  // console.log(counterInput.val(), "counter");
  // console.log(machineInput.val(), "machine");
  // console.log(tableValue.length, "table value");
  // if (counterMachineInput.val() && (counterInput.val() || machineInput.val())) {
  //   alert("containe cm and c or m");
  // } else {
  //   if (counterMachineInput.val() === "1") {
  //     if (counterInput.val() === "") {
  //       alert("c false");
  //     }
  //   } else if (counterMachineInput.val() === "2") {
  //     if (machineInput.val() === "") {
  //       alert("m false");
  //     }
  //   } else {
  //     alert("cm false");
  //   }
  // }

  if (
    dateInput.val() &&
    referenceInput.val() &&
    fromOutletInput.val() &&
    counterMachineInput.val() &&
    (counterInput.val() || machineInput.val()) &&
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
    if (fromOutletInput.val() === "") {
      // fromOutletInput.addClass("is-invalid");
      errorBox.append("From outlet field is required.<br/>");
    }
    if (counterMachineInput.val() === "1") {
      if (counterInput.val() === "") {
        // alert("c false");
        errorBox.append("Counter field is required.<br/>");
      }
    } else if (counterMachineInput.val() === "2") {
      if (machineInput.val() === "") {
        // alert("m false");
        errorBox.append("Machine field is required.<br/>");
      }
    } else {
      // alert("cm false");
      errorBox.append("To (Counter/Machine) field is required.<br/>");
    }
    if (tableValue.length === 0) {
      // searchInput.addClass("is-invalid");
      errorBox.append("Product item is required.<br/>");
    }
    $(window).scrollTop(0);
  }
  // $(searchInput).focusout(function () {
  //   searchInput.removeClass("is-invalid");
  // });
});

var purchasebutton = $("#purchasebutton");

purchasebutton.on("click", function (event) {
  var grn_no = $("#grn_no");
  var received_date = $("#received_date");
  var country = $("#country");
  var tableValue = $("#show_Product .purchase_itemTable tbody tr");
  var errorBox = $(".errorbox");

  if (
    grn_no.val() &&
    received_date.val() &&
    country.val() &&
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

    if (grn_no.val() === "") {
      errorBox.append("GRN number is required.<br/>");
    }
    if (received_date.val() === "") {
      errorBox.append("Received Date is required.<br/>");
    }
    if (country.val() === "") {
      errorBox.append("Country is required.<br/>");
    }
    if (tableValue.length === 0) {
      errorBox.append("Product item is required.<br/>");
    }
    $(window).scrollTop(0);
  }
});

// issue create submit button
var issueButton = $("#issuebutton");
issueButton.on("click", function (event) {
  // event.preventDefault();
  // console.log("hello");
  var dateInput = $("#date");
  var referenceInput = $("#reference");
  // var statusInput = $("#status");
  var fromOutletInput = $("#fromOutlet");
  var toMachineInput = $("#to_machine");
  var storeCustomer = $("#store_customer");
  var tableValue = $("#show_Product .issuetable tbody tr");
  var searchInput = $("#searchInput");
  var errorBox = $(".errorbox");
  // console.log(dateInput.val(), "date");
  // console.log(referenceInput.val(), "ref");
  // console.log(statusInput.val(), "status");
  // console.log(fromOutletInput.val(), "from");
  // console.log(toMachineInput.val(), "tomachine");
  // console.log(storeCustomer.val(), "store customer");
  // console.log(tableValue.length, "table value");

  if (
    dateInput.val() &&
    referenceInput.val() &&
    // statusInput.val() &&
    fromOutletInput.val() &&
    toMachineInput.val() &&
    storeCustomer.val() &&
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
    // if (statusInput.val() === "") {
    //   // statusInput.addClass("is-invalid");
    //   errorBox.append("The status field is required.<br/>");
    // }
    if (fromOutletInput.val() === "") {
      // fromOutletInput.addClass("is-invalid");
      errorBox.append("From outlet field is required.<br/>");
    }
    if (toMachineInput.val() === "") {
      errorBox.append("From Machine field is required.<br/>");
    }
    if (storeCustomer.val() === "") {
      errorBox.append("To Store Customer field is required.<br/>");
    }
    if (tableValue.length === 0) {
      // searchInput.addClass("is-invalid");
      errorBox.append("Product item is required.<br/>");
    }
    $(window).scrollTop(0);
  }
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
    var total = calculateTotal();
    $("#total").html(total);
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

// $("#machine").on("change", function () {
//   var machineId = $(this).val();
//   // console.log(machineId);
//   // Make an AJAX request to retrieve the item code data
//   $.ajax({
//     url: "outlet-machine-item", // Replace with the appropriate Laravel route
//     method: "GET",
//     data: {
//       machineId: machineId,
//     },
//     success: function (response) {
//       var itemCodeSelect = $("#item_code");
//       itemCodeSelect.empty(); // Clear existing options

//       if (response.itemCodes && response.itemCodes.length > 0) {
//         $.each(response.itemCodes, function (index, itemCode) {
//           itemCodeSelect.append(
//             $("<option>", {
//               value: itemCode,
//               text: itemCode,
//             })
//           );
//         });
//       }
//     },
//     error: function (xhr, status, error) {
//       console.error(error);
//     },
//   });
// });

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

// outlet level histories start
$(".outletlevelhistory-check").on("change", function () {
  // console.log("hello");
  var isChecked = $(this).is(":checked");
  var outletlevelhistory_id = $(this).val();
  // console.log(isChecked, outletlevelhistory_id);

  $.ajax({
    url: "/checkoutletlevelhistory/",
    type: "GET",
    data: {
      check: isChecked,
      id: outletlevelhistory_id,
    },
    success: function (response) {
      // console.log(response);
      // location.reload();
    },
  });
});
// outlet level histories end

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

// outlet level overview reprot for check column start
$(".outletleveloverview-check").on("change", function () {
  var isChecked = $(this).is(":checked");
  var outletleveloverview_id = $(this).val();

  $.ajax({
    url: "/checkoutletleveloverview/",
    type: "GET",
    data: {
      check: isChecked,
      id: outletleveloverview_id,
    },
    success: function (response) {
      console.log(response);
      // location.reload();
    },
  });
});
// outlet level overview reprot for check column end

// outlet stock overview reprot for physical column start
$(".physical-qty").on("focusout", function () {
  var physical_qty = $(this).val();
  var balance_qty = $(this).parent().parent().find(".balance-qty").text();
  // console.log(balance_qty);
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
// outlet level overview reprot for physical column end

$(".outlevel-physical-qty").on("focusout", function () {
  var physical_qty = $(this).val();
  var balance_qty = $(this)
    .parent()
    .parent()
    .find(".outlevel-balance-qty")
    .text();
  var outletleveloverview_id = $(this).data("id");
  $.ajax({
    url: "/updateoutletlevelphysicalqty/",
    type: "GET",
    data: {
      id: outletleveloverview_id,
      physical_qty: physical_qty,
      balance_qty: balance_qty,
    },
    success: function (response) {
      // console.log(response);
      location.reload();
    },
  });
});
// outlet level overview reprot for physical column end

// console.log("hello");
var outlet_id = $("#outlet_id").val();
$.ajax({
  url: "/getoutletItem/",
  type: "GET",
  data: {
    outlet_id,
  },
  success: function (response) {
    // console.log(response);
    $("#odsopen_item_code").html('<option value="">Choose...</option>');
    $.each(response, function (key, value) {
      $("#odsopen_item_code").append(
        '<option value="' + key + '">' + value + "</option>"
      );
    });
    // location.reload();
  },
});

$("#open_outlet_id").on("change", function () {
  var outlet_id = $(this).val();
  $.ajax({
    url: "/getoutletItem/",
    type: "GET",
    data: {
      outlet_id,
    },
    success: function (response) {
      $("#out_open_item_code").html('<option value="">Choose...</option>');
      $.each(response, function (key, value) {
        $("#out_open_item_code").append(
          '<option value="' + key + '">' + value + "</option>"
        );
      });
    },
  });
});

function deleteModalBox(deleteUrl, id) {
  // console.log(deleteUrl, id);
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: deleteUrl + "/" + id,
        type: "DELETE",
        success: function (response) {},
      });
    }
    location.reload();
  });
}

// $(".productcreatebtn").on("keyup", function (event) {
//   if (event.key === "Enter") {
//     // console.log("hello");
//     event.preventDefault();
//   }
// });

document.addEventListener("DOMContentLoaded", function () {
  // Get the button element
  const productCreateBtn = document.querySelector(".productcreatebtn");

  // Add event listener to the button
  productCreateBtn.addEventListener("click", function (event) {
    // Check if the button was clicked
    if (event.detail === 0) {
      // If the button was not clicked (e.g., triggered programmatically), do preventDefault()
      event.preventDefault();
    } else {
      // If the button was clicked, do the submission or any other desired action
      // For example, you can submit a form or call a function that handles the submission.
      // Replace the following line with your desired action:
      event.submit();
    }
  });
});

$("#outlet-dropdown").on("change", function () {
  var idOutlet = this.value;
  // console.log(idOutlet);
  // $("#machine-id").html("");
  $.ajax({
    url: "/get-machine/",
    type: "GET",
    data: {
      id: idOutlet,
    },
    success: function (result) {
      $("#machine-dropdown").html('<option value="">Choose...</option>');
      $.each(result, function (key, value) {
        $("#machine-dropdown").append(
          '<option value="' + value.id + '">' + value.name + "</option>"
        );
      });
    },
  });
});

document.addEventListener("DOMContentLoaded", function () {
  var idOutlet = $("#outlet-dropdown").val();
  $.ajax({
    url: "/get-machine/",
    type: "GET",
    data: {
      id: idOutlet,
    },
    success: function (result) {
      $("#machine-dropdown").html('<option value="">Choose...</option>');
      $.each(result, function (key, value) {
        $("#machine-dropdown").append(
          '<option value="' + value.id + '">' + value.name + "</option>"
        );
      });
    },
  });
});
