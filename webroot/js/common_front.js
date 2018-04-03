//var url = 'http://ec2-54-193-51-211.us-west-1.compute.amazonaws.com/';
var url = window.location.origin+'/';

$(document).ready(function(){

// This is used for tab section on home page.

  RESPONSIVEUI.responsiveTabs();

  /* Free horoscope slider    */
 /* Create by Stanfield    */
 /* Created on 28 Feb  */
 /* Last Modified 28 Feb */

$('.freehoroscope_slider').bxSlider({
    slideWidth: 100,
    minSlides: 2,
    maxSlides: 12,
    slideMargin: 0,
    moveSlides:1,
    pager: false,
    nextText: '<img src="'+url+'images/right-arrow.png" height="25" width="25"/>',
    prevText: '<img src="'+url+'images/right-left.png" height="25" width="25"/>',
    onSliderLoad: function(){
        $("#siteslides").css("visibility", "visible");
      }


  });

    /*$("#soft-exit").validationEngine({
        promptPosition:"topRight:-100",
       // scroll : false,
        showOneMessage: true,
        autoHidePrompt: true,
        autoHideDelay: 3000,
        scrollOffset: 250

    });*/

    /* Frontend User Validations*/
    $("#frmRegStepOne").validationEngine({
        promptPosition:"topRight:-100",
        //scroll : false,
        showOneMessage: true,
        autoHidePrompt: true,
        autoHideDelay: 1000,
        scrollOffset: 250
   
    });

    /* Edit profile-user detail Validations*/
    $("#frmUserDetail").validationEngine({
        promptPosition:"topRight:-100",
       // scroll : false,
        showOneMessage: true,
        autoHidePrompt: true,
        autoHideDelay: 3000,
        scrollOffset: 250
    });

    $("#free-mini-report-for-guest-user").validationEngine({
        promptPosition:"topRight:-100",
       // scroll : false,
        showOneMessage: true,
        autoHidePrompt: true,
        autoHideDelay: 5000,
        scrollOffset: 250
    });
    

    /* Edit profile-user birth detail Validations*/
    $("#frmUserBirthDetail").validationEngine({
        promptPosition:"topRight:-100",
        //scroll : false,
        showOneMessage: true,
        autoHidePrompt: true,
        autoHideDelay: 3000,
        scrollOffset: 250
    });

    /* Edit profile-user birth detail Validations*/
    $("#formCalendarSubscription").validationEngine({
        promptPosition:"topRight:0",
        //scroll : false,
        showOneMessage: true,
        autoHidePrompt: true,
        autoHideDelay: 3000,
        scrollOffset: 250

    });

    /* Edit profile-user birth detail Validations*/
    $("#frmChangePwd").validationEngine({
        promptPosition:"topRight:-100",
        //scroll : false,
        showOneMessage: true,
        autoHidePrompt: true,
        autoHideDelay: 3000,
        scrollOffset: 250

    });

    /* Edit profile-user birth detail Validations*/
    $("#frmUserPersonalDetail").validationEngine({
        promptPosition:"topRight:-100",
        //scroll : false,
        showOneMessage: true,
        autoHidePrompt: true,
        autoHideDelay: 3000,
        scrollOffset: 250

    });

    
    /* Edit profile-user birth detail Validations*/
    $("#customizeReport").validationEngine({
        promptPosition:"topRight:-100",
        //scroll : false,
        showOneMessage: true,
        autoHidePrompt: true,
        autoHideDelay: 3000,
        scrollOffset: 250

    });


/* 
 This is used to set current menu 
*/
   $('.current-menu-item').removeClass('current-menu-item');
   $('.current_page_item').removeClass('current_page_item');
   var currurl = decodeURIComponent(window.location.pathname);
     
    if (currurl.indexOf('/sun-signs/index/') > -1)
    {
       var val=$('li:has(a[href="/sun-signs/free-horoscope"])').addClass('current-menu-item current_page_item');
    }
    // we remove å from danish url for comparision

    else if(currurl.indexOf('/users/login') > -1 || currurl.indexOf('/dk/brugere/logpå') > -1 )
    {
       var val = $('a[href="'+currurl+'"]').addClass('btn btn-red');
       var val = $('a[href="/users/sign-up"]').removeClass('btn btn-red');	
       var val = $('a[href="/dk/brugere/tilmeld-dig"]').removeClass('btn btn-red');  
    }
    else
    {
       var val=$('li:has(a[href="'+currurl+'"])').addClass('current-menu-item current_page_item');
    }
   

    $("#frmLogin").validationEngine({
        promptPosition:"topRight:-100",
        //scroll : false,
        showOneMessage: true,
        autoHidePrompt: true,
        autoHideDelay: 1000,
        scrollOffset: 250

    });

	  $("#frmRegStepTwo").validationEngine({
        promptPosition:"topRight:-100",
        //scroll : false,
        showOneMessage: true,
        autoHidePrompt: true,
        autoHideDelay: 1000,
        scrollOffset: 250

    });

    $("#resetPassword").validationEngine({
        promptPosition:"topRight:-100",
        //scroll : false,
        showOneMessage: true,
        autoHidePrompt: true,
        autoHideDelay: 1000,
        scrollOffset: 250

    });

    $("#reset_password_token").validationEngine({
        promptPosition:"topRight:-100",
        //scroll : false,
        showOneMessage: true,
        autoHidePrompt: true,
        autoHideDelay: 1000,
        scrollOffset: 250

    });

    $("#step-1").validationEngine({
        promptPosition:"topRight:-100",
        showOneMessage: true,
        scrollOffset:200,
        autoHidePrompt: true,
        autoHideDelay: 3000

    });

    $("#support-tickets").validationEngine({
        promptPosition:"topRight:-100",
        showOneMessage: true,
        scrollOffset:200,
        scroll: false,
        autoHidePrompt: true,
        autoHideDelay: 3000

    });

    $("#step-2").validationEngine({
        //promptPosition:"topRight:-100",
        //scroll : false,
        showOneMessage: true,
        autoHidePrompt: true,
        autoHideDelay: 3000,
        scrollOffset: 250
    });

 $(".skype-radio").click(function(){
     
     changeConsultationPrice($(this).val(), $('#category_id').val());
   
 })

 $("#category_id").change(function(){

     //alert($(this).val());
     //alert($('input[name=currency_id]:checked').val());
     changeConsultationPrice($('input[name=currency_id]:checked').val(), $(this).val());
   
 })
 

/* 
   This datepicker is used for sign up  section
   Created On: 06 Jan 2017 
   Created By: Stan Field

*/

$('#signup-datepicker').datepicker({
        autoclose: true,
        startView:2,
        endDate: '+0d',
        format: 'dd/mm/yyyy - DD',
        forceParse: false,
        autoHidePrompt: true,
        autoHideDelay: 1000,
        showOneMessage: true
  });



	$('#datepicker').datepicker({
		    autoclose: true,
        startView:2,
        endDate: '+0d',
        //format: 'yyyy/mm/dd - DD',
        format: 'dd/mm/yyyy - DD',
        forceParse: false,
        autoHidePrompt: true,
        autoHideDelay: 1000,
        showOneMessage: true
	});



/* 
   This datepicker is used for report section
   Created On: 04 Jan 2017 
   Created By: Stan Field

*/
  $('#report-datepicker').datepicker({
        autoclose: true,
        startView:2,
        endDate: '+0d',
        format: 'dd/mm/yyyy - DD',
        forceParse: false,
        autoHidePrompt: true,
        autoHideDelay: 1000,
        showOneMessage: true
  });



   $('#datepicker-1').datepicker({
        autoclose: true,
        startView:2,
        endDate: '+0d',
        format: 'yyyy/mm/dd - DD',
        forceParse: false,
        autoHidePrompt: true,
        autoHideDelay: 1000,
        showOneMessage: true
    });


 $('#person-1').datepicker({
        autoclose: true,
        startView:2,
        endDate: '+0d',
        format: 'dd/mm/yyyy - DD',
        forceParse: false,
        autoHidePrompt: true,
        autoHideDelay: 1000,
        showOneMessage: true
    });

 $('#person-2').datepicker({
        autoclose: true,
        startView:2,
        endDate: '+0d',
        format: 'dd/mm/yyyy - DD',
        forceParse: false,
        autoHidePrompt: true,
        autoHideDelay: 1000,
        showOneMessage: true
    });



  $('#archive-date-picker').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        forceParse: false,
        autoHidePrompt: true,
        autoHideDelay: 1000,
        showOneMessage: true
      });
 
