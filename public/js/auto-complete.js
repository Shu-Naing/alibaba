function autocompletewithdata(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function (e) {
    var a,
      b,
      i,
      val = this.value;
    /*close any already open lists of autocompleted values*/
    closeAllLists();
    if (!val) {
      return false;
    }
    currentFocus = -1;
    /*create a DIV element that will contain the items (values):*/
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");
    /*append the DIV element as a child of the autocomplete container:*/
    this.parentNode.appendChild(a);
    /*for each item in the array...*/
    for (i = 0; i < arr.length; i++) {
      /*check if the item starts with the same letters as the text field value:*/
      if (
        arr[i].title.substr(0, val.length).toUpperCase() == val.toUpperCase()
      ) {
        /*create a DIV element for each matching element:*/
        b = document.createElement("DIV");
        /*make the matching letters bold:*/
        b.innerHTML =
          "<strong>" + arr[i].title.substr(0, val.length) + "</strong>";
        b.innerHTML += arr[i].title.substr(val.length);
        /*insert a input field that will hold the current array item's value:*/
        b.innerHTML += "<input type='hidden' value='" + arr[i].title + "'>";
        /*execute a function when someone clicks on the item value (DIV element):*/
        b.addEventListener("click", function (e) {
          /*insert the value for the autocomplete text field:*/
          inp.value = this.getElementsByTagName("input")[0].value;
          /*close the list of autocompleted values,
          (or any other open lists of autocompleted values:*/
          closeAllLists();
          $("#item_code").focus();
        });
        a.appendChild(b);
      }
    }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function (e) {
    var x = document.getElementById(this.id + "autocomplete-list");
    if (x) x = x.getElementsByTagName("div");
    if (e.keyCode == 40) {
      /*If the arrow DOWN key is pressed,
      increase the currentFocus variable:*/
      currentFocus++;
      /*and and make the current item more visible:*/
      addActive(x);
    } else if (e.keyCode == 38) {
      //up
      /*If the arrow UP key is pressed,
      decrease the currentFocus variable:*/
      currentFocus--;
      /*and and make the current item more visible:*/
      addActive(x);
    } else if (e.keyCode == 13) {
      /*If the ENTER key is pressed, prevent the form from being submitted,*/
      e.preventDefault();
      if (currentFocus > -1) {
        /*and simulate a click on the "active" item:*/
        if (x) x[currentFocus].click();
      }
    }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = x.length - 1;
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
    closeAllLists(e.target);
  });
}

function autocompleteorg(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function (e) {
    var a,
      b,
      i,
      val = this.value;
    /*close any already open lists of autocompleted values*/
    closeAllLists();
    if (!val) {
      return false;
    }
    currentFocus = -1;
    /*create a DIV element that will contain the items (values):*/
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");
    /*append the DIV element as a child of the autocomplete container:*/
    this.parentNode.appendChild(a);
    /*for each item in the array...*/
    for (i = 0; i < arr.length; i++) {
      /*check if the item starts with the same letters as the text field value:*/
      if (
        arr[i].title.substr(0, val.length).toUpperCase() == val.toUpperCase()
      ) {
        /*create a DIV element for each matching element:*/
        b = document.createElement("DIV");
        /*make the matching letters bold:*/
        b.innerHTML =
          "<strong>" + arr[i].title.substr(0, val.length) + "</strong>";
        b.innerHTML += arr[i].title.substr(val.length);
        /*insert a input field that will hold the current array item's value:*/
        b.innerHTML += "<input type='hidden' value='" + arr[i].title + "'>";
        /*execute a function when someone clicks on the item value (DIV element):*/
        b.addEventListener("click", function (e) {
          /*insert the value for the autocomplete text field:*/
          inp.value = this.getElementsByTagName("input")[0].value;
          /*close the list of autocompleted values,
          (or any other open lists of autocompleted values:*/
          getOrganizationData();
        });
        a.appendChild(b);
      }
    }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function (e) {
    var x = document.getElementById(this.id + "autocomplete-list");
    if (x) x = x.getElementsByTagName("div");
    if (e.keyCode == 40) {
      /*If the arrow DOWN key is pressed,
      increase the currentFocus variable:*/
      currentFocus++;
      /*and and make the current item more visible:*/
      addActive(x);
    } else if (e.keyCode == 38) {
      //up
      /*If the arrow UP key is pressed,
      decrease the currentFocus variable:*/
      currentFocus--;
      /*and and make the current item more visible:*/
      addActive(x);
    } else if (e.keyCode == 13) {
      /*If the ENTER key is pressed, prevent the form from being submitted,*/
      e.preventDefault();
      if (currentFocus > -1) {
        /*and simulate a click on the "active" item:*/
        if (x) x[currentFocus].click();
      }
    }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = x.length - 1;
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
    closeAllLists(e.target);
  });
}

