$(document).ready(function() {

  /* ==========================================================================
   clearfix for floating containers
   ========================================================================== */
  $('.float-left, .float-right').parent().addClass('clearfix');

  /* ==========================================================================
   Source code styling
   ========================================================================== */
  $('.code').html(function() {
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

  var isMultiLineComment = false;
//code high-lighting
  $('.code-highlight .code-line').html(function() {
    //string between quotes
    var stringHL = /("|')((?:[^"\\]|\\.)*)("|')/gi;
    var code = $(this).html().replace(stringHL,
            "<span class=\"code-highlight-string\">$1$2$1</span>");

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
//code line numbering
  $('.code-line-numbered').wrapInner('<ol>');
  $('.code-line-numbered > ol > .code-line').wrap('<li>');

  /* ==========================================================================
   Dropdown and dropup menu handler
   ========================================================================== */
  $('html').on('click', function() {
    $('.dropdown-menu').slideUp('fast');
  });
  
  $('.dropdown').on('click', function(e) {
    e.stopPropagation();
    $('.dropdown-menu').not($(this).next('.dropdown-menu')).slideUp('fast');
    $(this).next('.dropdown-menu').slideToggle('fast');
  });

  $('.submenu').mouseenter(function() {
    $(this).css('cursor', 'default');
    var dropDownMenu = $(this).next('.dropdown-menu');
    var y = $(this).parent().height();
    var x = $(this).parent().outerWidth();
    dropDownMenu.css('margin-top', -y);
    dropDownMenu.css('left', x);
    dropDownMenu.show();
    $(this).parent().mouseleave(function(){
      dropDownMenu.hide();
    });
  });
  
  $('.submenu').click(function(e){
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
   data-dismiss handler
   ========================================================================== */
  $('[data-dismiss]').on('click', function() {
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

  $('[data-dismiss-after]').on('click', function() {
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
    //$(this).css('width','100%');
    return $('#' + $(this).attr('data-colspan-from')).children().length;
  });

  /* ==========================================================================
   data-toggle handler
   ========================================================================== */
  $('[data-toggle-value]').on('click', function() {
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

  $('[data-toggle="button"]').on('click', function() {
    var className = '';
    if ($(this).attr('data-toggle-class') == null) {
      className = 'active';
    } else {
      className = $(this).attr('data-toggle-class');
    }
    $(this).toggleClass(className);
  });

  $('[data-toggle="button-group-radio"] > *').on('click', function() {
    var className = '';
    if ($(this).parent().attr('data-toggle-class') == null) {
      className = 'active';
    } else {
      className = $(this).parent().attr('data-toggle-class');
    }
    $(this).toggleClass(className);
    $(this).siblings().removeClass(className);
  });

  $('[data-toggle="button-group-checkbox"] > *').on('click', function() {
    var className = '';
    if ($(this).parent().attr('data-toggle-class') == null) {
      className = 'active';
    } else {
      className = $(this).parent().attr('data-toggle-class');
    }
    $(this).toggleClass(className);
  });

});