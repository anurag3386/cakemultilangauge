//var url = 'http://ec2-54-193-51-211.us-west-1.compute.amazonaws.com/admin_panel/';
var url = window.location.origin+'/admin_panel/';

$('document').ready(function(){

	  moment.locale('en', {
      week: { dow: 1 } // Monday is the first day of the week
    });
	
  /* for editing media */
  //category = $("#media-category").val();
  //changeCategory(category);


	$("#form_id").validationEngine({
		promptPosition:"topRight:-100",
	  ignore: ':hidden:not("#products_id")'

	});

	$('#datepicker').datepicker({
		autoclose: true,
		startView:2,
		//format: 'yyyy-mm-dd - DD' 
    format: 'dd/mm/yyyy - DD' 

	});

	/*
  This date picker is used on Sunsign Prediction Page
  Created By: Stan Field
  Created On: 06 Jan 2017
*/
	$('#datepicker-1').datepicker({
		autoclose: true,
		startView:2,
		format: 'dd/mm/yyyy',
		weekStart: 1 
	});


/*
  This date picker is used on Orders page 
  Created By: Stan Field
  Created On: 06 Jan 2017
*/
  $('#monthly-datepicker').datepicker({
    format: "MM yyyy",
    viewMode: "months", 
    minViewMode: "months",
    autoclose: true,

  });

  

$('#scope').change(function(){
    $('#datepicker-1').val('');
    $('#startDate').val('');
    $('#endDate').val('');
     var scope = $("#scope").val();
     if(scope == 2)
     {
  			$("#startDate").show();
		    $("#endDate").show();
     }
    else
    {
    	  $("#startDate").hide();
		    $("#endDate").hide();
    }
})


	$('#datepicker-1').on('changeDate', function (e) {
	     	$(this).datepicker('hide');
    		var scope = $("#scope").val();
  			if(scope == 2)
  			{
  			 	 var value = $("#datepicker-1").val();
  		     //var firstDate = moment(value, "YYYY-MM-DD").day(1).format("YYYY-MM-DD");
  		     //var lastDate =  moment(value, "YYYY-MM-DD").day(7).format("YYYY-MM-DD");

           var firstDate = moment(value, "DD/MM/YYYY").day(1).format("DD/MM/YYYY");
           var lastDate =  moment(value, "DD/MM/YYYY").day(7).format("DD/MM/YYYY");

  				 $("#startDate").val(firstDate);
  				 $("#endDate").val(lastDate);
  			   $("#startDate").show();
  		     $("#endDate").show();
  			}
  			else
  			{
  			   $("#startDate").hide();
  			   $("#endDate").hide();
  			}
           
  });
  

	$("#timepicker").timepicker({
		showMeridian: false,
		showInputs: false,
		showSeconds: true,
		secondStep: 1,
		minuteStep: 1,
		defaultTime:'00:00:00'
    //defaultTime: false
    });
    $('.select2').select2();

   $('#country_id').on('change', function() {
   		
   		var post_url = "./get_cities";
   		if( $('#edit_option').html() == 1) {
   			post_url = "../get_cities";
   		}

   		$('#city_id').empty();
   		$('#city_id').attr('disabled', 'disabled'); 
   		var val = $(this).val();
	   	if(val) {			
			$.post(post_url,{id:val},function(result){ 
				$('#city_id').removeAttr('disabled');
			    $('#city_id').append(result);
			});
		}
		else {
			$('#city_id').attr('disabled', 'disabled'); 
		}
   });

    $('#birth_country').on('change', function() {
		$('#birth_city').empty();
    	var post_url = "./get_cities";
   		if( $('#edit_option').html() == 1) {
   			post_url = "../get_cities";
   		}


   		
   		$('#birth_city').attr('disabled', 'disabled'); 
   		var val = $(this).val();
	   	if(val) {
			
			$.post(post_url,{id:val},function(result){ 
				$('#birth_city').removeAttr('disabled');
			    $('#birth_city').append(result);
			});
		}
		else {
			$('#birth_city').attr('disabled', 'disabled'); 
		}
   });
	
   tinymce.init({ 
   		 // selector:'textarea.html_editor',
   		//  plugins: "fullpage",
        selector: 'textarea.html_editor',
        convert_urls : false,
        height: 300,
        theme: 'modern',
        images_reuse_filename: true,
        plugins: [
          'advlist autolink lists link image charmap print preview hr anchor pagebreak',
          'searchreplace wordcount visualblocks visualchars code fullscreen',
          'insertdatetime media nonbreaking save table contextmenu directionality',
          'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc',
          'image code'
        ],
        toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons | codesample | link image | code',
        image_advtab: true,
         // enable title field in the Image dialog
        image_title: true, 
        // enable automatic uploads of images represented by blob or data URIs
        automatic_uploads: true,
        // URL of our upload handler (for more details check: https://www.tinymce.com/docs/configure/file-image-upload/#images_upload_url)
        images_upload_url: url+'users/upload-image-tinymce',
        // here we add custom filepicker only to Image dialog
        file_picker_types: 'image', 
        // and here's our custom image picker
        file_picker_callback: function(cb, value, meta) {
          var input = document.createElement('input');
          input.setAttribute('type', 'file');
          input.setAttribute('accept', 'image/*');
          
          // Note: In modern browsers input[type="file"] is functional without 
          // even adding it to the DOM, but that might not be the case in some older
          // or quirky browsers like IE, so you might want to add it to the DOM
          // just in case, and visually hide it. And do not forget do remove it
          // once you do not need it anymore.

          input.onchange = function() {
            var file = this.files[0];
            
            // Note: Now we need to register the blob in TinyMCEs image blob
            // registry. In the next release this part hopefully won't be
            // necessary, as we are looking to handle it internally.
            var id = 'blobid' + (new Date()).getTime();
            var blobCache = tinymce.activeEditor.editorUpload.blobCache;
            var blobInfo = blobCache.create(id, file);
            blobCache.add(blobInfo);
            
            // call the callback and populate the Title field with the file name
            cb(blobInfo.blobUri(), { title: file.name });
          };
          
          input.click();
        },
        templates: [
          { title: 'Test template 1', content: 'Test 1' },
          { title: 'Test template 2', content: 'Test 2' }
        ],
        content_css: [
          '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
          '//www.tinymce.com/css/codepen.min.css'
        ]
   	});

/* This is used to set default value to all price text boxes in admin panel
   Created By : Stan Field
   Created On : 22 Dec 2016
*/
$("input[name^='product_price[']").each(function( index ) {

   if($(this).val() == '')
   {
   	$(this).val('0.00');
   }
 });
  


});  
/* This ajax is used to change order status state */
 
