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
    dashboard.querySelector(".dashboard-sidebar .overlay").addEventListener("click", () => {
      dashboard.classList.remove("toggle");
    });
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
  const announcement = document.querySelectorAll(".announcement");
  if (announcement) {
    announcement.forEach((el) => {
      const announcementMore = el.querySelector(".announcement-more");
      if (el.offsetHeight >= 600) {
        el.classList.add("disactive");
        el.style.setProperty("height", "550px");
        announcementMore.addEventListener("click", () => {
          el.classList.remove("disactive");
          el.style.setProperty("height", "auto");
        });
      }
    });
  }

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
  if (stepSidebar) {
    const sidebarToggle = document.querySelector(".sidebar-toggle"),
      sidebarClose = document.querySelector(".sidebar-close");
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

  // Select
  function formatState (state) {
    if (!state.id) {
      return state.text;
    }
    var baseUrl = "/assets/img/flags";
    var $state = $(
      `<span><img src="${baseUrl}/${state.element.value.toLowerCase()}.png" class="select-img" /> ${state.text}</span>`
    );
    return $state;
  }

  $(document).ready(function() {
    $('.select2-img').select2({
      templateResult: formatState
    });
  });

  $(document).ready(function() {
    $('.select2-multiple').select2();
  });


  function inputChangeAgain(){
    const inputsTarget = document.querySelectorAll("[input-target]");
    const inputsPlaceholder = document.querySelectorAll("[input-placeholder]");
    if (inputsTarget) {
        inputsTarget.forEach((el) => {
        const target = document.querySelector(el.getAttribute("input-target"));

        function placeholderChange () {
            if (inputsPlaceholder) {
            inputsPlaceholder.forEach((placeholder) => {
                if (el.getAttribute("input-target") === placeholder.getAttribute("input-placeholder")) {
                el.placeholder = placeholder.value;

                if (el.value === "") {
                    target.textContent = placeholder.value;
                }
                }
                placeholder.addEventListener("change", inputChange);
                placeholder.addEventListener("keydown", inputChange);
                placeholder.addEventListener("keyup", inputChange);
            });
            }

        }
        function inputChange () {
            target.textContent = el.value;
            if (target.nodeName === "A") {
            target.href =  target.href = target.dataset.url + el.value;
            console.log("Yes");
            } else {
            placeholderChange();
            }
        }
        inputChange();
        el.addEventListener("change", inputChange);
        el.addEventListener("keydown", inputChange);
        el.addEventListener("keyup", inputChange);
        });
    }
  }

  inputChangeAgain();

  if ($(".select-input")) {
    $(document).ready(function () {
        $(".select-input").select2({
            width: "100%",
            minimumResultsForSearch: -1,
        });
    });
}

// Select2 Select Width Search
if ($(".select-input_search")) {
    $(document).ready(function () {
        $(".select-input_search").select2({
            width: "100%",
        });
    });
}



$("#title").on("change", function () {
    $.ajax({
        type: "GET",
        url: checkslug_title,
        data: {
            title: $(this).val(),
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
            $("#slug").val(data.slug);
            inputChangeAgain();

        },
    });
});


$("#language").on("change", function () {
    const lang = $(this).val();
    if (lang) {
        $.ajax({
            url: BASE_URL + "/blog/posts/getcategory/" + lang,
            type: "GET",
            dataType: "json",
            success: function (data) {
                if ($.isEmptyObject(data.message)) {
                    $("#category").empty();
                    $.each(data, function (key, value) {
                        $("#category").append(
                            '<option value="' +
                                key +
                                '">' +
                                value +
                                "</option>"
                        );
                    });
                } else {
                    $("#category").empty();
                    $("#category").append(
                        '<option value="" selected disabled>Choose</option>'
                    );
                    alert(data.message);
                }
            },
        });
    } else {
        $("#category").empty();
    }
});



var updateOutput2 = function () {
    var output = $(".dd").nestable("serialize");
    $("#nestable-output").val(window.JSON.stringify(output));
};

$("#nestable1")
    .nestable({
        maxDepth: 2,
        expandBtnHTML: "",
        collapseBtnHTML: "",
        group: 1,
    })
    .on("change", updateOutput2);

$("#nestable2")
    .nestable({
        maxDepth: 1,
        expandBtnHTML: "",
        collapseBtnHTML: "",
        group: 1,
    })
    .on("change", updateOutput2);
updateOutput2();

$('.select-2').select2();

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

try {
    document.querySelector("#gen_btn").onclick = genPassword;
} catch (error) {}

try {
    document.querySelector("#gen_code").onclick = genCode;
} catch (error) {}

try {
    document.querySelector("#gen_api").onclick = genAPi;
} catch (error) {}

function genPassword() {
    var chars =
        "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var passwordLength = 12;
    var password = "";
    for (var i = 0; i <= passwordLength; i++) {
        var randomNumber = Math.floor(Math.random() * chars.length);
        password += chars.substring(randomNumber, randomNumber + 1);
    }
    document.getElementById("password").value = password;
}


function genAPi() {
    var chars =
        "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var apiLength = 30;
    var api = "";
    for (var i = 0; i <= apiLength; i++) {
        var randomNumber = Math.floor(Math.random() * chars.length);
        api += chars.substring(randomNumber, randomNumber + 1);
    }
    document.getElementById("api_key").value = api;
}



function genCode() {
    var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var codeLength = 8;
    var code = "";
    for (var i = 0; i <= codeLength; i++) {
        var randomNumber = Math.floor(Math.random() * chars.length);
        code += chars.substring(randomNumber, randomNumber + 1);
    }
    document.getElementById("code").value = code;
}


$("#mailer").on("change", function () {
    const lang = $(this).val();
    if (lang == "smtp") {
        $(".mailer_show").addClass("d-block");
        $(".mailer_show").removeClass("d-none");
    } else {
        $(".mailer_show").addClass("d-none");
        $(".mailer_show").removeClass("d-block");
    }
});

$(".check_imap").on("click", function () {
    $(this).attr("disabled", "disabled");
    $("#log_info").html("");
    $(".log").css("display", "block");
    var _token = $("input[name='_token']").val();
    var host = $("input[name='imap_host']").val();
    var port = $("input[name='imap_port']").val();
    var user = $("input[name='imap_user']").val();
    var pass = $("input[name='imap_pass']").val();
    var encryption = $("#imap_encryption").val();
    var certificate = $("#validate_certificates").val();

    $("#log_info").append('<div class="info">Connecting...</div>');
    $.ajax({
        url: check_link,
        type: "POST",
        data: {
            _token: _token,
            host: host,
            port: port,
            user: user,
            pass: pass,
            encryption: encryption,
            certificate: certificate,
        },
        success: function (data) {
            $(".check_imap").removeAttr("disabled", "disabled");
            $("#log_info").html("");
            $("#log_info").append(data);
        },
        error: function (data) {
            $(".check_imap").removeAttr("disabled", "disabled");
            $("#log_info").append(data);
        },
    });
});

const checkIMAPButton = document.getElementById("checkIMAP");
if (checkIMAPButton) {
    checkIMAPButton.addEventListener("click", async function () {
        $(this).attr("disabled", "disabled");
        $("#log_info").html("");
        $(".log").css("display", "block");
        $("#log_info").append(
            '<div class="info" id="progressbar">Connecting...</div>'
        );
        $("#log_info").append('<div class="info" id="checker"></div>');

        const _token = $("input[name='_token']").val();
        const host = $("input[name='imap_host']").val();
        const user = $("input[name='imap_user']").val();
        const pass = $("input[name='imap_pass']").val();
        const ports = [993, 143];
        const encryptions = ["ssl", "tls"];
        const validateCerts = [0, 1];

        const successfulSettings = [];
        let successMessageReceived = false;
        let successMessage = "";
        let combinationsCount = 0;
        const totalCombinations = 8;

        for (const port of ports) {
            for (const encryption of encryptions) {
                for (const validateCert of validateCerts) {
                    document.getElementById(
                        "checker"
                    ).innerHTML = `We Test => [ Port: ${port}, Encryption: ${encryption}, Certificate Validation: ${
                        validateCert === "yes" ? "Enabled" : "Disabled"
                    }]`;
                    const result = await testIMAPConnection(
                        _token,
                        user,
                        pass,
                        host,
                        port,
                        encryption,
                        validateCert
                    );

                    console.log(result.success);
                    console.log(result.message);
                    successMessage = result.message;
                    if (result.success) {
                        $("#imap_port").val(port);
                        $("#imap_encryption")
                            .val(encryption)
                            .trigger("change");
                        $("#validate_certificates")
                            .val(validateCert)
                            .trigger("change");
                        successMessageReceived = true;
                        break;
                    }

                    combinationsCount++;
                    const progressBar = updateProgressBar(
                        combinationsCount,
                        totalCombinations
                    );
                }
                if (successMessageReceived) break;
            }
            if (successMessageReceived) break;
        }

        if (successMessageReceived) {
            $("#log_info").html("");
            $("#log_info").append(
                '<div class="success">' + successMessage + "</div>"
            );
        } else {
            $("#log_info").html("");
            $("#log_info").append(
                '<div class="error">' + successMessage + "</div>"
            );
        }

        $("#checkIMAP").removeAttr("disabled", "disabled");
    });

    async function testIMAPConnection(
        _token,
        user,
        pass,
        host,
        port,
        encryption,
        certificate
    ) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            const url = check_link_test; // Replace with your server-side script URL
            const params = `host=${host}&port=${port}&encryption=${encryption}&certificate=${certificate}&_token=${_token}&user=${user}&pass=${pass}`;

            xhr.open("POST", url, true);
            xhr.setRequestHeader(
                "Content-type",
                "application/x-www-form-urlencoded"
            );

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText); // Parse JSON response
                        //console.log(response);
                        resolve(response);
                    } else {
                        resolve({
                            success: false,
                            message: "An error occurred",
                        });
                    }
                }
            };

            xhr.send(params);
        });
    }

    function updateProgressBar(current, total) {
        const percentage = (current / total) * 100;
        const progressBarLength = Math.round(percentage / 4); // Half the percentage for simplicity
        const progressBar = "#".repeat(progressBarLength);
        document.getElementById(
            "progressbar"
        ).innerHTML = `[${progressBar}] ${Math.round(percentage)}%`;
    }
}



