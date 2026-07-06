// Chrome scrolls/changes unfocused number inputs on wheel instead of the page
document.addEventListener('wheel', function(e) {
  if (e.target.type === 'number' && document.activeElement !== e.target) {
    e.preventDefault();
    window.scrollBy(0, e.deltaY);
  }
}, { passive: false });

// jQuery helpers — jQuery + Bootstrap 3 are loaded from CDN in the layout
if (typeof jQuery !== 'undefined') {
  jQuery(function($) {
    $('.print-window').click(function() {
      window.print();
    });

    $(document).click(function(e) {
      var $target = $(e.target);

      if ($target.closest('[data-toggle="collapse"]').length) {
        return;
      }

      if ($target.closest('.collapse.in, .collapse.show').length) {
        return;
      }

      $('.collapse').collapse('hide');
    });
  });
}