function autocomplete(inp, arr, callback) {
  var currentFocus;
  inp.addEventListener("input", function (e) {
    var a,
      b,
      i,
      val = this.value;
    closeAllLists();
    if (!val) {
      return false;
    }
    currentFocus = -1;
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items border position-absolute w-75");
    this.parentNode.appendChild(a);

    var count = 0; // Keep track of displayed suggestions
    for (i = 0; i < arr.length && count < 5; i++) {
      // Limit to 5 suggestions
      if (
        arr[i].title.substr(0, val.length).toUpperCase() == val.toUpperCase()
      ) {
        b = document.createElement("DIV");
        b.setAttribute("class", "autocomplete-data");
        b.innerHTML =
          "<strong>" + arr[i].title.substr(0, val.length) + "</strong>";
        b.innerHTML += arr[i].title.substr(val.length);
        b.innerHTML +=
          "<input type='hidden' value='" +
          arr[i].title +
          "' data-id='" +
          arr[i].id +
          "'>";
        b.addEventListener("click", function (e) {
          inp.value = this.getElementsByTagName("input")[0].value;
          inp.id =
            this.getElementsByTagName("input")[0].getAttribute("data-id");
          callback(inp.value, inp.id);
          closeAllLists();
          inp.value = "";
        });
        a.appendChild(b);
        count++; // Increment displayed suggestion count
      }
    }
  });

  function closeAllLists() {
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      x[i].parentNode.removeChild(x[i]);
    }
  }
}

var product = [];

