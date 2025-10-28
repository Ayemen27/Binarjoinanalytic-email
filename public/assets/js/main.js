(function ($) {
  "use strict";

  // Get Current Year
  document.querySelectorAll("[data-year]").forEach(function (el) {
    el.textContent = new Date().getFullYear();
  });

  // Dropdown
  const dropdown = document.querySelectorAll('[data-dropdown]');
  if (dropdown) {
    dropdown.forEach(function (el) {
      let dropdownClose = el.querySelectorAll("[data-dropdown-close]");
      window.addEventListener("click", function (e) {
        if (el.contains(e.target) && el.hasAttribute("data-dropdown-propagation")) {
          if (!e.target.closest(".drop-down-menu")) {
            el.classList.toggle('active');
            setTimeout(function () {
              el.classList.toggle('animated');
            }, 0);
          }
        } else if (el.contains(e.target)) {
          el.classList.toggle('active');
          setTimeout(function () {
            el.classList.toggle('animated');
          }, 0);
        } else {
          el.classList.remove('active');
          el.classList.remove('animated');
        }
      });
      if (dropdownClose) {
        dropdownClose.forEach((closeBtn) => {
          closeBtn.addEventListener("click", () => {
            el.classList.remove('active');
            el.classList.remove('animated');
          });
        });
      }
    });
  }

  // Toggle
  var toggle = document.querySelectorAll('[data-toggle]');
  if (toggle) {
    toggle.forEach(function (el, id) {
      el.querySelector(".toggle-title").addEventListener("click", () => {
        for (var i = 0; i < toggle.length; i++) {
          if (i !== id) {
            toggle[i].classList.remove("active");
            toggle[i].classList.remove("animated");
          }
        }
        if (el.classList.contains("active")) {
          el.classList.remove("active");
          el.classList.remove("animated");
        } else {
          el.classList.add("active");
          setTimeout(function () {
            el.classList.add("animated");
          }, 0);
        }
      });
    });
  }

  // Dashboard
  const dashboard = document.querySelector(".dashboard"),
    dashboardToggleBtn = document.querySelectorAll(".dashboard-toggle-btn");
  if (dashboard) {
    dashboardToggleBtn.forEach((el) => {
      el.addEventListener("click", () => {
        dashboard.classList.toggle("toggle");
      });
    });
    const dashboardOverlay = dashboard.querySelector(".dashboard-sidebar .overlay");
    if (dashboardOverlay) {
      dashboardOverlay.addEventListener("click", () => {
        dashboard.classList.remove("toggle");
      });
    }
  }

  // Upload Image
  // Upload Image
document.querySelectorAll(".upload-image").forEach(imgContainer => {
    let imgInput = imgContainer.querySelector("input"),
        imgFile = imgContainer.querySelector("img");

    imgInput.onchange = () => {
      const [file] = imgInput.files;
      if (file) {
        imgFile.src = URL.createObjectURL(file);
        imgContainer.classList.add("active");
      } else {
        imgFile.src = '';
        imgContainer.classList.remove("active");
      }
    };
  });

  // Announcement


  // Password Input
  let password = document.querySelectorAll(".input-password");
  if (password.length > 0) {
    password.forEach((el) => {
      let passwordBtn = el.querySelector("button");
      let passwordInput = el.querySelector("input[type='password']") || el.querySelector("input[type='text']");

      if (passwordBtn && passwordInput) {
        passwordBtn.onclick = (e) => {
          e.preventDefault();
          if (passwordInput.type === "password") {
            passwordInput.type = "text";
            passwordBtn.querySelector('i').className = "fa fa-eye-slash";
          } else {
            passwordInput.type = "password";
            passwordBtn.querySelector('i').className = "fa fa-eye";
          }
        };
      }
    });
  }

  // Steps
  const stepSidebar = document.querySelector(".steps-sidebar");
  const sidebarToggle = document.querySelector(".sidebar-toggle");
  const sidebarClose = document.querySelector(".sidebar-close");
  
  if (stepSidebar && sidebarToggle && sidebarClose) {
    sidebarToggle.addEventListener("click", () => {
      stepSidebar.classList.add("show");
    });

    sidebarClose.addEventListener("click", () => {
      stepSidebar.classList.remove("show");
    });
  }

  $('.tagsinput input').tagsinput({
    cancelConfirmKeysOnEmpty: false
  });

  const goToValue = document.getElementById('goToValue');

    if (goToValue) {
        goToValue.addEventListener('change', function() {
            var selectedValue = this.value;
            if (selectedValue) {
                window.location.href = selectedValue; // Redirect to the selected route
            }
        });
    }


    const deleteModal = document.getElementById('deleteModal');
    if(deleteModal){
        document.addEventListener('DOMContentLoaded', function() {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget; // Button that triggered the modal
                const id = button.getAttribute('data-id'); // Extract info from data-* attributes
                const action = button.getAttribute('data-action');

                const form = deleteModal.querySelector('#deleteForm');
                form.setAttribute('action', action); // Set the form action attribute to the delete URL
            });
        });
    }



})(jQuery);