$('#icons span').after(function() {
  return " ." + $(this).attr('class') + "<br/>";
});
/* ==========================================================================
   Source code styling
   ========================================================================== */
$('[class^="code-"],[class*=" code-"]').html(function() {
  $(this).wrapInner('<div class="code-source">');
});

//code high-lighting
$('.code-highlight > div').html(function() {
  //string between quotes
  var stringHL = /"((?:[^"\\]|\\.)*)"/gi;
  var code = $(this).html().trim().replace(stringHL,
          "<span class=\"code-highlight-string\">\"$1\"</span>");
  //string between /* */
  var commentHL = /\/\*(.*)\*\//gi;
  code = code.replace(commentHL,
          "<span class=\"code-highlight-comment\">/*$1*/</span>");
  $(this).html(code);
});

//code line numbering
$('.code-line-numbered').html(function() {
  var code = $(this).html().trim();
  var lines = code.split('\n');
  var list = '<ol>';
  for (i = 0; i < lines.length; i++) {
    list += '<li></li>';
  }
  list += '</ol>';
  $(this).html(list + code);
});
/* ==========================================================================
   Dropdown menu
   ========================================================================== */
$('body').on('click',function(){
  $('.nav-dropdown-menu').slideUp('fast');
});
$('.nav-dropdown').on('click', function(e){
  e.stopPropagation();
  $(this).next('.nav-dropdown-menu').slideToggle('fast');/*
  if($(this).next('.nav-dropdown-menu').css('display') === 'none'){
    $(this).next('.nav-dropdown-menu').fadeIn('fast');
  }else{
    $(this).next('.nav-dropdown-menu').fadeOut();
  }*/
});