/* This is used to change product language 
   Created By : Stand Field
   Created On : 22nd Dec 2016 
*/

$('#language').change(function(){
        $(".language_id").val($(this).val());
})

$('.btn-color').click(function(){
   $('.btn-color').removeClass('active-color');
   $(this).addClass('active-color');

})

$("select").addClass('down');

$("select").click(function(){
  $(this).toggleClass('up');
});


/*$(".down").on('click', function () {
  if ($(this).is(":focus")) {
    //alert ('hmmmmmmma');
    //$(this).addClass('up');
    $(this).toggleClass('up');
  } else {
    $(this).removeClass('up');
  }
});*/


/* Accordion Links*/	
    $(".link").click(function(e) {
    
            e.preventDefault();
            var title = $(this).data('rel');

            var faq_title = title.replace( /_/g,' ' );

            $('.content-container div').fadeOut('slow');
            $('#' + title).fadeIn('slow');
            
            $("#faq-title").text(faq_title.toUpperCase()+" FAQ");


});



/* To change currency dropdown arrow direction on reports page */
    // $('#currency_id').click(function(){
    //    $(this).toggleClass('up');
    // })
    // $('#language').click(function(){
    //    $(this).toggleClass('up');
    // })

    
    

})
/* Accordion */
  $( "#accordion" ).accordion();

 function selectCity(id,city_id_div, result_div, city_box, val)
 {
                                $('#'+city_id_div).val(id);
                                $("#"+city_box).val(val);
                                $("#"+result_div).hide();

}
/* This function is used to change the drop down arrow */
// function changeArrowOnClick( field )
// {
//    console.log(field);
//   $('#' + field).toggleClass('up');
// }