if (document.getElementById("searchInput")) {
  $("#fromOutlet").change(function () {
    var fromOutletId = $(this).children(":selected").val();
    $("#show_Product table tbody").html("");
    $("#total").html(0);

    // console.log("hello" + fromOutletId);

    $.get(
      "/get-product-lists",
      { fromOutletId: fromOutletId },
      function (data, status) {
        // console.log(data);
        if (status == "success") {
          let productArr = [];
          product = Object.keys(data).map((key) => {
            productArr.push({
              id: key,
              title: data[key],
            });
          });

          // var distributedId = $("#distributedId").val();
          function resultGet(res, id) {
            $.ajax({
              url: "/search",
              type: "GET",
              data: {
                // distributed_id: distributedId,
                variant_id: id,
                from_outlet: fromOutletId,
              },
              success: function (response) {
                var tablehaveItem = $("#show_Product table tbody tr");
                var res = JSON.parse(response);
                var errorBox = $(".errorbox");
                if (
                  $('#ds_itemTable tbody tr[data-id="' + id + '"]').length === 0
                ) {
                  // Item does not exist, add it to the table
                  $("#show_Product table tbody").append(res.html);
                  var total = calculateTotal();
                  var numberformattotal = total.toLocaleString();
                  $("#total").html(numberformattotal);

                  errorBox.html("").removeClass("alert alert-danger");
                  if (tablehaveItem.css("background-color", "#fee7e1")) {
                    tablehaveItem.css("background-color", "");
                  }
                } else {
                  if (tablehaveItem.css("background-color", "#fee7e1")) {
                    tablehaveItem.css("background-color", "");
                  }
                  // Item already exists, display an error message or take appropriate action
                  if (errorBox.html() === "") {
                    errorBox
                      .append(
                        "<strong>Whoops!</strong> There were some problems with your input.<br><br>"
                      )
                      .addClass("alert alert-danger");
                    errorBox.append("This item is already have.<br/>");
                  } else {
                    errorBox.html("");
                    errorBox
                      .append(
                        "<strong>Whoops!</strong> There were some problems with your input.<br><br>"
                      )
                      .addClass("alert alert-danger");
                    errorBox.append("This item is already have.<br/>");
                  }

                  $('#ds_itemTable tbody tr[data-id="' + id + '"]').css(
                    "background-color",
                    "#fee7e1"
                  );

                  // setTimeout(function () {
                  //   $('#ds_itemTable tbody tr[data-id="' + id + '"]').removeClass(
                  //     "border border-warning"
                  //   );
                  //   errorBox.html("").removeClass("alert alert-danger");
                  // }, 10000);
                  // Remove the highlight when clicked
                  $('#ds_itemTable tbody tr[data-id="' + id + '"]').on(
                    "click",
                    function () {
                      $(this).css("background-color", "");
                      errorBox.html("").removeClass("alert alert-danger");
                    }
                  );

                  $(window).scrollTop(0);
                }
              },
            });
          }
          autocomplete(
            document.getElementById("searchInput"),
            productArr,
            resultGet
          );
        } else {
          console.log(status);
        }
      }
    );
  });

  // var fromOutletId = $("#searchInput").data("id");

  // $.get(
  //   "/get-product-lists",
  //   { fromOutletId: fromOutletId },
  //   function (data, status) {
  //     console.log(data);
  //     if (status == "success") {
  //       let productArr = [];
  //       product = Object.keys(data).map((key) => {
  //         productArr.push({
  //           id: key,
  //           title: data[key],
  //         });
  //       });

  //       var distributedId = $("#distributedId").val();
  //       function resultGet(res, id) {
  //         $.ajax({
  //           url: "/search",
  //           type: "GET",
  //           data: {
  //             distributed_id: distributedId,
  //             variant_id: id,
  //             from_outlet: fromOutletId,
  //           },
  //           success: function (response) {
  //             var tablehaveItem = $("#show_Product table tbody tr");
  //             var res = JSON.parse(response);
  //             var errorBox = $(".errorbox");
  //             if ($('#itemTable tbody tr[data-id="' + id + '"]').length === 0) {
  //               // Item does not exist, add it to the table
  //               $("#show_Product table tbody").append(res.html);
  //               var total = calculateTotal();
  //               $("#total").html(total);
  //               errorBox.html("").removeClass("alert alert-danger");
  //               if (tablehaveItem.css("background-color", "#fee7e1")) {
  //                 tablehaveItem.css("background-color", "");
  //               }
  //             } else {
  //               if (tablehaveItem.css("background-color", "#fee7e1")) {
  //                 tablehaveItem.css("background-color", "");
  //               }
  //               // Item already exists, display an error message or take appropriate action
  //               if (errorBox.html() === "") {
  //                 errorBox
  //                   .append(
  //                     "<strong>Whoops!</strong> There were some problems with your input.<br><br>"
  //                   )
  //                   .addClass("alert alert-danger");
  //                 errorBox.append("This item is already have.<br/>");
  //               } else {
  //                 errorBox.html("");
  //                 errorBox
  //                   .append(
  //                     "<strong>Whoops!</strong> There were some problems with your input.<br><br>"
  //                   )
  //                   .addClass("alert alert-danger");
  //                 errorBox.append("This item is already have.<br/>");
  //               }

  //               $('#itemTable tbody tr[data-id="' + id + '"]').css(
  //                 "background-color",
  //                 "#fee7e1"
  //               );

  //               // setTimeout(function () {
  //               //   $('#itemTable tbody tr[data-id="' + id + '"]').removeClass(
  //               //     "border border-warning"
  //               //   );
  //               //   errorBox.html("").removeClass("alert alert-danger");
  //               // }, 10000);
  //               // Remove the highlight when clicked
  //               $('#itemTable tbody tr[data-id="' + id + '"]').on(
  //                 "click",
  //                 function () {
  //                   $(this).css("background-color", "");
  //                   errorBox.html("").removeClass("alert alert-danger");
  //                 }
  //               );

  //               $(window).scrollTop(0);
  //             }
  //           },
  //           error: function (xhr, status, error) {
  //             alert("An error occurred while adding the product to cart");
  //             console.log(xhr.responseText);
  //           },
  //         });
  //       }
  //       autocomplete(
  //         document.getElementById("searchInput"),
  //         productArr,
  //         resultGet
  //       );
  //     } else {
  //       console.log(status);
  //     }
  //   }
  // );
}