function changeOrderStatus(controller, id, type)
{
  
  if(confirm("Are you sure you want to update the status?"))
  {
    $.post(url+controller+"/change",{id:id,val:type},function(result){ 
       if(result === "true")
       {
        alert("Thank you. Status has been changed.");
        $('#current_status').html($("#status option:selected").text());
       }
       else
       {
         alert('Unable to process request');
         return false;
       }
    });
  } 
}



function changeStatus(controller, row_id,type)
{
	if(confirm("Are you sure you want to update the status?"))
	{
	  $.post(controller+"/change",{id:row_id,val:type},function(result){ 
	    $("#status_"+row_id).html(result);
	  });
	} 
}

function changeAstrologerStatus(controller, row_id,type)
{
	if(confirm("Are you sure you want to update the status?"))
	{
	  $.post(controller+"/change_astrologer",{id:row_id,val:type},function(result){
	    $("#status_"+row_id).html(result);
	  });
	} 
}

function deleteImage() {
	if(confirm('Are you sure you want to delete?')) {
	$('.fa-trash').next().next().val(1);
	$('.fa-trash').prev().fadeOut();
	$('.fa-trash').fadeOut();
}	
}

function updateSeo(val)
{
	var txt = val.toLowerCase();
	txt = txt.replace(/ /g,"-");
	$('#seo_url').val(txt);
}

function calculatePrice(code, counter) {
	var price = $('#price_'+code+'_'+counter).val();
	var vat = $('#vat_'+code+'_'+counter).val();
	var total = (Number(price) + Number(vat)).toFixed(2);
	$('#total_'+code+'_'+counter).val(total);
}

