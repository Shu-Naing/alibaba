// dropdown plus and minutes start
const aElement = document.querySelectorAll(".dropdownli");

document.addEventListener("DOMContentLoaded", function () {
  // Update the text content for each dropdownli element on page load
  aElement.forEach(function (element) {
    const classExit = element.parentNode.querySelectorAll(
      ".submenuParent li a.active"
    );
    const spanElement = element.querySelector(".showHide");
    if (classExit.length > 0) {
      spanElement.textContent = "-";
    } else {
      spanElement.textContent = "+";
    }
  });
});

// console.log(aElement);
aElement.forEach(function (element) {
  element.addEventListener("click", () => {
    const classExit = element.classList.contains("collapsed");
    const spanElement = element.querySelector(".showHide");
    // console.log(classExit);
    spanElement.textContent = classExit ? "+" : "-";
  });
});
// dropdown plus and minutes end

/*
Template Name: Admin Template
Author: Wrappixel

File: js
*/
// ==============================================================
// Auto select left navbar
// ==============================================================
// $(function () {
//   "use strict";
//   var url = window.location + "";
//   var path = url.replace(
//     window.location.protocol + "//" + window.location.host + "/",
//     ""
//   );
//   var element = $("ul#sidebarnav a").filter(function () {
//     return this.href === url || this.href === path; // || url.href.indexOf(this.href) === 0;
//   });
//   element.parentsUntil(".sidebar-nav").each(function (index) {
//     if ($(this).is("li") && $(this).children("a").length !== 0) {
//       $(this).children("a").addClass("active");
//       $(this).parent("ul#sidebarnav").length === 0
//         ? $(this).addClass("active")
//         : $(this).addClass("selected");
//     } else if (!$(this).is("ul") && $(this).children("a").length === 0) {
//       $(this).addClass("selected");
//     } else if ($(this).is("ul")) {
//       $(this).addClass("in");
//     }
//   });

//   element.addClass("active");
//   $("#sidebarnav a").on("click", function (e) {
//     if (!$(this).hasClass("active")) {
//       // hide any open menus and remove all other classes
//       $("ul", $(this).parents("ul:first")).removeClass("in");
//       $("a", $(this).parents("ul:first")).removeClass("active");

//       // open our new menu and add the open class
//       $(this).next("ul").addClass("in");
//       $(this).addClass("active");
//     } else if ($(this).hasClass("active")) {
//       $(this).removeClass("active");
//       $(this).parents("ul:first").removeClass("active");
//       $(this).next("ul").removeClass("in");
//     }
//   });
//   $("#sidebarnav >li >a.has-arrow").on("click", function (e) {
//     e.preventDefault();
//   });
// });
