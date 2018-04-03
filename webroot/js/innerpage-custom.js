var base_url = window.location.origin+'/';
$(document).ready(function() {
	/** Subscriber page script Start **/
	//var currency_id  = 1;
    var DefaultCurrencyValue = $('input[name=RadioCurrency]:checked').val();
    subscriptionPrice (DefaultCurrencyValue);
	$('.rdoCurrency').on('change', function () {
        var value = $(this).val();
        subscriptionPrice (value);
	});

	function changeCurrency(id) {
		currency_id = id;
		$(".currencySymbol").hide();
		$(".currencyAmount").hide();	
		$(".currency_"+id).show();
		$("#hdnCurrencyCode").val($("#hdnCurrencyCode_"+id).val());		
		$("#hdnPrice").val(parseFloat($.trim($("#hdnspnPrice_"+id).html())));		
	}

	$('#user-id').on('change', function () {
		var selectedUser = $(this).val();
		if (selectedUser.indexOf('_') > 0) {
			$('#user-type').val('anotherPerson');
		} else {
			$('#user-type').val('user');
		}
	});
	/** Subscriber page script End **/


	/** Dashboard page script Start **/
	/**
     * Parametes to initialize accordion on page load
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Dec. 06, 2016
     */
    var accordionOpts = {
                    collapsible: "true",
                    active: "false",
                    autoHeight: "false"
                };

    $('#accordion').accordion(accordionOpts);
    /** Dashboard page script END **/

    /** Edit Profile page script start **/
    $('.accordian-content').hide(); // on page load hide all expandable divs
    $('.accordian-content:first').show();
    //$('#user_dob_edit').datepicker({autoclose: true});
    $('#user_dob_edit').datepicker({
        autoclose: true, // Close datepicker automatically after date selection
        endDate: '+0d', //'-1y', // maxDate will be before 1year
        startView:2, // Show year, then month and in the last day
        forceParse: false,
        format: 'dd/mm/yyyy'
    });
    /** Edit Profile page script end **/

});

function subscriptionPrice (value) {
    if (value != '' && typeof value != "undefined") {
        if (value.indexOf("-") >= 0) {
            value = value.split('-');
        }
        //alert (value);
        var priceWithCurrencySign = value[0];
        var currencyCode = value[1];
        //value = value[0].split(' ');
        if (priceWithCurrencySign.indexOf("kr.") >= 0) {
            var currencySymbol = value[0].slice(0, 3);
            var selectedPrice = value[0].slice(3, value[0].length);
            //alert (currencySymbol+' => '+selectedPrice);
        } else if (priceWithCurrencySign.indexOf("kr") >= 0) {
            var currencySymbol = value[0].slice(0, 2);
            var selectedPrice = value[0].slice(2, value[0].length);
            //alert (currencySymbol+' => '+selectedPrice);
        } else {
            var currencySymbol = value[0].slice(0, 1);
            var selectedPrice = value[0].slice(1, value[0].length);
            //alert (currencySymbol+' => '+selectedPrice);
        }
        //alert (value+' => '+selectedPrice);
        //$('#spnSymbol_3').html(value[0]+'&nbsp;');
        
        //$('#spnSymbol_3').html(currencySymbol); //SF
        
        //$('#spnPrice_3').html(value[1]);
        
        //$('#spnPrice_3').html(selectedPrice); //SF

         //update on 16 March 2017
        $('#symbol_price').html(currencySymbol+selectedPrice);

        //$("#hdnPrice").val(value[1]);
        $("#hdnPrice").val(selectedPrice);
        $('#hdnCurrencyCode').val(currencyCode);
    }
}

/** Dashboard page function starts **/
/**
 * Load important transitions on page load
 * Created By : Kingslay <kingslay@123789.org>
 * Created Date : Nov. 28, 2016
 * Modified Date : Nov. 30, 2016
 */
function getImportantTransitions (url) {
	$.ajax({
        async : false,
        type:"POST",
        url: url,
        /*complete: function(){
            $('.fullpageloader').css ('display', 'none');
            //$('.myAstroPage').css ('display', 'block');
        },*/
        success: function(data) {
            $('#transitOfSelectedDate').html(data);
        }
    });
}

