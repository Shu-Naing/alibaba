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
 
});

// outletdistribute create submit button
var outletdsButton = $("#outletDsbutton");

outletdsButton.on("click", function (event) {
  var dateInput = $("#date");
  var referenceInput = $("#reference");
  var fromOutletInput = $("#fromOutlet");
  var counterMachineInput = $("#counterMachine");
  var counterInput = $("#counter");
  var machineInput = $("#machine");
  var tableValue = $("#show_Product .outdstable tbody tr");
  var searchInput = $("#searchInput");
  var errorBox = $(".errorbox");

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
  var dateInput = $("#date");
  var referenceInput = $("#reference");
  var fromOutletInput = $("#fromOutlet");
  var toMachineInput = $("#to_machine");
  var storeCustomer = $("#store_customer");
  var tableValue = $("#show_Product .issuetable tbody tr");
  var searchInput = $("#searchInput");
  var errorBox = $(".errorbox");

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
  
  $.ajax({
    url: "/checkoutletstockhistory/",
    type: "GET",
    data: {
      check: isChecked,
      id: outletstockhistory_id,
    },
    success: function (response) {
      // location.reload();
    },
  });

});
// outlet stock histories end

// outlet level histories start
$(".outletlevelhistory-check").on("change", function () {
  var isChecked = $(this).is(":checked");
  var outletlevelhistory_id = $(this).val();
  
  $.ajax({
    url: "/checkoutletlevelhistory/",
    type: "GET",
    data: {
      check: isChecked,
      id: outletlevelhistory_id,
    },
    success: function (response) {
      // location.reload();
    },
  });
});
// outlet level histories end

// outlet stock overview reprot for check column start
$(".outletstockoverview-check").on("change", function () {
  var isChecked = $(this).is(":checked");
  var outletstockoverview_id = $(this).val();

  $.ajax({
    url: "/checkoutletstockoverview/",
    type: "GET",
    data: {
      check: isChecked,
      id: outletstockoverview_id,
    },
    success: function (response) {
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
      // location.reload();
    },
  });
});
// outlet level overview reprot for check column end

// outlet stock overview reprot for physical column start
$(".physical-qty").on("focusout", function () {
  var physical_qty = $(this).val();
  var balance_qty = $(this).parent().parent().find(".balance-qty").text();
   var outletstockoverview_id = $(this).data("id");

  $.ajax({
    url: "/updatephysicalqty/",
    type: "GET",
    data: {
      id: outletstockoverview_id,
      physical_qty: physical_qty,
      balance_qty: balance_qty,
    },
    success: function (response) {
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
    $("#odsopen_item_code").html('<option value="">Choose...</option>');
    $.each(response, function (key, value) {
      $("#odsopen_item_code").append(
        '<option value="' + key + '">' + value + "</option>"
      );
    });    
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
      event.submit();
    }
  });
});

$("#outlet-dropdown").on("change", function () {
  var idOutlet = this.value;
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

// distribute product details quantity input box edit start
$("#disprod_quantity").on("focusout", function () {
  var qty = $(this).val();
  var id = $(this).data("id");
  $.ajax({
    url: "/updatedistributeproductdetailqty/",
    type: "GET",
    data: {
      id,
      qty,
    },
    success: function (response) {
      location.reload();
    },
  });
});
// distribute product details quantity input box edit end

// purchase details conutry input box edit start
$("#purchase-detail-country").on("change", function () {
  var idCountry = this.value;
  var grn_no = $(this).data("id-grn");
  var received_date = $(this).data("id-received-date");

  $.ajax({
    url: "/purchasedetailcountry/",
    type: "GET",
    data: {
      idCountry,
      grn_no,
      received_date,
    },
    success: function (result) {
      console.log(result);
    },
  });
});
// purchase details conutry input box edit end

$(document).on("focusout", ".purchasedPrice, .purchaseQuantity", function () {
  var purchasedPrice = $(this).closest("tr").find(".purchasedPrice").val();
  var qty = $(this).closest("tr").find(".purchaseQuantity").val();
  var total = $(this).closest("tr").find(".purchaseTotal");

  var calcuTotal = purchasedPrice * qty;
  total.html(calcuTotal);
});

function generatedamagecode(outlet_id) {
  $.ajax({
    url: "/generatedamagecode",
    type: "GET",
    data: {
      outlet_id,
    },
    success: function (response) {
      $("#damage_no").val(response);
    },
    error: function () {
      console.log("you got error ");
    },
  });
}

$("#demage_outlet_id").change(function () {
  var selectedOption = $(this).find("option:selected");
  var outletName = selectedOption.text();
  generatedamagecode(outletName);
});

$(document).on("focusout", ".damageQuantity", function () {
  var damagePrice = $(this).closest("tr").find(".damagePrice").val();
  var qty = $(this).closest("tr").find(".damageQuantity").val();
  var total = $(this).closest("tr").find(".damageTotal");
  console.log(total);
  var calcuTotal = damagePrice * qty;
  total.html(calcuTotal);
  total.val(calcuTotal);
});

function generateadjcode(outlet_id) {
  $.ajax({
    url: "/generateadjcode",
    type: "GET",
    data: {
      outlet_id,
    },
    success: function (response) {
      $("#adj_no").val(response);
    },
    error: function () {
      console.log("you got error ");
    },
  });
}

$("#open_outlet_id").change(function () {
  var selectedOption = $(this).find("option:selected");
  var outletName = selectedOption.text();
  generateadjcode(outletName);
});

var deButton = $("#demagebutton");
deButton.on("click", function (event) {
  var dateInput = $("#date");
  var demageOutletId = $("#demage_outlet_id");
  var damageNo = $("#damage_no");
  var name = $("#name");
  var error = $("#error");
  var openDistination = $("#open_distination");
  var tableValue = $("#show_Product .detable tbody tr");
  var errorBox = $(".errorbox");
  if (
    dateInput.val() &&
    demageOutletId.val() &&
    damageNo.val() &&
    name.val() &&
    error.val() &&
    openDistination.val() &&
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
      errorBox.append("Date field is required.<br/>");
    }
    if (demageOutletId.val() === "") {
      // referenceInput.addClass("is-invalid");
      errorBox.append("Outlet field is required.<br/>");
    }
    if (damageNo.val() === "") {
      // fromOutletInput.addClass("is-invalid");
      errorBox.append("Damage No field is required.<br/>");
    }
    if (name.val() === "") {
      // toOutletInput.addClass("is-invalid");
      errorBox.append("Name field is required.<br/>");
    }
    
    if (error.val() === "") {
      // toOutletInput.addClass("is-invalid");
      errorBox.append("Error field is required.<br/>");
    }
    if (openDistination.val() === "") {
      // toOutletInput.addClass("is-invalid");
      errorBox.append("Distination field is required.<br/>");
    }
    if (tableValue.length === 0) {
      // searchInput.addClass("is-invalid");
      errorBox.append("Product item is required.<br/>");
    }
    $(window).scrollTop(0);
  }
});