if (document.getElementById("outletdistir_searchInput")) {
  var fromOutletId = $("#outletdistir_searchInput").data("id");
  // console.log(fromOutletId);
  $.get(
    "/get-product-lists",
    { fromOutletId: fromOutletId },
    function (data, status) {
      // console.log(data);
      if (status == "success") {
        let productArr = [];
        product = Object.keys(data).map((key) => {
          productArr.push({
            id: key,
            title: data[key],
          });
        });

        // console.log(productArr);
        // var outletdistribute_id = $("#outletdistribute_id").val();
        // console.log(outletdistribute_id, "hello");
        function resultGet(res, id) {
          $.ajax({
            url: "/search",
            type: "GET",
            data: {
              variant_id: id,
              from_outlet: fromOutletId,
            },
            success: function (response) {
              var tablehaveItem = $("#show_Product table tbody tr");
              var res = JSON.parse(response);
              var errorBox = $(".errorbox");
              if (
                $('#outds_itemTable tbody tr[data-id="' + id + '"]').length ===
                0
              ) {
                // Item does not exist, add it to the table
                $("#show_Product table tbody").append(res.html);
                var total = calculateTotal();
                $("#total").html(total);
                errorBox.html("").removeClass("alert alert-danger");
                if (tablehaveItem.css("background-color", "#fee7e1")) {
                  tablehaveItem.css("background-color", "");
                }
              } else {
                if (tablehaveItem.css("background-color", "#fee7e1")) {
                  tablehaveItem.css("background-color", "");
                }
                // Item already exists, display an error message or take appropriate action
                if (errorBox.html() === "") {
                  errorBox
                    .append(
                      "<strong>Whoops!</strong> There were some problems with your input.<br><br>"
                    )
                    .addClass("alert alert-danger");
                  errorBox.append("This item is already have.<br/>");
                } else {
                  errorBox.html("");
                  errorBox
                    .append(
                      "<strong>Whoops!</strong> There were some problems with your input.<br><br>"
                    )
                    .addClass("alert alert-danger");
                  errorBox.append("This item is already have.<br/>");
                }

                $('#outds_itemTable tbody tr[data-id="' + id + '"]').css(
                  "background-color",
                  "#fee7e1"
                );

                // setTimeout(function () {
                //   $('#outds_itemTable tbody tr[data-id="' + id + '"]').removeClass(
                //     "border border-warning"
                //   );
                //   errorBox.html("").removeClass("alert alert-danger");
                // }, 10000);
                // Remove the highlight when clicked
                $('#outds_itemTable tbody tr[data-id="' + id + '"]').on(
                  "click",
                  function () {
                    $(this).css("background-color", "");
                    errorBox.html("").removeClass("alert alert-danger");
                  }
                );

                $(window).scrollTop(0);
              }
            },
          });
        }
        autocomplete(
          document.getElementById("outletdistir_searchInput"),
          productArr,
          resultGet
        );
      } else {
        console.log(status);
      }
    }
  );
}

