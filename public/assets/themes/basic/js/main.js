$(window).on("load", function() {
    $(".preloader").fadeOut("slow");
});


"use strict";

// Clipboard JS

  // Get Current Year
  document.querySelectorAll("[data-year]").forEach(function (el) {
    el.textContent = new Date().getFullYear();
  });


  document.addEventListener("DOMContentLoaded", function() {
    const cookieConsentDiv = document.getElementById('cookieConsent');
    ////console.log("cookieConsentDiv:", cookieConsentDiv);
    ////console.log("Cookies: ", document.cookie);
    ////console.log(document.cookie.includes("cookie_consent=accepted"));
    if (!document.cookie.includes("cookie_consent=accepted")) {
        if (cookieConsentDiv) {
            setTimeout(() => {
                cookieConsentDiv.classList.add('show');
            }, 1000);
            //cookieConsentDiv.classList.add('show');
        }
    }

    const acceptCookieBtn = document.getElementById('acceptCookie');
    //console.log("acceptCookieBtn:", acceptCookieBtn);

    if (acceptCookieBtn) {
        acceptCookieBtn.addEventListener('click', function() {
            document.cookie = "cookie_consent=accepted; path=/; max-age=" + (60 * 60 * 24 * 365);

            if (cookieConsentDiv) {
                cookieConsentDiv.classList.remove('show');
            }
        });
    }
});



$(".mail-select").click(function() {
    let copyBtn = document.querySelector(".mail-select");
    let copyIcon = document.querySelector("#copyIcon");

    if (copyBtn) {
        // Change the icon to a checkmark (or success icon) to indicate copying
        new ClipboardJS(copyBtn);

        copyIcon.classList.remove("far", "fa-clone");
        copyIcon.classList.add("fas", "fa-check");

        // After a certain delay (e.g., 2 seconds), change the icon back to the clone icon
        setTimeout(function () {
            copyIcon.classList.remove("fas", "fa-check");
            copyIcon.classList.add("far", "fa-clone");
        }, 1000); // 2000 milliseconds = 2 seconds
    }
});

// Dropdown
let dropdown = document.querySelectorAll("[dropdown]");

if (dropdown) {
  dropdown.forEach((el) => {
    window.addEventListener("click", (e) => {
      if (el.contains(e.target)) {
        el.classList.toggle("active");

        setTimeout(() => {
          el.classList.toggle("animated");
        }, 10);
      } else {
        el.classList.remove("active");
        el.classList.remove("animated");
      }
    });
  });
}

// Navbar
let navbar = document.querySelector(".nav");
if (navbar) {
  function navbarOP() {
    if (window.scrollY > 100) {
      navbar.classList.add("nav-sticky");
    } else {
      navbar.classList.remove("nav-sticky");
    }
  }
  window.addEventListener("load", navbarOP);
  window.addEventListener("scroll", navbarOP);
}

// Nav Menu
let navMenu = document.querySelector(".nav-menu"),
  navMenuBtn = document.querySelector(".nav-menu-button");
if (navMenu) {
  let navMenuClose = navMenu.querySelector(".nav-menu-close"),
    navMenuOverlay = navMenu.querySelector(".overlay");
  navMenuBtn.onclick = () => {
    navMenu.classList.add("show");
    document.body.classList.add("overflow-hidden");
  };
  navMenuClose.onclick = navMenuOverlay.onclick = () => {
    navMenu.classList.remove("show");
    document.body.classList.remove("overflow-hidden");
  };
}

/*
// perfect Scroll
const scroller = document.querySelector('.nav-menu-scroller');
if (scroller) {
  const ps = new PerfectScrollbar(scroller);
}
*/


// Header Circles
let headerCircles = document.querySelectorAll(".header-circle");
if (headerCircles) {
  headerCircles.forEach((el) => {
    el.style.width = el.scrollHeight + "px";
    el.style.height = el.scrollHeight + "px";
  });
}

// Go Up
let goUp = document.querySelector(".go-up");
if (goUp) {
  function goUpActive() {
    if (window.scrollY > window.innerHeight) {
      goUp.classList.add("show");
    } else {
      goUp.classList.remove("show");
    }
  }
  goUp.onclick = () => {
    window.scrollTo(0, 0);
  };
  window.addEventListener("load", goUpActive);
  window.addEventListener("scroll", goUpActive);
}