function getCities(country_box, city_box ,result_div, city_id_div, url)
 {
      var country = $('#'+country_box).val();
      var city = $('#'+city_box).val();
      
      $("#" + city_id_div).val('');
      if( city == '' ){
      $("#" + result_div).hide();
          return false;
      }
          if( country == '' ){
            alert('Please Select Country');
            $('#'+result_div).focus();
            $('#'+city_box).val('');
            return false;
          }
          if( city.length < 2){
          return false;
                    }
        var lastChar = country_box.slice(-1);

        if(lastChar==1 || lastChar ==2)
        {
         $("#loading-" + lastChar).show();
        }
        else
        {
         $("#loading").show(); 
        }


        $.ajax({
         type : "POST",
         url : url,
         cache: false,
         async: true,
         data:{
                country : country,
                city : city,
                city_id_div : city_id_div,
                result_div : result_div,
                city_box : city_box
             },
         success: function(data){
                                 // if(data == false)
                                 // {
                                 //    data = 'City not found with entered letters'; 
                                 //    //$('#'+city_box).val('')
                                 // }
                                // console.log(data);
                                     $("#"+result_div).show();
                                     $("#"+result_div).html(data);
                                     $("#"+city_box).css("background","#FFF");
                                     if(lastChar==1 || lastChar ==2)
                                     {
                                      $("#loading-"+lastChar).hide();
                                     }
                                     else
                                     {
                                        $("#loading").hide(); 
                                     }

                          
                                },
         error: function(error){
                                 console.log(error);
                               }
        });
}


	/* Get Prediction */