if (document.getElementById("outletissue_searchInput")) {
  $("#to_machine").change(function () {
    var to_machine = $(this).children(":selected").val();
    $("#show_Product table tbody").html("");
    $("#total").html(0);
    // console.log(toMachine);

    $.get(
      "/get-outletdistir-issue-lists",
      { to_machine: to_machine },
      function (data, status) {
        // console.log(data);
        if (status == "success") {
          let productArr = [];
          product = Object.keys(data).map((key) => {
            productArr.push({
              id: key,
              title: data[key],
            });
          });

          // console.log(productArr);
          // var outletdistributed_id = $("#outletdistributed_id").val();
          // console.log(outletdistribute_id, "hello");
          function resultGet(res, id) {
            $.ajax({
              url: "/search-outlet-issue",
              type: "GET",
              data: {
                // outlet_distributed_id: outletdistributed_id,
                variant_id: id,
                to_machine: to_machine,
              },
              success: function (response) {
                var tablehaveItem = $("#show_Product table tbody tr");
                var res = JSON.parse(response);
                var errorBox = $(".errorbox");
                if (
                  $('#issue_itemTable tbody tr[data-id="' + id + '"]')
                    .length === 0
                ) {
                  // Item does not exist, add it to the table
                  $("#show_Product table tbody").append(res.html);
                  var total = calculateTotal();
                  $("#total").html(total);
                  errorBox.html("").removeClass("alert alert-danger");
                  if (tablehaveItem.css("background-color", "#fee7e1")) {
                    tablehaveItem.css("background-color", "");
                  }
                } else {
                  if (tablehaveItem.css("background-color", "#fee7e1")) {
                    tablehaveItem.css("background-color", "");
                  }
                  // Item already exists, display an error message or take appropriate action
                  if (errorBox.html() === "") {
                    errorBox
                      .append(
                        "<strong>Whoops!</strong> There were some problems with your input.<br><br>"
                      )
                      .addClass("alert alert-danger");
                    errorBox.append("This item is already have.<br/>");
                  } else {
                    errorBox.html("");
                    errorBox
                      .append(
                        "<strong>Whoops!</strong> There were some problems with your input.<br><br>"
                      )
                      .addClass("alert alert-danger");
                    errorBox.append("This item is already have.<br/>");
                  }

                  $('#issue_itemTable tbody tr[data-id="' + id + '"]').css(
                    "background-color",
                    "#fee7e1"
                  );

                  // setTimeout(function () {
                  //   $('#issue_itemTable tbody tr[data-id="' + id + '"]').removeClass(
                  //     "border border-warning"
                  //   );
                  //   errorBox.html("").removeClass("alert alert-danger");
                  // }, 10000);
                  // Remove the highlight when clicked
                  $('#issue_itemTable tbody tr[data-id="' + id + '"]').on(
                    "click",
                    function () {
                      $(this).css("background-color", "");
                      errorBox.html("").removeClass("alert alert-danger");
                    }
                  );

                  $(window).scrollTop(0);
                }
              },
            });
          }
          autocomplete(
            document.getElementById("outletissue_searchInput"),
            productArr,
            resultGet
          );
        } else {
          console.log(status);
        }
      }
    );
  });
}

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

if (document.getElementById("searchInputPurchase")) {
  $.get("/get-product-lists-puchase", function (data, status) {
    // console.log(data);
    if (status == "success") {
      let productArr = [];
      product = Object.keys(data).map((key) => {
        productArr.push({
          id: key,
          title: data[key],
        });
      });

      // var distributedId = $("#distributedId").val();
      function resultGet(res, id) {
        $.ajax({
          url: "/search-purchase",
          type: "GET",
          data: {
            variant_id: id,
          },
          success: function (response) {
            // console.log(response);
            var errorBox = $(".errorbox");
            var tablehaveItem = $("#show_Product table tbody tr");
            // console.log(tablehaveItem);
            if (
              $('#ds_itemTable tbody tr[data-id="' + id + '"]').length === 0
            ) {
              $("#show_Product table tbody").append(response);
              errorBox.html("").removeClass("alert alert-danger");
              if (tablehaveItem.css("background-color", "#fee7e1")) {
                tablehaveItem.css("background-color", "");
              }
            }
          },
        });
      }
      autocomplete(
        document.getElementById("searchInputPurchase"),
        productArr,
        resultGet
      );
    } else {
      console.log(status);
    }
  });
}