const tagSelector = $("#tagSelector");
const cards = $(".plugin");

console.log("tagSelector");
console.log(tagSelector);

tagSelector.on("change", function () {

    const selectedTag = tagSelector.val();
    console.log(selectedTag);

    cards.each(function () {
        const tags = $(this).data("tags").split(" ");

        if (selectedTag === "all" || tags.includes(selectedTag)) {
            $(this).css("display", "block");
        } else {
            $(this).css("display", "none");
        }
    });
});


if (window.CodeMirror) {
    $(".codeeditor").each(function () {
        let editor = CodeMirror.fromTextArea(this, {
            lineNumbers: true,
            theme: "dracula",
            mode: "javascript",
            height: 300,
        });

        // Add a keydown event listener to the CodeMirror instance
        editor.on("keydown", function (cm, event) {
            if (event.key === "Enter") {
                event.preventDefault(); // Prevent the default behavior (inserting a single line break)
                cm.replaceSelection("\n"); // Insert three new lines
            }
        });
    });
}


/// in admin

document.addEventListener('DOMContentLoaded', function() {
// Initialize Select2
$('#type').select2();
$('#users select').select2();

const typeSelect = $('#type');
const usersDiv = $('#users');

const toggleUsersDiv = () => {
    if (typeSelect.val() == '2') {
        usersDiv.show();
    } else {
        usersDiv.hide();
    }
};

// Initial check on page load
toggleUsersDiv();

// Add event listener for change event using Select2
typeSelect.on('change', toggleUsersDiv);
});

