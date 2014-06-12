/* ==========================================================================
 date calendar
 ========================================================================== */
$(window).resize(function() {
  $('.calendar').remove();
});
$(document).ready(function() {

  $('html').click(function() {
    $('.calendar').remove();
  });

  $('[data-calendar]').attr('id', function() {
    return $(this).attr('name');
  });

  $('[data-calendar]').focus(function() {
    if (!$(this).next().is('.calendar')) {

      var dt = new Date();
      var calendar = document.createElement('div');
      calendar.setAttribute('class', 'calendar container-box shadow-medium');
      $(this).after(calendar);
      if ($(this).attr('data-calendar-format') == null) {
        $(this).attr('data-calendar-format', 'dd/mm/yyyy');
      }
      if ($(this).val() != '') {
        var sep = $(this).attr('data-calendar-format').replace(new RegExp('[dmy]', 'gi'), '');
        var maskArray = $(this).attr('data-calendar-format').split(sep[0]);
        var mIndex;
        var yIndex;
        for (var i = 0; i < 3; i++) {
          if (maskArray[i].indexOf('m') > -1) {
            mIndex = i;
          }
          if (maskArray[i].indexOf('y') > -1) {
            yIndex = i;
          }
        }
        var dtArray = $(this).val().split(sep[0]);
        var month = parseInt(dtArray[mIndex]) - 1;
        var year = parseInt(dtArray[yIndex]);
        mountCalendar($(this).attr("id"), month, year);
      } else {
        mountCalendar($(this).attr("id"));
      }

      //positioning calendar
      x = $(this).offset().left;
      y = $(this).offset().top + $(this).outerHeight() + 4;
      $('.calendar').css('left', x);
      $('.calendar').css('top', y);
      $('.calendar').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
      });
    }
  });
  $('[data-calendar]').click(function(e) {
    e.preventDefault();
    e.stopPropagation();
  });

  $('[data-calendar]').keydown(function(e) {
    e.preventDefault();
    e.stopPropagation();
  });
  
});

var months = new Array(
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December');
var months_pt = new Array(
        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

function formatDate(day, month, year, dateMask) {
  var separator = dateMask.replace(new RegExp('[dmy]', 'gi'), '');
  separator = separator[0];
  var dateMaskArray = dateMask.split(separator);
  var formattedDate = '';
  for (var i = 0; i < dateMaskArray.length; i++) {
    var sep = '';
    if (i < dateMaskArray.length - 1) {
      sep = separator;
    }
    switch (dateMaskArray[i]) {
      case 'd':
        formattedDate += day + sep;
        break;
      case 'dd':
        if (day < 10) {
          formattedDate += '0' + day + sep;
        } else {
          formattedDate += day + sep;
        }
        break;
      case 'm':
        formattedDate += month + sep;
        break;
      case 'mm':
        if (month < 10) {
          formattedDate += '0' + month + sep;
        } else {
          formattedDate += month + sep;
        }
        break;
      case 'yy':
        formattedDate += year.toString().substring(2) + sep;
        break;
      case 'yyyy':
        formattedDate += year + sep;
        break;
    }
  }

  return formattedDate;
}

function setInputDate(inputId, value) {
  document.getElementById(inputId).value = value;
}

function getDaysTable(inputId, month, year) {
  var dt = new Date();
  var prevDate = 1;
  dt.setDate(1);
  dt.setMonth(month);
  dt.setYear(year);
  var table = '';
  if (navigator.language.indexOf('pt') > -1) {
    table = '<table class="table-calendar"><tr>' +
            '<td>Dom</td>' +
            '<td>Seg</td>' +
            '<td>Ter</td>' +
            '<td>Qua</td>' +
            '<td>Qui</td>' +
            '<td>Sex</td>' +
            '<td>Sab</td></tr>';
  } else {
    table = '<table class="table-calendar"><tr>' +
            '<td>Sun</td>' +
            '<td>Mon</td>' +
            '<td>Tue</td>' +
            '<td>Wen</td>' +
            '<td>Thu</td>' +
            '<td>Fri</td>' +
            '<td>Sat</td></tr>';
  }
  for (i = 1; i <= 6; i++) {
    table += "<tr>";
    for (ii = 0; ii <= 6; ii++) {
      if (dt.getDay() === ii && dt.getDate() >= prevDate) {
        dateMask = document.getElementById(inputId).getAttribute('data-calendar-format');
        dateString = formatDate(dt.getDate(), month + 1, year, dateMask);
        table += '<td onclick="setInputDate(\'' + inputId +
                '\',\'' + dateString + '\')">' + dt.getDate() + '</td>';
        dt.setDate(dt.getDate() + 1);
        prevDate++;
      } else {
        table += "<td>&nbsp;</td>";
      }
    }
    table += "</tr>";
  }
  table += '</table>';
  return table;
}

function mountCalendar(inputId, month, year) {
  var dt = new Date();
  if (month == null) {
    month = dt.getMonth();
  }
  if (year == null) {
    year = dt.getFullYear();
  }

  var monthString = '';
  if (navigator.language.indexOf('pt') > -1) {
    monthString = months_pt[month];
  } else {
    monthString = months[month];
  }

  var prevMonth = null;
  var nextMonth = null;
  if (month == 0) {
    prevMonth = "11," + (year - 1);
  } else {
    prevMonth = (month - 1) + "," + year;
  }

  if (month == 11) {
    nextMonth = "0," + (year + 1);
  } else {
    nextMonth = (month + 1) + "," + year;
  }

  var today = new Date();

  var dateMask = document.getElementById(inputId).getAttribute('data-calendar-format');
  var todayString = formatDate(today.getDate(), today.getMonth() + 1, today.getFullYear(), dateMask);

  var calendarContent = '<section><span class="input-group">' +
          '<input type="button" onclick="mountCalendar(\'' + inputId + '\',' +
          month + ',' + (year - 1) + ')" value="<<"> ' +
          '<input type="button" onclick="mountCalendar(\'' + inputId + '\',' +
          prevMonth + ')" value="<"> ' +
          '<span style="width: 120px; text-align: center">' + monthString +
          ', ' + year + '</span>' +
          '<input type="button" onclick="mountCalendar(\'' + inputId + '\',' +
          nextMonth + ')" value=">">' +
          '<input type="button" onclick="mountCalendar(\'' + inputId + '\',' +
          month + ',' + (year + 1) + ')" value=">>">' +
          '</span>' +
          getDaysTable(inputId, month, year) +
          '<span class="input-group">' +
          '<input type="button" onclick="setInputDate(\'' + inputId +
          '\',\'\')" value="C" />' +
          '<input type="button" onclick="setInputDate(\'' + inputId +
          '\',\'' + todayString + '\')" value="&bull;" />' +
          '<input type="button" onclick="$(\'.calendar\').remove()" value="&times;" />' +
          '</span>' +
          '</section>';
  $('.calendar').html(calendarContent);
}
