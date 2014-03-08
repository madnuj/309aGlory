var products = {
  
  product_id: 0,
  
  initialize: function() {
    $(document).on('pageshow', '#products-page', this.products_page_show_event);
    $(document).on('pageshow', '#product-detail-page', this.product_detail_page_show_event);
  },

  products_page_show_event: function() {
    products.reloadListData();
  },
    
  product_detail_page_show_event: function() {
    products.reloadDetailData();
  },

  reloadListData: function() {
    $.mobile.loading('show');
    products.products_list_ajax_query();
  },

  reloadDetailData: function() {
    $.mobile.loading('show');
    products.products_detail_ajax_query();
  },
  
  products_list_ajax_query: function() {
    var get_products_url = base_url + '/?json=get_posts&post_type=custom_post_products';
    
    $.ajax({
      url: get_products_url,
      type: "GET",
      dataType: "json",
      success:function(ret_data) {
        products.generate_sub_page(ret_data);
      },
      error: function(errorThrown) {
        $.mobile.loading( 'hide' );
        navigator.notification.alert( '网络连接超时！' );
      }
    });
  },
  
  products_detail_ajax_query: function() {
    var get_product_detail_url = base_url + '/?json=get_post&post_type=custom_post_products&post_id=' + products.product_id;
    
    $.ajax({
      url: get_product_detail_url,
      type: "GET",
      dataType: "json",
      success:function(ret_data) {
        products.generate_product_detail(ret_data);
      },
      error: function(errorThrown) {
        $.mobile.loading( 'hide' );
        navigator.notification.alert( '网络连接超时！' );
      }
    });
  },
  
  generate_sub_page: function(list_data) {
    if (list_data.status != "ok") {
      navigator.notification.alert('获取页面失败！');
      return;
    }
    products.generate_products_list(list_data);
  },

  generate_products_list: function(list_data) {
    var products_array = list_data.posts;
    $.mobile.loading('hide');
    for (var i in products_array) {
      var post = products_array[i];
      $('#products_content_list').append('<li><a href="javascript:products.enter_next_level_list(' + post.id + ')">' + post.title + '</a></li>');
    }
  
    $('#products_content_list').listview('refresh');
  },
  
  generate_product_detail: function(detail_data) {
    if (detail_data.status != "ok") {
      navigator.notification.alert('获取页面失败！');
      $.mobile.loading('hide');
      return;
    }
    $.mobile.loading('hide');
    $('#product_title').text(detail_data.post.title);
    $('#product_content_div').html(detail_data.post.content);
  },
  
  enter_next_level_list: function(post_id) {
    products.product_id = post_id;
    $.mobile.changePage( "product_detail.html", { transition: "slide" });
  }
}