var fixes = {
  initialize: function() {
    $(document).on('pageshow', '#fix-list-page', this.fixes_list_page_show_event);
  },
  
  fixes_list_page_show_event: function() {
    if (last_page_id == "login-page") {
      last_page_id = $.mobile.activePage.attr("id");
      history.back();
      return;
    }
    last_page_id = $.mobile.activePage.attr("id");

    if (!user.is_login) {
      $.mobile.changePage( "../user/login.html", { transition: "slideup" } );
    }
  }
}