function calculateDiscountPrice(code, counter) {
	var price = $('#discount_price_'+code+'_'+counter).val();
	var vat = $('#discount_vat_'+code+'_'+counter).val();
	var total = (Number(price) + Number(vat)).toFixed(2);
	$('#discount_total_'+code+'_'+counter).val(total);
}

function previewTemplate(template_id) {

	 $.post("email-templates/fetch",{id:template_id},function(result){ 
	 	$.colorbox({ html: result, scrolling: true, maxHeight: '80%', maxWidth: '100%' });
	  });	
}



function showFields(fields)
{
  for(i = 0; i < fields.length; i++)
  {
  	$('#' + fields[i] ).show();
    $('#' + fields[i] ).removeAttr("style");
  }
}
function hideFields(fields)
{
 for(i = 0; i < fields.length; i++)
  {
  	$('#' + fields[i]  ).hide();
  }
}

function setTabsValues(fields)
{
  for (var key in fields) {
     $('#' + key ).html( fields[key] );
 }
}

function setProductType(data)
{
	for (var key in data) {
	    $('#' + key ).html( data[key] );
	 }
}


function getProductTypes(categoryId, productId=0)
 { 
     field = [];
     product_type = [];
     tabs = [];



	   switch(categoryId)
     {
       case '1' :
                   

                    tabs['dynamic-tab-1'] =  'Software CD';
                    tabs['dynamic-tab-2'] =  'Shareware';
                    setTabsValues(tabs);

                    fields = ['dynamic-tab-2', 'tab_6'];
                    showFields(fields);
                  
                   
                    
                    product_type['product-type-1']  = 6;
                    product_type['product-type-1']  = 7; 
                  
				          	setProductType(product_type);
                    hideFields(['products']);
                    
                    for(j=1; j<=5; j++)
                    {
                    	$('#product_price_'+j+'_product_type_id').val(6);
                    }

					          for(j=6; j<=10; j++)
                    {
                    	$('#product_price_'+j+'_product_type_id').val(7);
                    }

                    
                    break;



      case '2':    tabs['dynamic-tab-1'] =  'Full Report';
                   tabs['dynamic-tab-2'] =  'Elite Full Report';
                   setTabsValues(tabs);
                   fields = ['dynamic-tab-1','tab_5','dynamic-tab-2', 'tab_6']; 
                   showFields(fields);

        				   product_type['product-type-1']  = 5;
                   product_type['product-tab-2']   = 8;
                   setProductType(product_type);
                   hideFields(['products']);


                   for(j=1; j<=5; j++)
                   {
                    	$('#product_price_'+j+'_product_type_id').val(5);
                   }

                   for(j=6; j<=10; j++)
                   {
                    	$('#product_price_'+j+'_product_type_id').val(8);
                   }  

                   
                    break;

      case '20' :
                   
                     
                    tabs['dynamic-tab-1'] =  'Software CD';
                    tabs['dynamic-tab-2'] =  'Shareware';
                    setTabsValues(tabs);

                    fields = ['dynamic-tab-2', 'tab_6', 'products'];
                    showFields(fields);
                  
                   
                    
                    product_type['product-type-1']  = 6;
                    product_type['product-type-1']  = 7; 
                  
                    setProductType(product_type);
                    
                    for(j=1; j<=5; j++)
                    {
                      $('#product_price_'+j+'_product_type_id').val(6);
                    }

                    for(j=6; j<=10; j++)
                    {
                      $('#product_price_'+j+'_product_type_id').val(7);
                    }

                    
                    break;
      
       default:     
                    tabs['dynamic-tab-1'] =  'Tab-1';
					          fields = ['dynamic-tab-2', 'tab_6' , 'products'];
                    hideFields(fields); 
                    setTabsValues(tabs);

     }
 } 

 /* This function is used to change category of media module*/
function changeCategory(category)
{
  $("#path").show();
  if(category == 25)
  {
    $("#src").html("<input name='path' class='form-control validate[required]' maxlength='200' placeholder='Enter youtube video id' type='text'/>");
    
  }
  else if(category == 24)
  {
      $("#src").html("<input name='path' class='form-control validate[required]' type='file'/>");
    
  }
  else
  {
      $("#path").hide();
  }
}
