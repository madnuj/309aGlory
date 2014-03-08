function my_js_function() 
{
  var data = {
  	action : 'get_guides_data',
  };
  
  jQuery.post(dfe_ajax_script.ajaxurl, data, function(response) {
  	alert(response);
  });
}