/**
     * Custom accordion functionality
     * Created By : Krishna Gupta
     * Created Date : Dec. 22, 2016
     */
    $(document).on('click', '.ui-accordion-header', function (e) {
        var currentElementId = $(this).attr('id');
        var currentElementAriaControls = $(this).attr('aria-controls');
        $('.geth3content').each( function () {
            var h3id = $(this).attr('id');
            var h3Arial = $(this).attr('aria-controls');
            if ((h3id == currentElementId) && (h3Arial == currentElementAriaControls) ) {
                $(this).addClass('ui-state-active');
            } else {
                $(this).removeClass('ui-state-active');
            }
        });
        $('.getDivContent').each( function () {
            var getDivContentId = $(this).attr('id');
            var getDivContentAriaLabelledBy = $(this).attr('aria-labelledby');
            if ((getDivContentId==currentElementAriaControls) && (getDivContentAriaLabelledBy==currentElementId)) {
                $(this).addClass('ui-accordion-content-active');
                //$(this).addClass('firstChild').slideDown("slow");
            } else {
                $(this).hide();
                $(this).removeClass('firstChild');
                $(this).removeClass('ui-accordion-content-active');
            }
        });
    });


/**
 * Show div content on month selection for calendar
 * Created By : Kingslay <kingslay@123789.org>
 * Created Date : Dec. 12, 2016
 * Modified Date : Dec. 12, 2016
 */
function tabbed_calendar (month) {
    
    var maxllimit = $('li.previousMonth').attr('myAttr');
    if (maxllimit == '') {
        maxllimit = 14;
    } else {
        maxllimit = parseInt(maxllimit)+1;
    }
    $('li.tablinks').hide();
    $('.tabify04-content').hide();
    $('div.tabify04-content[ref='+month+']').show();
    $('li.tablinks').removeClass('active');
    $('li.tablinks[ref='+month+']').addClass('active');
    $('li.tablinks[ref='+month+']').css ('display', '');
    //$('li#tabbedMonth_'+month).css ('display', '');
    /*var next = parseInt(refVal+1);
    var previous = parseInt(refVal-1);*/
    if ((month-1) > 0) {
        $('li.previousMonth').attr('ref', (month-1));
        $('li.previousMonth').attr('onclick', "tabbed_calendar("+(month-1)+", "+maxllimit+")");
        //$('li.previousMonth').show();
        //$('li.previousMonth').css('visibility', 'visible');
        $('li.previousMonth').removeClass('visibilityHidden');
    } else {
        $('li.previousMonth').removeAttr('ref');
        $('li.previousMonth').removeAttr('onclick');
        //$('li.previousMonth').hide();
        //$('li.previousMonth').css('visibility', 'hidden');
        $('li.previousMonth').addClass('visibilityHidden');
        $('li.nextMonth').attr('ref', (month+1, maxllimit));
    }

    if ((month+1) < maxllimit) {
        $('li.nextMonth').attr('ref', (month+1));
        $('li.nextMonth').attr('onclick', "tabbed_calendar("+(month+1)+", "+maxllimit+")");
        //$('li.nextMonth').show();
        //$('li.nextMonth').css('visibility', 'visible');
        $('li.nextMonth').removeClass('visibilityHidden');
    } else {
        $('li.nextMonth').removeAttr('ref');
        $('li.nextMonth').removeAttr('onclick');
        //$('li.nextMonth').hide();
        //$('li.nextMonth').css('visibility', 'hidden');
        $('li.nextMonth').addClass('visibilityHidden');
    }
}

/**
 * Get current date prediction (influences) on page load
 * Created By : Kingslay <kingslay@123789.org>
 * Created Date : Dec. 06, 2016
 */