// History > Select All
let historyDays = document.querySelectorAll(".mail-history-day");
if (historyDays) {
  historyDays.forEach((el) => {
    let historyDaysMainInput = el.querySelector(".mail-history-day-header input"),
      historyDaysInputs = el.querySelectorAll(".mail-history-day-body input");
    historyDaysMainInput.onchange = () => {
      if (historyDaysMainInput.checked === true) {
        historyDaysInputs.forEach((input) => {
          input.checked = true;
        });
      } else {
        historyDaysInputs.forEach((input) => {
          input.checked = false;
        });
      }
    };
    historyDaysInputs.forEach((input) => {
      input.onchange = () => {
        setTimeout(() => {
          if (el.querySelectorAll(".mail-history-day-body input:checked").length === historyDaysInputs.length) {
            historyDaysMainInput.checked = true;
          } else {
            historyDaysMainInput.checked = false;
          }
        }, 10);
      };
    });
  });
}

// Discount
let discount = document.querySelector(".discount"),
  discountbtn = document.querySelector(".discount-btn");
if (discount) {
  discountbtn.onclick = () => {
    discount.classList.toggle("show");
    setTimeout(() => {
      discount.classList.toggle("animate");
    }, 0);
  };
}



function formatText(icon) {
  return $('<span class="select2-container-custom-span" ><i class="fas ' + $(icon.element).data('icon') +
    '"></i> ' + icon.text + '</span>');
};

if ($('.select-input')) {
  $(document).ready(function () {
    $('.select-input').select2({
      width: '100%',
      minimumResultsForSearch: -1,
      templateResult: formatText
    });
  });
}

var selDiv = "";
var storedFiles = [];
$(document).ready(function () {
  $("#change_avatar").on("change", handleFileSelect);
  selDiv = $("#selectedImg");
});

function handleFileSelect(e) {
  var files = e.target.files;
  var filesArr = Array.prototype.slice.call(files);
  filesArr.forEach(function (f) {
    if (!f.type.match("image.*")) {
      return;
    }
    storedFiles.push(f);

    var reader = new FileReader();
    reader.onload = function (e) {
      var html =
        '<img src="' +
        e.target.result +
        "\" data-file='" +
        f.name +
        "alt='Category Image' height='200px' width='200px'>";
      selDiv.html(html);
    };
    reader.readAsDataURL(f);
  });
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})


$(document).ready(function () {
    // Get the selected value
    var gatewayValue = $("input[name='gateway']:checked").val();

    // Check if "bank" is selected or no radio button is checked, then show the div, otherwise hide it
    if (gatewayValue === "bank") {
        $('#bankDiv').show();
    } else {
        $('#bankDiv').hide();
    }

    // Listen for changes in the radio button selection
    $('input[name="gateway"]').change(function () {
        var gatewayValue = $("input[name='gateway']:checked").val();

        if (gatewayValue === "bank") {
            $('#bankDiv').show();
        } else {
            $('#bankDiv').hide();
        }
    });


            var discountDiv = $('#discountDiv');
            var discountInput = $('input[name="discount"]').val();

            $('.discount-btn').click(function () {
                discountDiv.toggle();
            });

            if (discountInput && discountInput.trim()) {
                discountDiv.show();
            } else {
                discountDiv.hide();
            }



            function toggleMailActionTooltips() {
                const mailActionTooltips = document.querySelectorAll('.mail-actions [data-toggle="tooltip"]');

                if (window.innerWidth > 992) {

                    mailActionTooltips.forEach(el => {
                        //console.log(window.innerWidth);
                        let tooltipInstance = bootstrap.Tooltip.getInstance(el);
                        //console.log(tooltipInstance);
                        if (tooltipInstance) {

                            tooltipInstance.dispose(); // Hide tooltips inside .mail-actions
                        }
                    });
                } else {
                    mailActionTooltips.forEach(el => {
                        new bootstrap.Tooltip(el); // Show tooltips inside .mail-actions
                    });
                }
            }

            // Run on page load
            toggleMailActionTooltips();

            // Run on window resize
            window.addEventListener("resize", toggleMailActionTooltips);



                document.querySelectorAll("figure table").forEach(function (table) {
                    table.classList.add("table", "table-bordered", "table-hover");

                    let thead = table.querySelector("thead");
                    if (thead) {
                        thead.classList.add("table-dark");
                    }
                });





});