$(function() {


if ($('.colorPicker').length) {
    $('.colorPicker').izoColorPicker({
        buttonApplyTitle: 'Apply',
        buttonCancelTitle: 'Cancel',
        myColors: '#ddd',
        onApply: (color) => {
            console.log('Selected color: ' + color)
        },
        onSave: (color, colors) => {
            console.log('Saved color: ' + color)
            console.log('Saved color list: ' + colors.join())
        },
        onRemove: (color, colors) => {
            console.log('Removed color: ' + color)
            console.log('Saved color list: ' + colors.join())
        }
    });
}

})

const goToValue = document.getElementById('goToValue');

if (goToValue) {
    goToValue.addEventListener('change', function() {
        var selectedValue = this.value;
        if (selectedValue) {
            window.location.href = selectedValue; // Redirect to the selected route
        }
    });
}


const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

//admin

document.querySelectorAll('.shortcode-item').forEach(function(item) {
    // When clicking the list item or the copy button
    item.addEventListener('click', function() {
        const shortcode = item.getAttribute('data-shortcode');

        // Create a temporary input element to copy the shortcode
        const tempInput = document.createElement('input');
        document.body.appendChild(tempInput);
        tempInput.value = shortcode;
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);

        const copyBtn = item.querySelector('.copy-btn i');
        copyBtn.classList.remove('fa-copy');
        copyBtn.classList.add('fa-check');

        // Revert back to the copy icon after 2 seconds
        setTimeout(function() {
            copyBtn.classList.remove('fa-check');
            copyBtn.classList.add('fa-copy');
        }, 500); // 2 seconds delay
    });

    // Add click event listener on the copy button
    item.querySelector('.copy-btn').addEventListener('click', function(e) {
        e.stopPropagation(); // Prevent the event from bubbling to the <li> element
        item.click();
    });

});