function getDailyPredictionData (url, currentDateClendar, status/*status=''*/) { // currentDateClendar is optional parameter(d-m-Y)
	$.ajax({
        async : false,
        type:"POST",
        url: url,
        success: function(data) {
            data = data.slice(0,-1); // removing unwanted data
            // Accordion parameters
            var accordionOpts = {
                    collapsible: "true",
                    active: "false",
                    autoHeight: "false"
                };
            $("#accordion").accordion("destroy"); // Destroying old accordion
            $('#accordion').html(data);

            // Highlighted selected date 
            if (currentDateClendar != '') {
                $('a[class="lnkDaily"]').each( function () {
                    var dateOnCalendarLink = $(this).attr('id');
                    if (dateOnCalendarLink == currentDateClendar) {
                        $(this).parent('td').addClass('calendarActiveClass');
                    } else {
                        $(this).parent('td').removeClass('calendarActiveClass');
                    }
                });
            }
            $('#accordion').accordion(accordionOpts); // Reinitialized accordion
            // To remove arrow sign from paragraph (no transitions available)
            $('p.ui-accordion-header-collapsed').css('cursor', 'text');
            $('p.ui-accordion-header-collapsed').removeClass('ui-accordion-header-collapsed');
            if (status != '') {
                //$('#accordion').focus().slideDown("slow");
                $('html, body').animate({
                    scrollTop: $("#accordion").offset().top-170
                }, 'slow');
            }
            //$(window).scrollTop($('.firstChild').offset().top); //
        }
    });
}


/**
 * Used to ajax request on change user
 * Created By : Kingslay <kingslay@123789.org>
 * Created Date : Nov. 22, 2016
 * Modified Date : Nov. 24, 2016
 */
function getSelectedUserDataFromDropdownList (url, selectedUser) {
	$.ajax({
        type:"POST",
        url: url,
        async: false,
        dataType: 'json',
        success: function(data) {
            $('#user_dob').text(data.dob);
            $('#user_birth_time').text(data.time);
            $('#user_birth_city').text(data.cityname+', '+data.countryname);
            $('#user_birth_timezone').text(data.zone);
            $('#user_birth_summertime').text(data.type);
            $('#my-personal-horoscope-wheel-link').attr('href', data.wheelURL);
            $('#expand-horoscope-wheel').attr('href', data.wheelURL);
            var basePath = window.location.origin;
            //$('#my-personal-horoscope-wheel').attr('src', data.wheelImageSRC);

            if(selectedUser.indexOf('_') != -1) {
                var splittedData = selectedUser.split('_');
                var userId = splittedData[1];
                var imagepath = basePath+"/user-personal-horoscope/anotherPerson_"+userId+".onlywheel.jpg";
                //chmod 777 "/var/www/html/webroot/imagepath/user-personal-horoscope/anotherPerson_"+userId+".onlywheel.jpg";
                $('#my-personal-horoscope-wheel-link').html('<div id="myPersonalHorospcopeImage"><img src="'+imagepath+'" title="Personal Horoscope wheel" alt="Personal Horoscope wheel" id="my-personal-horoscope-wheel"></div>');
                $('.click-expand').css('display', 'block');
            } else {
                $('#my-personal-horoscope-wheel-link').html(selectedUser+".onlywheel.jpg");
                var imagepath = basePath+"/user-personal-horoscope/"+selectedUser+".onlywheel.jpg";
                //chmod 777 imagepath;
                $('#my-personal-horoscope-wheel-link').html('<div id="myPersonalHorospcopeImage"><img src="'+imagepath+'" title="Personal Horoscope wheel" alt="Personal Horoscope wheel" id="my-personal-horoscope-wheel"></div>');
                $('.click-expand').css('display', 'block');
            }

            /*var wheelImageSource = data.wheelImageSRC;
            if (wheelImageSource.indexOf("personal-wheel.jpeg") >= 0) {
                if ($('#myPersonalHorospcopeImage').length) {
                    $('#myPersonalHorospcopeImage').attr('id', 'default_wheel');
                }
            } else {
                if (wheelImageIdOnPageLoad == 'myPersonalHorospcopeImage') {
                    if ($('#default_wheel').length) {
                        $('#default_wheel').attr('id', 'myPersonalHorospcopeImage');
                    }
                } else {
                    $('#default_wheel').attr('id', 'myPersonalHorospcopeImage');
                    $('#default_wheel').attr('src', data.wheelImageSRC);
                }
            }*/
        }
    });
}

/**
 * Show tooltip data value on hover on icons in subscription calendar
 * Created By : Kingslay <kingslay@123789.org>
 * Created Date : Dec. 14, 2016
 * Modified Date : Dec. 18, 2016
 */
