// Chrome scrolls/changes unfocused number inputs on wheel instead of the page
document.addEventListener('wheel', function(e) {
  if (e.target.type === 'number' && document.activeElement !== e.target) {
    e.preventDefault();
    window.scrollBy(0, e.deltaY);
  }
}, { passive: false });

function formatDateInputValue(raw) {
  var digits = String(raw || '').replace(/\D/g, '').slice(0, 8);

  if (digits.length <= 4) {
    return digits;
  }

  if (digits.length <= 6) {
    return digits.slice(0, 4) + '-' + digits.slice(4);
  }

  return digits.slice(0, 4) + '-' + digits.slice(4, 6) + '-' + digits.slice(6, 8);
}

function sanitizeDateInput(input) {
  if (!input || input.type !== 'date') {
    return;
  }

  var formatted = formatDateInputValue(input.value);

  if (formatted !== input.value) {
    input.value = formatted;
  }
}

document.addEventListener('input', function(e) {
  if (e.target.type === 'date') {
    sanitizeDateInput(e.target);
  }
});

document.addEventListener('blur', function(e) {
  if (e.target.type === 'date') {
    sanitizeDateInput(e.target);
  }
}, true);

document.addEventListener('paste', function(e) {
  if (e.target.type === 'date') {
    setTimeout(function() {
      sanitizeDateInput(e.target);
    }, 0);
  }
});

// jQuery helpers — jQuery + Bootstrap 3 are loaded from CDN in the layout
if (typeof jQuery !== 'undefined') {
  jQuery(function($) {
    $('.print-window').click(function() {
      window.print();
    });

    $('form').on('submit', function() {
      $(this).find('input[type=date]').each(function() {
        sanitizeDateInput(this);
      });
    });

    // Footer panels use a simple class toggle — not Bootstrap collapse,
    // which shares the .collapse class with the main navbar and causes flashing.
    $('.notes_trigger, .options_trigger').on('click', function(e) {
      e.preventDefault();
      e.stopPropagation();

      var $panel = $($(this).attr('href'));
      var willOpen = !$panel.hasClass('is-open');

      $('.footer-panel.is-open').removeClass('is-open');

      if (willOpen) {
        $panel.addClass('is-open');
      }
    });

    $(document).on('click', function(e) {
      if ($(e.target).closest('.notes_trigger, .options_trigger, .footer-panel, footer').length) {
        return;
      }

      $('.footer-panel.is-open').removeClass('is-open');
    });
  });
}