//function getPrediction(date, sign, language, scope, div, type, url)
function getPrediction(date, sign, language, scope, div, type,field)
{
   
   $.ajax({

            url : url + 'get-prediction', 
            cache : false,
            type : "POST",
            dataType: 'json',
            async: false,
            data:
            { 
                date: date,
                sign: sign, 
                language: language,
                type: type,
                scope: scope,
            },
           success: function(data) {
            if(data != null)
            {
              
                $("#"+div).html(data.prediction);
                
                
                
                //$('meta[property="og:description"]').attr("content", 'new value');
                //$("meta[property='og\\:description']").attr("content", data.prediction);
            }
            else
            {
                  console.log('Error');
            }
        }

    });

}

//function getPredictionOnClick(field, previous, next, current_date_field, date_val, img_field, img, url, date, sign, language, scope)
function getPredictionOnClick(field, previous, next, current_date_field, date_val, img_field, img, date, sign, language, scope)
{
    $("#" +field).click(function()
    {
       switch(scope)
           {
             case 1 : scope_name = 'daily';
                       break;
             case 2 : scope_name = 'weekly';
                       break;
             case 3 : scope_name = 'monthly';
                       break;
             case 4 : scope_name = 'yearly';
                       break;
             default : scope_name = 'daily';
                       break;

           }
        $("#" + field).hide();
        $("#" + next).attr('class', '');
        $('#' + next).addClass('btn btn-red nextBtn');
        $('#' + next).show();
        $("#" + previous).attr('class', '');
        $('#' + previous).addClass('btn btn-red prevBtn');
        $('#' + previous).show();
        $('#' + current_date_field).html(date_val);
        $('#' + img_field).html(img);
        //getPrediction(date, sign, language, scope, img_field, scope_name, url);
        getPrediction(date, sign, language, scope, img_field, scope_name,field);

})


}

