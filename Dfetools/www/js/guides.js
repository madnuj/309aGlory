var guides = {
  
  post_parent: 0,
  
  guides_level: 0,
  
  guides_post_array: new Array(),
  
  initialize: function() {
    for (var i = 0; i < 8; i ++) {
      $(document).on('pageshow', '#guides-page-' + i, this.guides_page_show_event);
    }
  },
  
  guides_page_show_event: function() {
    guides.nav_back_process();
    if ('guides-page-' + guides.guides_level == $.mobile.activePage.attr("id")) {
      guides.reloadListData();
    }
  },
  
  nav_back_process: function() {
    var current_level = guides.get_current_guide_level($.mobile.activePage.attr("id"));
    if (current_level < guides.guides_level) {
      // 清除之前页面的资源
      guides.guides_post_array[guides.guides_level] = 0;
      
      // 加载当前页面资源
      guides.post_parent = guides.guides_post_array[current_level];
      guides.guides_level = current_level;
    }
  },
  
  get_current_guide_level: function(page_id) {
    var splited_str = page_id.split("-");
    if (splited_str.length != 3) {
      return NaN;
    }
    return parseInt(splited_str[2]);
  },
  
  reloadListData: function() {
    $.mobile.loading('show');
    guides.guides_list_ajax_query();
  },
  
  guides_list_ajax_query: function() {
    var get_guides_url = base_url + '/?json=get_posts&post_type=custom_post_guides&post_parent=' + guides.post_parent;
    
    $.ajax({
      url: get_guides_url,
      type: "GET",
      dataType: "json",
      success:function(ret_data) {
        guides.generate_sub_page(ret_data);
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
    if (list_data.count > 0) {
      guides.generate_guides_list(list_data);
    } else {
      guides.guides_detail_ajax_query();
    }
  },
  
  guides_detail_ajax_query: function() {
    var get_guides_url = base_url + '/?json=get_post&post_type=custom_post_guides&post_id=' + guides.post_parent;
    $.ajax({
      url: get_guides_url,
      type: "GET",
      dataType: "json",
      success:function(ret_data) {
        guides.generate_guides_detail(ret_data);
      },
      error: function(errorThrown) {
        $.mobile.loading( 'hide' );
        navigator.notification.alert( '网络连接超时！' );
      }
    }); 
  },
  
  generate_guides_detail: function(ret_data) {
    $.mobile.loading('hide');
    $('#guides_list').hide();
    $('#guide_title').text(ret_data.post.title);
    $('#guide_date').text(ret_data.post.date);
    $('#guide_content').html(ret_data.post.content);
  },
  
  generate_guides_list: function(list_data) {
    var guides_array = list_data.posts;
    $.mobile.loading('hide');
    $('#guides_detail').hide();
    guides.guides_post_array[guides.guides_level] = guides.post_parent;
    for (var i in guides_array) {
      var post = guides_array[i];
      $('#guides_content_list_' + guides.guides_level).append('<li><a href="javascript:guides.enter_next_level_list(' + post.id + ')">' + post.title + '</a></li>');
    }
  
    $('#guides_content_list_' + guides.guides_level).listview('refresh');
  },
  
  enter_next_level_list: function(post_id) {
    guides.post_parent = post_id;
    guides.guides_level += 1;
  
    $.mobile.changePage( "guides" + guides.guides_level + ".html", { transition: "slide" });
  }
} 