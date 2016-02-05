function arrive() {
  $(function () {
    // http://materializecss.com/modals.html#initialization
    $(document).arrive('.modal-trigger', function() {
      $(this).leanModal();
    });

    // http://materializecss.com/forms.html#character-counter
    $(document).arrive('input[length], textarea[length]', function() {
      $(this).characterCounter();
    });

    // http://materializecss.com/pushpin.html#initialization
    $(document).arrive('.tabs-pushpin', function() {
      $(this).pushpin({top: 64});
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
  });
}

export default arrive;