function showToolTipOnCalendarIcons (icon, title, TransitTitle, date) {// Y-m-d
    //alert (icon); alert (title); alert (TransitTitle); alert (date);
    $('.calendarIconTooltip').empty ();
    $('.calendarIconTooltip').hide ();
    var date = date.split('-');
    if (date[2] < 10) {
        date[2] = date[2].substring(1);;
    }
    if (date[1] < 10) {
        date[1] = date[1].substring(1);;
    }
    date = date[1]+'-'+date[2]+'-'+date[0];
    var tooltip = "<div class='calendar_content'><span><img src='"+icon+"' alt='tooltip' width='70' height='70' class='imgCalenderIcon' /></span><span>"+title+"</span><div class='tooltipTitle'>"+TransitTitle+"</div></div>";
    $('div#'+date).html (tooltip);
    $('div#'+date).show();
}

/**
 * Hide tooltip data value on hover-out on icons in subscription calendar
 * Created By : Kingslay <kingslay@123789.org>
 * Created Date : Dec. 15, 2016
 */
function hideToolTipOnCalendarIcons () {
    $('.calendarIconTooltip').empty();
}

    


/** Dashboard page function end **/


/** Edit Profile page function start **/

function showOrHideDivsOnClick (plus, minus, headerindex) {
	$('.statusicon').attr('src', plus); // apply plus sign image on all links
    $('.accordian-content').hide(); // hide all expandable divs
    var selectedHeaderIndex = headerindex; // get headerindex attribute value of selected expandable link
    var selectedHeaderIndexPosition = selectedHeaderIndex.substring(0, selectedHeaderIndex.length - 1);
    var contentindex = selectedHeaderIndexPosition+'c'; // get corresponding div attribute value
    //$(this+'.statusicon').attr('src', minus); // apply minus sign for selected link that shows that this link is expanded
    $('a[headerindex='+selectedHeaderIndex+']').find('img.statusicon').attr('src', minus);
    var aa = $('div.accordian-content[contentindex='+contentindex+']').slideDown("slow").show();
}

function getCitiesListBasedOnSelectedCountry (url) {
	$.ajax({
        async : false,
        type:"POST",
        url: url,
        beforeSend: function(){
            $('#ddBirthCity').html('Loading city');
        },
        success: function(data){
    		$('#ddBirthCity').html(data);
        },
        error: function (data) {
            alert('Some error occured.');
        }
    });
}
/** Edit Profile page function end **/









    function holdSelectedUser (selectedUserOnDash, langg) {
        $.ajax({
            type:"POST",
            url: selectedUserOnDash,
            cache: false,
            success: function(data) {
                //location.href = base_url+"users/dashboard";
                var SelectedLanguage = langg;
                //if (data) {
                    if (SelectedLanguage == 'dk' || SelectedLanguage == 'da') {
                        //var selectedUserDataURL = base_url+"dk/brugere/instrumentbræt";
                        location.href = base_url+"dk/brugere/instrumentbræt";
                    } else {
                        //var selectedUserDataURL = base_url+"users/dashboard";
                        location.href = base_url+"users/dashboard";
                    }
                    //alert(selectedUserDataURL);
                    //$(location).attr('href', selectedUserDataURL);
                //}
                //window.location.href = selectedUserDataURL;

                /*if (language == 'dk' || language == 'da') {
                    var selectedUserDataURL = "<?php //echo Router::url('/', true); ?>dk/brugere/udvalgte-user-id/"+selectedUserId;
                } else {
                    var selectedUserDataURL = "<?php //echo Router::url('/', true); ?>users/selected-user-id/"+selectedUserId;
                }
                // To get selected user data
                setTimeout(function(){
                    getSelectedUserDataFromDropdownList (selectedUserDataURL, selectedUserId);
                },500);*/
                //return false;
                //location.reload(true);
                /*var selectedUserDataURL = "<?php //echo $this->Url->build([ 'controller' => 'users', 'action' => 'selectedUserId']);?>/"+selectedUser;
                getSelectedUserDataFromDropdownList (selectedUserDataURL, selectedUser);*/
            }
        });
    }