//function getArchivePrediction(img , url, sign, language)
function getArchivePrediction(img, sign, language)
{
 
      $('#getPrediction').click(function(){
          var scope = $('#scope').val();
          var date = $('#archive-date-picker').val();
          var div = 'archive-prediction';
          date = date.replace(/\//g, '-');
                           
              if( date == '' )
              {
                alert('Please Select Date');
                $('#scope').focus();
                return false;

              }


        $('#archive-prediction').html(img);

            switch(scope){

                                //case '1' :     getPrediction(date, sign, language ,1, div, 'archive' , url);
                                case '1' :     getPrediction(date, sign, language ,1, div, 'archive');
                                               break;

                                //case '2' :     getPrediction(date, sign, language, 2, div, 'archive' , url);
                                case '2' :     getPrediction(date, sign, language, 2, div, 'archive');
                                               break;

                                //case '3' :     getPrediction(date, sign, language, 3, div, 'archive', url);
                                case '3' :     getPrediction(date, sign, language, 3, div, 'archive');
                                               break;

                                //case '4' :     getPrediction(date, sign, language, 4, div, 'archive', url);
                                case '4' :     getPrediction(date, sign, language, 4, div, 'archive');
                                               break;


                                default :    console.log('Error Occurred');      
                        }


    });

}
/* function to download software in 5 sec */
function downloadSoftware(link)
{
    var i = 5;
    timer = window.setInterval(function(){
        $('#counter').html(i)
        if(i>0)
        {
          i--;
        }
        else
        {
          window.location = link;
          clearInterval(timer);

        }
    }, 1000);

}

/** This function is used to change products prices **/
//function changePrice( product_id, currency_id, product_type_id, category) {
  function changePrice( product_id, currency_id, product_type_id, category, $locale) {
    

    
        $('#product_type_id').val(product_type_id);
        if(product_type_id == 6)
        {
          $('.type').text(' CD');
          $('#type').val(' CD');
          if($locale.toLowerCase() == 'en_us' )
          {
            $('.btn-text').val('Buy Software CD')
          }
          else
          {
            $('.btn-text').val('Køb Software CD')
          }
          type = 'software-cd';
          $('#delivery_opt').val('2');

        }
        else if(product_type_id == 7)
        {
          $('.type').text(' Shareware');
          $('#type').val(' shareware');
          
          if($locale.toLowerCase() == 'en_us' )
          {
            $('.btn-text').val('Buy/Register shareware');
          }
          else
          {
            $('.btn-text').val('Køb/Register shareware');

          }
          $('#delivery_opt').val('1');
          type = 'shareware';


        }

     
        $.post(url + "products/get-product-price",{product:product_id, currency: currency_id, product_type_id: product_type_id},function(result){ 
          priceInfo = result.split("-");
          $('#original_price').html(priceInfo[1].replace(' ',''))
          $('#total_price, #strPrice').html(priceInfo[0].replace(' ',''));
          $('#price').val(priceInfo[0]);

        });   
    
     if(category == 'software-bundle')
     {
        $.post(url + "products/get-software-bundle-products-price",{product:product_id, currency: currency_id, product_type_id: product_type_id},function(result){ 
                 


                   $.each(JSON.parse(result), function(k,v)
                            {
                                
                              if(v['discount_total_price'] == 0)
                              {
                                $("#bprice"+ v['product_id']).html(v['currency']['symbol']+(v['total_price']).toFixed(2));
                              }
                              else
                              {
                                $("#bprice"+ v['product_id']).html(v['currency']['symbol']+(v['discount_total_price']).toFixed(2));
                              }
                              $("#blink"+ v['product_id']).attr('href','/products/'+ v['product']['seo_url']+'/'+type+'/'+v['product_id']);
                                
                            });
                 
                });   

     }
  }

/** This function is used to change skype consultation price **/
function changeConsultationPrice(currencyId, categoryId)
{
  // $('.category_id').val(categoryId);
  locale = $('#locale').val();

  product = $("input[name='consult_product']:checked").val(); 
  id = $('input[type=radio][name=consult_product]:checked').attr('id')
  counter = id.split('_').pop();

//   $.post(url + "orders/get-product-price",{category:categoryId, currency: currencyId},function(result){ 
   $.post(url + "orders/get-product-price",{category:categoryId, currency: currencyId, locale: locale},function(result){ 
            result = JSON.parse(result);
            for(i=0; i<result.length; i++)
            {
            
               if(result.length>2)
               {
                $("#consultation").show();
               }
               else
               {
                $("#consultation").hide();
               }
               if(result[i]['ProductPrices']['discount_total_price'] == 0)
               {
                $("#checkout-price-"+i).val(result[i]['ProductPrices']['total_price']);
                 $("#spnSymbol_"+i).html('('+result[i]['currency']['symbol'] + result[i]['ProductPrices']['total_price']+')');   
               }
               else
               {
                $("#checkout-price-"+i).val(result[i]['ProductPrices']['discount_total_price']);
                 $("#spnSymbol_"+i).html('('+result[i]['currency']['symbol'] + result[i]['ProductPrices']['discount_total_price']+')');   
               }

               $(".checkout-currency-code").val(result[i]['currency']['code']);
               $(".checkout-currency-symbol").val(result[i]['currency']['symbol']);
               $(".checkout-currency-id").val(result[i]['currency']['id']);
               $("#consult-title-"+i).html(result[i]['name']);

               // $("#spnSymbol_"+i).html('('+result[i]['currency']['symbol'] + result[i]['ProductPrices']['total_price']+')');   
               
               $("#product_name_"+i).val(result[i]['name']);
               $("#product_id_"+i).val(result[i]['id']);
            }
            product = $("input[name='consult_product']:checked").val();
            setConsultationData(product, counter);

      });  


}


function setConsultationData(product_id, counter)
{
  $("#product_id").val(product_id);
  $("#checkout-price").val($("#checkout-price-" + counter).val());
  $("#product_name").val($("#product_name_" + counter ).val());
}

function hideExtraFields(field1, field2)
{
  $('#' + field1 ).hide();
  $('#' + field2).val('');


}
