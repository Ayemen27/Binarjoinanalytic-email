$(document).ready(function() {


    new ClipboardJS('.btn-copy');



    $(".btn-copy").click(function() {
        let copyIcon = document.querySelector("#copyIcon");
        let inputField = document.querySelector("#mainEmail");

        if (inputField) {
            // Focus and select the input field
            inputField.focus();
            inputField.select();
        }

        if (copyIcon) {
            copyIcon.classList.remove("far", "fa-clone");
            copyIcon.classList.add("fas", "fa-check");

            setTimeout(function() {
                copyIcon.classList.remove("fas", "fa-check");
                copyIcon.classList.add("far", "fa-clone");
            }, 500);
        }
    });



    AOS.init({disable: 'mobile'});


    var email = $("#mainEmail"),
        email_token = $("#email_token"),
        //history_total = "getFeatureValue('history', true) ",
        history_total = 0
    _token = $('meta[name="csrf-token"]').attr('content');
    window.data = localStorage.getItem('emails');
    window.myLanding;
    window.stop = false;
    window.check_is_runing = false;

    const rootStyles = getComputedStyle(document.documentElement);
    const secondaryColor = rootStyles.getPropertyValue('--secondary_color').trim();


    transferCompletedAudio = new Audio(BASE_PATH + "assets/audio/notification.mp3");

    $("#refresh").click(function(e) {
        e.preventDefault(); // Prevent the default link behavior
        location.reload(); // Reload the page
    });

    const iframe = document.getElementById('myContent');

    if (iframe) {
        iframe.addEventListener('load', function() {
            // Function to set iframe height to match its content height
            function setIframeHeight() {
                const iframeDocument = iframe.contentDocument || iframe.contentWindow.document;
                if (iframeDocument && iframeDocument.body) {
                    // Ensure styles within iframe do not add unnecessary scroll
                    iframeDocument.body.style.margin = '0';
                    iframeDocument.body.style.padding = '0';
                    iframe.style.height = (iframeDocument.documentElement.scrollHeight + 5) + 'px';

                }
            }

            // Call the function initially
            setIframeHeight();

            setTimeout(setIframeHeight, 500); // delay in milliseconds

            // Optionally, recheck and set the height periodically if the content might change dynamically
            //setInterval(setIframeHeight, 500); // Adjust the interval as needed
        });

        var message_id = $('#message_id').val();
        var is_seen = $('#is_seen').val();

        $.ajax({
            url: BASE_PATH + 'is-seen',
            type: "POST",
            data: {
                _token: _token,
                is_seen: is_seen,
                message_id: message_id,
            },
            success: function(data) {
                //alert('is_seen done');
            },
            error: function(data) {
                //alert('is_seen error');
                console.log(data);
            },
        });

    }


    $("#save_message").click(function() {
        var message_id = $('#message_id').val();
        $("#save_message").prop('disabled', true);
        $("#save_message").attr("title", please_wait);
        $("#save_message").attr("data-bs-original-title", please_wait);
        $("#save_message").html(
            '<i class="fa-solid fa-circle-notch fa-spin"></i>');
        $.ajax({
            url: BASE_PATH + 'messages',
            type: "POST",
            data: {
                _token: _token,
                message_id: message_id,
            },
            success: function(data) {

                $("#save_message").prop('disabled', false);

                if (data.favorited) {
                    $("#save_message").html(
                        '<i class="fa-solid fa-heart star-icon-color"></i>');
                    $("#save_message").attr("title", favorited);
                    $("#save_message").attr("data-bs-original-title", favorited);
                } else {
                    $("#save_message").html('<i class="fa-regular fa-heart"></i>');
                    $("#save_message").attr("title", not_favorited);
                    $("#save_message").attr("data-bs-original-title", not_favorited);
                }

                flasher.success(data.message, flasher_success);

            },
            error: function(data) {
                $("#save_message").prop('disabled', false);
                $("#save_message").html('<i class="fa-regular fa-heart"></i>');
                $("#save_message").attr("title", not_favorited);
                $("#save_message").attr("data-bs-original-title", not_favorited);
                var res = parseData(data);
                var message = res.responseJSON.message;
                flasher.error(message, flasher_error);

            },
        });
    });



    function landing_email(pass = false) {

        if (email.val().length == 0 || email.val() == landing || pass) {
            setButtonState(true);
            email.val(landing);
            window.myLanding = setInterval(function() {
                var val = "";
                switch (email.val()) {
                    case landing:
                        val = landing + ".";
                        break;

                    case landing + ".":
                        val = landing + "..";
                        break;

                    case landing + "..":
                        val = landing + "...";
                        break;
                    case landing + "...":
                        val = landing;
                        break;
                }
                email.val(val);
            }, 100);
        }
    }


    // Enable or disable buttons based on the "disabled" parameter
    function setButtonState(disabled = false) {
        var buttons = $(".disable-button");

        if (disabled) {
            buttons.prop('disabled', true);
        } else {
            setTimeout(function() {
                buttons.prop('disabled', false);
            }, 500); // delay in milliseconds
        }

    }

    // Parse data into an object, handling possible errors
    function parseData(data) {
        if (!data) return {};
        try {
            return typeof data === 'object' ? data : JSON.parse(data);
        } catch (e) {
            console.error('Unable to parse data:', e);
            return {};
        }
    }




    function success_message(data, message = null)  {

        if (message != null) {
            //console.log(message);
            flasher.success(message, flasher_success);
        }

        clearInterval(window.myLanding);

        var res = parseData(data);

        window.check_is_runing = true;
        email.val(res.mailbox);
        email_token.val(res.email_token);
        ////console.log(res.mailbox);

        //updateEmailHistory(res.mailbox);

        localStorage.setItem('histories', JSON.stringify(res.histories));
        let histories = JSON.parse(localStorage.getItem("histories"));
        convertLocalStorageEmailsToHtmlHistory(histories, true);

        window.stop = false;

        setButtonState();

        return res;

    }

    function messages() {

        window.check_is_runing = false;

        // Define the CancelToken source
        //const cancelSource = axios.CancelToken.source();

        const controller = new AbortController();

        if (window.stop) {
            return false;
        }

        let captcha = $('#captcha-response').val();

        landing_email();

        axios.post(BASE_PATH + 'get_messages', {
                _token: _token,
                captcha: captcha
            }, {
                signal: controller.signal
            })
            .then(function(response) {

                Progress.configure({
                    color: [secondaryColor]
                });
                Progress.configure({
                    speed: 1
                });
                Progress.complete();


                //console.log(response);
                var res = success_message(response.data);


                if (res.messages.length == 0) {
                    $(".mailbox").addClass("empty");
                    $("#mailbox").html("");
                } else {
                    $(".mailbox").removeClass("empty");
                    $("#mailbox").html("");
                }

                var count_notification = 0;

                _.forEach(res.messages, function(item, index, object) {

                    if (saveIdToLocalStorage(item.id)) {
                        count_notification++;
                    }

                    var is_seen = "";
                    if (!item.is_seen) {
                        is_seen = '<span class="is_seen_message">' + new_message +
                            '</span>';
                    }

                    $("#mailbox").prepend(`
                            <div class="mailbox-item mailbox-custom-link" data-url="${BASE_PATH}view/${item.id}">
                                    <!-- Start Mailbox Item Col -->
                                    <div class="mailbox-item-col">
                                        <a href="${BASE_PATH}view/${item.id}">
                                        <p>${item.from}</p>
                                        <p class="small">${item.from_email}</p>
                                        </a>
                                    </div>
                                    <!-- End Mailbox Item Col -->
                                    <!-- Start Mailbox Item Col -->
                                    <div class="mailbox-item-col">
                                        <a href="${BASE_PATH}view/${item.id}" class="link link-primary">${item.subject}</a>
                                    </div>
                                    <!-- End Mailbox Item Col -->
                                    <!-- Start Mailbox Item Col -->
                                    <div class="mailbox-item-col small">
                                        <time>${moment(item.receivedAt).format("hh:mm A DD/MM/YYYY ")}</time>
                                    </div>
                                    <!-- End Mailbox Item Col -->
                                    ${is_seen}
                                </div>
                            `);

                });

                if (count_notification > 0) {
                    transferCompletedAudio.play().catch(function(error) {
                        console.error('Audio playback failed:', error);
                    });
                    //alert(count_notification);
                    flashTitle("New Message Received!");
                }


            })
            .catch(function(error) {
                if (axios.isCancel(error)) {
                    console.log("Axios request was cancelled by user");
                } else {

                    console.log("Error occurred: ", error);
                    //console.log("Error occurred: ", error.response.data.message);
                    //window.check_is_runing = true;
                    email.val(error.response.data.message);
                    clearInterval(window.myLanding);

                }
            });

        // Use the cancel token to cancel the request on click
        $(".kill").click(function() {
            //console.log("Use the cancel token to cancel the request on click");
            controller.abort();
            window.check_is_runing = true;
        });
    }



    let clickCount = 0;
    let resetClicksTimeout;


    $("#delete").click(function() {

        // Increment click count
        clickCount++;

        // If this is the first click, start the timer to reset the count
        if (!resetClicksTimeout) {
            resetClicksTimeout = setTimeout(function() {
                clickCount = 0;
                resetClicksTimeout = null;
            }, 60000); // Reset after 1 second
        }

        // If clicks exceed 2 within 1 second, prevent further actions and show alert
        if (clickCount > 2) {
            flasher.error(limit_error, flasher_error);


            //alert("You can only click up to 2 times per second.");
            return; // Prevent further execution if more than 2 clicks in 1 second
        }


        window.stop = true;
        landing_email(true);

        axios.post(BASE_PATH + 'delete', {
                _token: _token
            })
            .then(function(response) {
                //console.log(response);
                success_message(response.data, email_deleted_message);
                call_messsage();
            })
            .catch(function(error) {
                //alert("delete" + error);
                window.stop = false;
                setButtonState();
            });

    });



    $('body').on('click', '.mailbox-custom-link', function(event) {
        // Get the URL from the data-url attribute
        var url = $(this).attr("data-url");

        // Navigate to the URL when the div is clicked
        window.location.href = url;
    });












    $('body').on('click', '.history_choose_email', function(event) {
        const id = $(this).attr('for').split('@');
        const name = id[0];
        const domain = id[1];
        const old_email = $("#mainEmail").val();

        window.stop = true;
        landing_email(true);

        // clear box function

        axios.post(BASE_PATH + 'change_email', {
                _token: _token,
                name: name,
                domain: domain,
            })
            .then((response) => {
                $('#history').modal('toggle');
                success_message(response.data, email_changed_message);
                call_messsage();
            })
            .catch((error) => {
                clearInterval(window.myLanding);
                window.stop = false;
                setButtonState();
                email.val(old_email);

                var errors = error.response.data.errors

                for (const field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        const errorMessages = errors[field];
                        errorMessages.forEach(errorMessage => {
                            console.log(errorMessage);
                            flasher.error(errorMessage, flasher_error);
                        });
                    }
                }


            });
    });


    $("#change_email").click(function() {

        var name = $("#random_code_input").val();
        var domain = $("#name_domain").val();
        var old_email = $("#mainEmail").val();

        window.stop = true;

        landing_email(true);

        // clear box function

        axios.post(BASE_PATH + 'change', {
                _token: _token,
                name: name,
                domain: domain,
            })
            .then(function(response) {

                //console.log(response);

                $('#changeEmail').modal('toggle');
                $("#random_code_input").val('');

                success_message(response.data, email_changed_message);
                call_messsage();

            })
            .catch(function(error) {


                var errors = error.response.data.errors

                for (const field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        const errorMessages = errors[field];
                        errorMessages.forEach(errorMessage => {
                            console.log(errorMessage);
                            flasher.error(errorMessage, flasher_error);
                        });
                    }
                }

                clearInterval(window.myLanding);
                window.stop = false;
                setButtonState();
                email.val(old_email);

            });

    });


    $('body').on('click', '#delete_history', function(event) {

        $('#delete_history').prop('disabled', true);
        window.stop = true;

        const emailsHistory = JSON.parse(localStorage.getItem("histories"));
        const checkedEmails = $('.all_emails_checkbox:checkbox:checked').map(function() {

            var delete_email = $(this).val();
            if (isValidEmail(delete_email)) {
                if (delete_email != email.val()) {
                    return delete_email;
                }
            }

        }).get();

        if (checkedEmails.length == 0) {
            $('.all_emails_checkbox').prop('checked', false);
        }


        //console.log("checkedEmails " + checkedEmails);
        //console.log("checkedEmails " + checkedEmails.length);

        axios.post(BASE_PATH + 'delete_emails', {
                _token: _token,
                emails: checkedEmails,
            })
            .then((response) => {
                $('#delete_history').prop('disabled', false);
                window.stop = false;
                if (response.data.success) {
                    //console.log("response " + response);
                    const updatedEmailsHistory = emailsHistory.filter(function(emailObj) {
                        return !checkedEmails.includes(emailObj.email);
                    });

                    localStorage.setItem('histories', JSON.stringify(updatedEmailsHistory));
                    convertLocalStorageEmailsToHtmlHistory(updatedEmailsHistory);
                    $("#search_history").val('');
                }
            })
            .catch((error) => {
                window.stop = false;
                $('#delete_history').prop('disabled', false);
                console.log("error " + error);
            });
    });




    function call_messsage() {
        $(".mailbox").addClass("empty");
        $("#mailbox").html("");
        messages();
    }



    function saveIdToLocalStorage(id) {
        try {
            if (typeof(Storage) !== "undefined") {
                let idsArray = JSON.parse(localStorage.getItem("idArray")) || [];
                if (!idsArray.includes(id)) {
                    idsArray.push(id);
                    localStorage.setItem("idArray", JSON.stringify(idsArray));
                    return true;
                }
                return false;
            } else {
                return false;
            }
        } catch (error) {
            console.error("An error occurred while saving to Local Storage: " + error.message);
            return false;
        }
    }



    $("#show_history").click(function() {
        $("#search_history").val('');
        // Pass the cached email history to the rendering function
        let histories = JSON.parse(localStorage.getItem("histories"));
        convertLocalStorageEmailsToHtmlHistory(histories);

        $('#history').modal('show');
    });


    function convertLocalStorageEmailsToHtmlHistory(emailsData, check_search = false) {


        if (check_search) {
            if ($("#search_history").val() != "" || $(".all_emails_checkbox ").is(":checked")) {
                return false;
            }
        }


        const getMonthName = item => moment(item.time, 'YYYY-MM-DD hh:mm:ss').format('YYYY-MM-DD');
        const emailsGroupedByMonth = _.groupBy(_.orderBy(emailsData, 'time', 'asc'), getMonthName);
        //console.log(emailsGroupedByMonth);


        // Group emails by month using Moment.js library

        // Clear contents of 'mail-history' element
        const $mailHistory = $('#mail-history');
        $mailHistory.html('');

        // Generate HTML for each month's group of emails
        let dayIndex = 1;
        if (emailsData.length === 0) {
            // Display message if history is empty
            $mailHistory.html(`
                                <div class="mail-history-empty">
                                    <h5>The email history is empty.</h5>
                                </div>
                                `);
        } else {
            _.forEach(emailsGroupedByMonth, function(emailsInMonth, monthKey) {

                // Create div element for each day's emails
                const $mailHistoryDay = $(`
                        <div class="mail-history-day">
                        <div class="mail-history-day-header text-start">
                            <div class="form-check">
                            <input class="form-check-input all_emails_checkbox check_all" id="check${dayIndex}" type="checkbox" />
                            </div>
                            <p class="mb-0">${moment(monthKey).format('dddd - YYYY/MM/DD')}</p>
                        </div>
                        <div class="mail-history-day-body" id="mail-history-day-body${dayIndex}">
                        </div>
                        </div>
                    `);

                // Append day's div element to 'mail-history' element
                $mailHistory.prepend($mailHistoryDay);

                // Generate HTML for each email in the day's group
                _.forEach(emailsInMonth, function(emailData) {

                    // Create button label based on email status
                    let buttonLabel;
                    //console.log(emailData.current);
                    if (emailData.current === true) {
                        //console.log('Current');
                        buttonLabel = '<label class="ms-auto label_bluer">' + current_message +
                            '</label>';
                    } else {
                        buttonLabel =
                            `<label class="kill btn btn-primary btn-sm history_choose_email" for="${emailData.email}">${choose_message}</label>`;
                    }

                    // Create div element for the email
                    const $mailHistoryItem = $(`
                        <div class="mail-history-item">
                            <div class="form-check">
                            <input class="form-check-input all_emails_checkbox check${dayIndex}" value="${emailData.email}" type="checkbox" />
                            </div>
                            <span>${moment(emailData.time).format('hh:mm A')}</span>
                            <label class="email">${emailData.email}</label>
                            ${buttonLabel}
                        </div>
                        `);

                    // Append email div element to the day's body
                    $(`#mail-history-day-body${dayIndex}`).prepend($mailHistoryItem);
                });

                dayIndex++;
            });
        }

        // Update email count and message

        let histories = JSON.parse(localStorage.getItem("histories")).length;
        $('#history-count').html(histories);
        //$('#history-total').html(`(${emailsCount} ${emailsCount === 1 ? 'email' : 'emails'})`);
    }


    $("#search_history").keyup(function() {
        var search_history = $(this).val();

        const monthName = item => moment(item.time, 'YYYY-MM-DD hh:mm:ss').format(
            'YYYY-MM-DD');

        var results = _.filter(JSON.parse(localStorage.getItem("histories")), function(obj) {
            return obj.email.indexOf(search_history) !== -1;
        });


        if (results.length == 0) {

            $('#mail-history').html(`
                <div class="mail-history-empty">
                    <h5>  ${search_history_message} "${search_history}"</h5>
                </div>
            `);

        } else {

            convertLocalStorageEmailsToHtmlHistory(results);

        };
    });



    $('body').on('click', '.check_all', function(event) {
        const id = $(this).attr('id');
        $('.' + id).prop('checked', this.checked);
    });

    $("#show_qr_code").click(function() {
        var emailVal = email_token.val();
        if (emailVal.length == 0) {
            ////console.log('Please enter a valid email address.');
            return;
        }
        $("#qrcode").html('');
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: BASE_PATH + "go-to-email/" + emailVal,
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
        $('#qrCode').modal('show');
    });


    function isValidEmail(email) {
        // Regular expression for email validation
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }


    function genCode(charsLength = 10) {
        var chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        var code = "";
        for (var i = 1; i <= charsLength; i++) {
            var randomNumber = Math.floor(Math.random() * chars.length);
            code += chars.substring(randomNumber, randomNumber + 1);
        }

        return code;
    }


    window.myCallback = function setResponse(response) {
        document.getElementById('captcha-response').value = response;
        window.check_recaptcha = true;
    }


    function check_messgaes() {

        if (window.check_is_runing && window.check_recaptcha) {
            messages();
        }
    }


    function recaptcha_works() {
        if (window.check_recaptcha) {
            messages();
            clearInterval(window.set_recaptch);
        } else {
            grecaptcha.execute();
        }
    }

    let mailbox_email = document.querySelector("#mainEmail");

    if (mailbox_email) {
        try {
            setInterval(check_messgaes, 1000 * fetch_time);
            window.set_recaptch = setInterval(recaptcha_works, 1000);
        } catch (error) {}
    }

    $("#random_code").click(function() {
        $("#random_code_input").val(genCode());
    })


    let originalTitle = document.title;

    function flashTitle(newTitle) {
        let flash = true;
        let interval = setInterval(function() {
            document.title = flash ? newTitle : originalTitle;
            flash = !flash;
        }, 1000);

        // Stop flashing after a few seconds (you can adjust the duration)
        setTimeout(function() {
            clearInterval(interval);
            document.title = originalTitle;
        }, 1000000);
    }

});
