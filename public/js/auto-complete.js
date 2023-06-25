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
  var fromOutletId = $("#searchInput").data("id");
  console.log(fromOutletId);
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

        var distributedId = $("#distributedId").val();
        function resultGet(res, id) {
          $.ajax({
            url: "/search",
            type: "GET",
            data: {
              distributed_id: distributedId,
              variant_id: id,
              from_outlet: fromOutletId,
            },
            success: function (response) {
              location.reload();
              // console.log(response);
              // let res = JSON.parse(response);
              // $("#show_dsProduct").html(res.html);
              // $("#total").html(res.total);
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
}

if (document.getElementById("outletdistir_searchInput")) {
  var fromOutletId = $("#outletdistir_searchInput").data("id");
  $.get(
    "/get-outletdistir-product-lists",
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
        var outletdistribute_id = $("#outletdistribute_id").val();
        // console.log(outletdistribute_id, "hello");
        function resultGet(res, id) {
          $.ajax({
            url: "/search-outlet-distributes",
            type: "GET",
            data: {
              outlet_distributed_id: outletdistribute_id,
              variant_id: id,
              from_outlet: fromOutletId,
            },
            success: function (response) {
              location.reload();
              // console.log('response',response);
              // console.log(response.purchased_price);
              // let res = JSON.parse(response);
              // $("#show_dsProduct").html(res.html);
              // $("#total").html(res.total);
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
  var to_machine = $("#outletissue_searchInput").data("id");
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
        var outletdistributed_id = $("#outletdistributed_id").val();
        // console.log(outletdistribute_id, "hello");
        function resultGet(res, id) {
          $.ajax({
            url: "/search-outlet-issue",
            type: "GET",
            data: {
              outlet_distributed_id: outletdistributed_id,
              variant_id: id,
              from_outlet: fromOutletId,
            },
            success: function (response) {
              location.reload();
              // console.log(response);
              // console.log(response.purchased_price);
              // let res = JSON.parse(response);
              // $("#show_dsProduct").html(res.html);
              // $("#total").html(res.total);
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
}
