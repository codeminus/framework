$(document).ready(function() {

  /* ==========================================================================
   Codemius plugins
   ========================================================================== */
  (function($) {

    /* ========================================================================
     Source code format
     ======================================================================== */
    $.fn.codify = function(codeHighlight) {

      this.addClass('code');

      this.html(function() {
        var code = $(this).html().trim();
        var lines = code.split('\n');
        var codeWrapped = '';
        for (i = 0; i < lines.length; i++) {
          if (lines[i] === "") {
            lines[i] = '&nbsp;';
          }
          codeWrapped += '<div class="code-line">' + lines[i] + '</div>';
        }
        return codeWrapped;
      });

      //code high-lighting
      if (codeHighlight) {
        var isMultiLineComment = false;
        this.addClass('code-highlight');
        this.children('.code-line').html(function() {
          var code;

          //string between quotes
          var stringHL = /("|')((?:[^"\\]|\\.)*)("|')/gi;
          code = $(this).html().replace(stringHL,
                  "<span class=\"code-highlight-string\">$1$2$1</span>");

          var keywords = [
            "namespace", "use", "as", "class", "extends",
            "public", "protected", "private",
            "function", "return",
            "if", "else", "elseif", "for", "foreach", "while", "do", "switch", "case",
            "null", "true", "false"
          ];

          var beginMatch = "\\s|^";
          var endMatch = "?=\\s|\\(|\\{|$";
          var keywordsRegExp = new RegExp(
                  "(" + beginMatch +
                  ")(" + keywords.join("|") +
                  ")(" + endMatch + ")", "gi");
          code = code.replace(keywordsRegExp, "$1<span style=\"color: #3a87ad\">$2</span>");

          //string beginning with /*
          var beginComment = /(\/\*.*)/g;
          //string ending with */
          var endComment = /(.*\*\/)/g;
          //string between /* */
          var commentSingleLine = /(\/\*.*\*\/)/g;

          if (code.match(commentSingleLine)) {
            code = code.replace(commentSingleLine,
                    "<span class=\"code-highlight-comment\">$1</span>");
          } else if (code.match(beginComment)) {
            code = code.replace(beginComment,
                    "<span class=\"code-highlight-comment\">$1</span>");
            isMultiLineComment = true;
          } else if (isMultiLineComment) {
            if (code.match(endComment)) {
              code = code.replace(endComment,
                      "<span class=\"code-highlight-comment\">$1</span>");
              isMultiLineComment = false;
            } else {
              code = "<span class=\"code-highlight-comment\">" + code + "</span>";
            }
          }
          return code;
        });
      }

      //code line numbering
      this.addClass('code-line-numbered');
      this.wrapInner('<ol>');
      this.find('.code-line').wrap('<li>');

    };
  }(jQuery));

  $('.code').codify(true);


  /* ==========================================================================
   CSS dynamic utilities
   ========================================================================== */
  $('.float-left, .float-right, [class^="span"], [class*=" span"]').parent().addClass('clearfix');

  $('.fixed').after(function() {
    var newElement = document.createElement('div');
    newElement.setAttribute('class', 'invisible ' + $(this).attr('class').replace('fixed', ''));
    newElement.innerHTML = $(this).html();
    $(this).width($(this).removeClass('fixed').width()).addClass('fixed');
    $(this).css('z-index', 1);
    return newElement;
  });

  $('.absolute-center, .absolute-center-horizontal').css('margin-left', function() {
    return -$(this).width() / 2;
  });
  $('.absolute-center, .absolute-center-vertical').css('margin-top', function() {
    return -$(this).height() / 2;
  });

  /* ==========================================================================
   disabled navigator item behavior
   ========================================================================== */
  $('.nav .disabled').click(function(e) {
    e.preventDefault();
    e.stopPropagation();
  });
  /* ==========================================================================
   Dropdown and dropup menu handler
   ========================================================================== */
  $('.drop-menu').addClass('nav nav-vlist');

  $('html').click(function() {
    $('.drop-menu').prev('.trigger.on:not(.tab-trigger)').removeClass('active on');
    $('.drop-menu').hide();
  });

  $('.dropdown > .trigger').click(function(e) {
    e.stopPropagation();
    $('.trigger.on').not($(this)).removeClass('active on');
    //if its a navigation tab
    if (!$(this).hasClass('tab-trigger')) {
      $(this).toggleClass('active on');
    }
    var dropMenu = $(this).next('.drop-menu');
    $('.drop-menu').not(dropMenu).hide();
    //if its a input-group
    if (dropMenu.parents('.input-group').length === 1) {
      var x = $(this).parents('.input-group').width() - $(this).outerWidth();
      dropMenu.css('margin-left', -x);
    }
    dropMenu.toggle();
  });

  $('.dropup > .trigger').click(function(e) {
    e.stopPropagation();
    $('.trigger.on').not($(this)).removeClass('active on');
    if (!$(this).hasClass('tab-trigger')) {
      $(this).toggleClass('active on');
    }
    var dropMenu = $(this).next('.drop-menu');
    $('.drop-menu').not(dropMenu).hide();
    var y = $(this).parent().height() + dropMenu.height();
    dropMenu.css('margin-top', -y);
    //if its a input-group
    if (dropMenu.parents('.input-group').length === 1) {
      var x = $(this).parents('.input-group').width() - $(this).outerWidth();
      dropMenu.css('margin-left', -x);
    }
    dropMenu.toggle();
  });

  $('.submenu > .trigger').mouseenter(function() {
    if (!$(this).hasClass('disabled')) {
      $(this).css('cursor', 'default');
      var dropMenu = $(this).next('.drop-menu');
      var y = $(this).parent().height();
      var x = $(this).parent().outerWidth();
      dropMenu.css('margin-top', -y);
      dropMenu.css('left', x);
      dropMenu.show();
      $(this).parent().mouseleave(function() {
        dropMenu.hide();
      });
    }
  });

  $('.submenu > .trigger').click(function(e) {
    e.preventDefault();
    e.stopPropagation();
  });

  //defining arrows for usage on menus
  $('.arrow-left, .caret').html('&blacktriangleleft;');
  $('.arrow-top').html('&blacktriangle;');
  $('.arrow-right').html('&blacktriangleright;');
  $('.arrow-bottom').html('&blacktriangledown;');
  //defining caret orientation according to its menu type
  $('.dropdown').find('.caret').html('&blacktriangledown;');
  $('.dropup').find('.caret').html('&blacktriangle;');
  $('.submenu').find('.caret').html('&blacktriangleright;').css('float', 'right');

  /* ==========================================================================
   data-tab handler
   ========================================================================== */
  $('[data-tab-target]').click(function() {
    $(this).parents('.tab-triggers').find('[data-tab-target], .tab-trigger').removeClass('active');
    if ($(this).parents('.dropdown, .dropup').length === 1) {
      $(this).parents('.dropdown, .dropup').find('.trigger').addClass('active');
    } else {
      $(this).addClass('active');
    }
    var tab = '#' + $(this).attr('data-tab-target');
    $(tab).siblings('.tab').removeClass('active');
    $(tab).addClass('active');
  });

  /* ==========================================================================
   data-dismiss handler
   ========================================================================== */
  $('[data-dismiss]').click(function() {
    var mode = $(this).attr('data-dismiss-mode');
    switch (mode) {
      case 'slide':
        $('#' + $(this).attr('data-dismiss')).stop(true).slideUp('fast');
        break;
      default:
        $('#' + $(this).attr('data-dismiss')).stop(true).fadeOut('fast');
        break;
    }
  });

  $('[data-dismiss-after]').click(function() {
    var mode = $(this).attr('data-dismiss-mode');
    switch (mode) {
      case 'slide':
        $(this).delay($(this).attr('data-dismiss-after')).slideUp('fast');
        break;
      default:
        $(this).delay($(this).attr('data-dismiss-after')).fadeOut('fast');
        break;
    }

  });
  $('[data-dismiss-after]').click();

  /* ==========================================================================
   data-colspan-from handler
   ========================================================================== */
  $('[data-colspan-from]').attr('colspan', function() {
    return $('#' + $(this).attr('data-colspan-from')).children().length;
  });
  /* ==========================================================================
   data-width-from handler
   ========================================================================== */
  $('[data-width-from]').css('width', function() {
    var extraWidth = parseInt($(this).css('padding-left'))
            + parseInt($(this).css('padding-right'))
            + parseInt($(this).css('border-right-width'))
            + parseInt($(this).css('border-left-width'));
    return ($('#' + $(this).attr('data-width-from')).outerWidth() - extraWidth);
  });
  /* ==========================================================================
   data-height-from handler
   ========================================================================== */
  $('[data-height-from]').css('height', function() {
    var extraHeight = parseInt($(this).css('padding-top'))
            + parseInt($(this).css('padding-bottom'))
            + parseInt($(this).css('border-top-width'))
            + parseInt($(this).css('border-bottom-width'));
    return $('#' + $(this).attr('data-height-from')).outerHeight() - extraHeight;
  });
  /* ==========================================================================
   data-toggle handler
   ========================================================================== */
  $('[data-toggle-value]').click(function() {
    var values = $(this).attr('data-toggle-value').split(';');
    if (values[1] == null) {
      values[1] = $(this).attr('value');
      $(this).attr('data-toggle-value', values[0] + ';' + values[1]);
    }
    if ($(this).attr('value') == values[0]) {
      $(this).attr('value', values[1]);
    } else {
      $(this).attr('value', values[0]);
    }
  });

  $('[data-toggle="button"]').click(function() {
    var className = '';
    if ($(this).attr('data-toggle-class') == null) {
      className = 'active';
    } else {
      className = $(this).attr('data-toggle-class');
    }
    $(this).toggleClass(className);
  });

  $('[data-toggle="button-group-radio"] > *').click(function() {
    var className = '';
    if ($(this).parent().attr('data-toggle-class') == null) {
      className = 'active';
    } else {
      className = $(this).parent().attr('data-toggle-class');
    }
    $(this).toggleClass(className);
    $(this).siblings().removeClass(className);
  });

  $('[data-toggle="button-group-checkbox"] > *').click(function() {
    var className = '';
    if ($(this).parent().attr('data-toggle-class') == null) {
      className = 'active';
    } else {
      className = $(this).parent().attr('data-toggle-class');
    }
    $(this).toggleClass(className);
  });

});