$(".offcanvasScrolling").on("click", function () {

    var _token =  $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: BASE_URL + "/broadcasts",
        type: "POST",
        data: {
            _token: _token,
        },
        success: function (data) {
            $('#broadcast_badge').remove();
        },
        error: function (data) {
        },
    });
});


document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', function() {
            let jobId = this.getAttribute('data-job-id');

            // Show modal & set loading state
            // Show modal & set loading state
            document.getElementById('modal-title').innerText = `Job Results (ID: ${jobId})`;
            document.getElementById('modal-body').innerText = 'Loading...';
            let customModal = new bootstrap.Modal(document.getElementById('customModal'));
            customModal.show();


            // Fetch data using AJAX
            fetch(BASE_URL + `/settings/job-results/${jobId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal-body').innerText = JSON.stringify(
                        data, null, 4);
                })
                .catch(error => {
                    document.getElementById('modal-body').innerText =
                        'Failed to load data.';
                });
        });
    });
});

function closeModal() {
    document.getElementById('custom-modal').style.display = 'none';
}




function setupSelectAll(selectAllId, checkboxClass, totalSelectedId) {
    const selectAllCheckbox = document.getElementById(selectAllId);
    const checkboxes = document.querySelectorAll(`.${checkboxClass}:not([disabled])`);
    const totalSelected = document.getElementById(totalSelectedId);

    function updateTotal() {
        const selectedCount = [...checkboxes].filter(chk => chk.checked).length;
        totalSelected.textContent = `(${selectedCount} Selected)`;
        selectAllCheckbox.checked = selectedCount === checkboxes.length;
    }

    selectAllCheckbox.addEventListener("change", function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        updateTotal();
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", updateTotal);
    });

    updateTotal();
}

setupSelectAll("selectAllLanguages", "language-checkbox", "totalLanguagesSelected");
setupSelectAll("selectAllOptions", "option-checkbox", "totalOptionsSelected");



})(jQuery);
