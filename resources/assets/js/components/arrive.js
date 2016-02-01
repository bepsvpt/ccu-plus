function arrive() {
  // http://materializecss.com/modals.html#initialization
  $(document).arrive('.modal-trigger', function() {
    $(this).leanModal();
  });

  // http://materializecss.com/forms.html#character-counter
  $(document).arrive('input[length], textarea[length]', function() {
    $(this).characterCounter();
  });
}

export default arrive;
