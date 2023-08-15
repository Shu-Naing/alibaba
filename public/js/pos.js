$(function () {
  $("#barcode-icon").on("click", function () {
    const urlParams = new URLSearchParams(window.location.search);
    var hasPrarms = urlParams.has("filter");
    // console.log("haspra",urlParams.has('filter'));
    if (hasPrarms) {
      $(this).toggleClass("barcode-active");
      var isActive = $(this).hasClass("barcode-active");
      localStorage.setItem("activeState", isActive);
    } else {
      // alert("Choose Payment Type First !");
      var warningMessage = `
              <div class="d-flex justify-content-between">
                Warning: Choose Payment Type First !
                <div class="btn-alert-box"><i class="bi bi-x-lg"></i></div>
              </div>
          `;
      $(".errorbox")
        .empty() // Clear previous content, if any
        .append(warningMessage)
        .addClass("alert alert-warning alert-dismissible fade show");
    }
  });

  $(document).on("click", ".btn-alert-box", function () {
    $(this)
      .closest(".alert")
      .removeClass("alert alert-warning alert-dismissible fade show");
    $(this).parent().remove();
  });

  window.addEventListener("load", function () {
    var activeState = localStorage.getItem("activeState");
    const urlParams = new URLSearchParams(window.location.search);
    var hasPrarms = urlParams.has("filter");

    if (activeState === "true") {
      if (hasPrarms) {
        $("#barcode-icon").addClass("barcode-active");
      } else {
        alert("Choose Payment Type First !");
      }
    }
  });

  function addTempItem(variation_id, payment_type, barcode) {
    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });

    $.ajax({
      type: "POST",
      url: "/pos-item/add",
      data: {
        payment_type: payment_type,
        variation_id: variation_id,
        barcode: barcode,
        _token: $('meta[name="csrf-token"]').attr("content"),
      },
      success: function (response) {
        if (response.status === "fail") {
          alert(response.message);
        }

        if (response.status === "success") {
          location.href = location.href;
        }
      },
      error: function (xhr, status, error) {
        alert("An error occurred while adding the product to cart");
        console.log(xhr.responseText);
      },
    });
  }

  $(document).on("click", ".add-pos-btn", function (e) {
    e.preventDefault();
    var variation_id = $(this).data("variation-id");
    var payment_type = $(this).data("payment-type");
    addTempItem(variation_id, payment_type, null);
  });

  var barcode = "";
  var interval;

  // check the barcode icon is active or not when do scan
  $(document).on("keydown", function (evt) {
    var isActive = $("#barcode-icon").hasClass("barcode-active");
    var isSearchInput = $(evt.target).is("#searchInput");
    var isQuantityInput = $(evt.target).is("#quantityInput");
    const urlParams = new URLSearchParams(window.location.search);
    const payment_type = urlParams.get("filter");
    // console.log('serarch parmaeter',filterValue);

    if (interval) clearInterval(interval);
    if (evt.code == "Enter") {
      if (isActive) {
        if (barcode) handleBarcode(barcode, payment_type);
        barcode = "";
        return;
      } else {
        if (!isSearchInput && !isQuantityInput) {
          alert("Please Select Barcode First!");
        }
      }
    }
    if (evt.code != "Shift") barcode += evt.key;
    interval = setInterval(function () {
      barcode = "";
    }, 20);
  });

  function handleBarcode(scanned_barcode, payment_type) {
    addTempItem(null, payment_type, scanned_barcode);
  }

  $("#searchInput").on("keydown", function (event) {
    // console.log(event.key);
    if (event.key === "Enter") {
      event.preventDefault();
      var searchValue = $("#searchInput").val();
      var currentUrl = window.location.href;
      console.log(currentUrl);
      var newUrl;

      // Check if the 'key' parameter already exists in the URL
      if (currentUrl.includes("key=")) {
        // If 'key' parameter exists, replace its value
        newUrl = currentUrl.replace(/key=.*/, "key=" + searchValue);
      } else {
        // If 'key' parameter doesn't exist, append it to the URL
        newUrl = currentUrl + "&key=" + searchValue;
      }

      window.location.href = newUrl;
    }
  });
});
