
let notice = jQuery('.wpann_notice_container')
let notice_msg = jQuery('.wpann_notice_msg')
let notice_close_btn = jQuery('.wpann_notice_btn_close')

//vars
let dataObj = {
    position: 'top',
    fixed_header_selector: '',
};

//setter
function setData(key, value) {
    dataObj[key] = value
}

// handle cta button click
function handleCTA() {
    jQuery('.wpann_notice_container a').click(function (e) {
        e.preventDefault()
        const url = jQuery(this).attr('href')
        const noticeID = notice.attr('data-id')

        jQuery.ajax({
            url: ajax_object.ajax_url, // Assuming you've created an AJAX object
            type: 'POST',
            data: {
                action: 'wpa_increment_click_count',
                notice_id: noticeID
            },
            success: function (response) {
                window.location.href = url
            },
            error: function (error) {
                console.error('AJAX error:', error);
            }
        });
    })
}
handleCTA()

//check if the notice should visible or not
async function shouldVisibleFilter(user_ip, allowed_countries, allowed_days) {
    const isValidGeoLocation = await geolocationFilter(user_ip, allowed_countries)
    const isValidDay = checkDayRestriction(allowed_days)

    return isValidGeoLocation && isValidDay;
}

function checkDayRestriction(allowed_days) {
    const date = new Date()

    if (allowed_days.find(item => item == '7')) {
        return true;
    }

    if (allowed_days.find(item => item == date.getUTCDay())) {
        return true;
    }

}

//Handle geolocation filter
async function geolocationFilter(ip, country_codes) {
    if (country_codes.length === 0 || country_codes?.find(item => item === 'all')) {
        return true
    }

    //get user's location to apply the filter
    const response = await fetch('https://freeipapi.com/api/json/' + ip)
    const user_location = await response.json();
    if (country_codes?.find(item => item === user_location?.countryCode?.toLowerCase())) {
        return true;
    } else {
        return false;
    }
}

//Handle startup delay
function startUpDelay(startup_delay) {
    if (startup_delay !== '' && !isNaN(startup_delay)) {
        setTimeout(() => {
            notice.css('display', 'flex');
        }, startup_delay)
    } else {
        notice.css('display', 'flex');
    }
}

//Handle auto close
function autoCloseDelay(startup_delay, auto_close_delay) {
    if (auto_close_delay !== '' && !isNaN(auto_close_delay)) {
        let delay = Number(auto_close_delay)
        if (startup_delay !== '' && !isNaN(startup_delay)) {
            delay += Number(startup_delay);
        }
        setTimeout(() => {
            notice.css('display', 'none');
        }, delay)
    }
}

//handle sliding msg with custom siding animation
function handleSlidingMessage(notice_msg_arr, notice_msg_interval) {
    let msg_counter = 1;
    let slider = null;

    function sliderFunc() {
        //reset current timer until animation done
        clearInterval(slider)

        //vars
        let i = 0;
        let status = 'start';

        //animation
        let textMovingAnim = setInterval(() => {
            if (status === 'start') {
                i = i + 2;
                notice_msg.css('top', i + 'px');
                if (i >= notice.height()) {
                    notice_msg.text(notice_msg_arr[msg_counter % notice_msg_arr.length])
                    msg_counter++;
                    status = 'left'
                }
            } else if (status === 'left') {
                i = i - 2;
                notice_msg.css('top', i + 'px');
                if (i === 0) {
                    status = 'start'
                    clearInterval(textMovingAnim)
                    slider = setInterval(sliderFunc, notice_msg_interval)
                }
            }
        }, 1)
        if (msg_counter >= notice_msg_arr.length) {
            msg_counter = 0;
        }
    }
    if (notice_msg_arr.length > 1) {
        slider = setInterval(sliderFunc, notice_msg_interval)
    }
}

// Handle countdown timer
function handleCountDown(deadline) {
    const deadlineEl = jQuery('#deadline')
    if (!deadlineEl.length && !jQuery('#countdown_days').length) {
        return;
    }
    // Set the date we're counting down to
    var countDownDate = new Date(deadline);
    countDownDate.setHours(23);
    countDownDate.setMinutes(59);
    countDownDate.setSeconds(59);
    countDownDate.setMilliseconds(999);
    countDownDate = countDownDate.getTime();

    // Update the count down every 1 second
    var x = setInterval(function () {

        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Output the result in an element with id="demo"
        if (deadlineEl.length) {
            deadlineEl.html(days + "d " + hours + "h " + minutes + "m " + seconds + "s ");
        } else {
            jQuery('#countdown_days').text(days.toString().padStart(2, '0'))
            jQuery('#countdown_hours').text(hours.toString().padStart(2, '0'))
            jQuery('#countdown_minutes').text(minutes.toString().padStart(2, '0'))
            jQuery('#countdown_seconds').text(seconds.toString().padStart(2, '0'))
        }

        // If the count down is over, write some text 
        if (distance < 0) {
            clearInterval(x);
            deadlineEl.html('')
        }
    }, 1000);
}

//fix header overlap issue
function fixHeaderIssue() {
    if (dataObj?.position == 'top' && dataObj?.fixed_header_selector != '') {
        const header_container = jQuery(dataObj?.fixed_header_selector);
        const scrollTopPosition = jQuery(window).scrollTop();
        let notice_container_height = notice.innerHeight();

        if (notice_container_height > 0 && notice.css('display') != 'none') {
            if (header_container.css('position') == 'sticky' || header_container.css('position') == 'fixed') {
                if (scrollTopPosition == 0) {
                    setTimeout(() => {
                        header_container.css('top', (parseInt(notice_container_height) - 1) + 'px');
                    }, 10)
                } else {
                    header_container.css('top', (parseInt(notice_container_height) - 1) + 'px');
                }

                //fix for fixed/sticky header when notice is not sticky
                if (scrollTopPosition <= notice_container_height && notice.css('position') != 'sticky') {
                    header_container.css('top', (parseInt(notice_container_height) - 1 - scrollTopPosition) + 'px');
                } else if (scrollTopPosition > notice_container_height && notice.css('position') != 'sticky') {
                    header_container.css('top', '0px');
                }
            }
        } else {
            header_container.css('top', '0px');
        }
    }
}

//handle show/hide the notice section
function handleNoticeVisibility() {
    if (sessionStorage.getItem('disable_notice') != dataObj?.notice_id && ((jQuery('html').width() > 800) || (jQuery('html').width() < 800 && dataObj?.hide_mobile != 'yes'))) {
        // 
    } else {
        notice.css('display', 'none');
        fixHeaderIssue()
    }
}

//handle close
function handleClose() {
    notice_close_btn?.click(function (e) {
        sessionStorage.setItem('disable_notice', dataObj?.notice_id)
        notice.css('display', 'none');
        fixHeaderIssue()
    })
}

// handle window resize
function handleResize() {
    jQuery(window).resize(function () {
        handleNoticeVisibility()
        fixHeaderIssue();
    })
}

// handle scroll
function handleResize() {
    jQuery(window).scroll(function () {
        fixHeaderIssue();
    })
}
