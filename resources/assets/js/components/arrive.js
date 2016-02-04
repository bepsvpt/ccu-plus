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
  });
}

export default arrive;
