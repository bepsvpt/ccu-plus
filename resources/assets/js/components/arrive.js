function arrive() {
  $(function () {

    // http://materializecss.com/modals.html#initialization
    $('.modal-trigger').leanModal();

    $(document).arrive('.modal-trigger', function() {
      $(this).leanModal();
    });

    // http://materializecss.com/navbar.html#mobile-collapse
    $(".button-collapse").sideNav();

    $(document).arrive('.button-collapse', function() {
      $(this).sideNav();
    });

    // http://materializecss.com/modals.html#initialization
    $('.parallax').parallax();

    $(document).arrive('.parallax', function() {
      $(this).parallax();
    });

    // http://materializecss.com/forms.html#character-counter
    $(document).arrive('input[length], textarea[length]', function() {
      $(this).characterCounter();
    });

    // http://materializecss.com/forms.html#select
    $(document).arrive('select', function() {
      $(this).material_select();
    });

    // http://materializecss.com/dialogs.html#tooltip
    $(document).arrive('.tooltipped', function () {
      $(this).tooltip();
    });
    $(document).leave('.tooltipped', function () {
      $(this).tooltip('remove');
    });

    // http://materializecss.com/collapsible.html#intialization
    $(document).arrive('.collapsible', function() {
      $(this).collapsible();
    });

    // http://materializecss.com/tabs.html#initialization
    $(document).arrive('ul.tabs', function() {
      $(this).tabs();
    });

    // http://momentjs.com/
    moment.locale('zh-tw');

    $(document).arrive('span[data-time-humanize]', function() {
      $(this).text(moment($(this).data('time-humanize')).fromNow());
    });
  });
}

export default arrive;
