var user = {
  is_login: false,
  
  initialize: function() {
    $(document).on('pageshow', '#login-page', this.login_page_show_event);
  },
  
  login_page_show_event: function() {
    last_page_id = $.mobile.activePage.attr("id");
  },
  
  show_signup_page: function() {
    $.mobile.changePage("signup.html", { transition: "slide" } );
  },
  
  signup_get_nonce_ajax_query: function() {
    $.mobile.loading( 'show' );
    var get_nonce_url = base_url + '/api/get_nonce/?controller=user&method=register';
    $.ajax({
      url: get_nonce_url,
      type: "POST",
      dataType: "json",
      success:function(ret_data) {
        user.create_new_user_ajax_query(ret_data);
      },
      error: function(errorThrown) {
        $.mobile.loading( 'hide' );
        navigator.notification.alert( '服务器链接超时！' );
      }
    });
  },
  
  create_new_user_ajax_query: function(nonce_data) {
    //var create_new_user_url = base_url + '/?json=user.register&nonce=' + nonce_data.nonce + '&username=' + $('#signup_user_name').val() + '&email=' + $('#signup_email').val() + '&password=' + $('#signup_password').val() + '&display_name=' + $('#signup_true_name').val() + '&mobile=' + $('#signup_mobile').val() + '&company=' + $('#signup_company').val();
    var create_new_user_url = base_url + '/?json=user.register';
    var content = {
      'username': $('#signup_user_name').val(),
      'email': $('#signup_email').val(),
      'password': $('#signup_password').val(),
      'display_name': $('#signup_true_name').val(),
      'mobile': $('#signup_mobile').val(),
      'company': $('#signup_company').val(),
      'nonce': nonce_data.nonce
    };
    
    $.post(
      create_new_user_url,
      content,
      function(ret_data) {
          user.create_new_user_result_ajax_query(ret_data);
    });
  },
  
  create_new_user_result_ajax_query: function(ret_data) {
    $.mobile.loading( 'hide' );
    if (ret_data.msg == "Success") {
      navigator.notification.alert( '用户注册成功！', this.on_create_new_user_success, '提示', '完成');
    }
  },
  
  on_create_new_user_success: function() {
    history.back();
  }
}

