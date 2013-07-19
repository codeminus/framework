$('#classForm').submit(function() {
  var formData = $(this).serialize();
  $.ajax({
    type: "POST",
    url: "action.php",
    data: formData,
    beforeSend: function() {
      $('#submitBtn').attr('disabled', 'disabled').val('generating code...');
      $('#codeTools').hide('slow');
      $('#codeContainer').html('');
    },
    complete: function() {
      $('#submitBtn').removeAttr('disabled');
      $('#submitBtn').val('generate');
    },
    success: function(data) {
      $('#codeContainer').show();
      if (!data.error) {
        $('#className').text($('#classForm input[name=db_table]').val() + ' class');
        if (data.savePath) {
          $('#saveForm').addClass('inline');
          $('#saveForm input[name=saveCode]').val(data.code);
          $('#saveForm input[name=savePath]').val(data.savePath);
          $('#saveForm input[name=replaceFile]').val(0);
          $('#saveForm input[name=replaceBtn]')
                  .removeAttr('disabled')
                  .removeClass('active');
          
          $('#saveForm input[name=saveBtnLabel]').val('save to ' + data.saveLabel);
          $('#saveForm input[name=saveBtn]')
                  .val('save to ' + data.saveLabel)
                  .show()
                  .removeAttr('disabled')
                  .removeClass('btn-danger')
                  .addClass('btn-success');
        }else{
          $('#saveForm').removeClass('inline').hide();
        }
        $('#codeTools').show('slow');
        $('#codeContainer').html(data.formattedCode);
        $('#classCode').codify(true);
      } else {
        $('#codeContainer').html(data.error);
      }
    },
    error: function(data) {
      $('#codeContainer').html(data.responseText);
    }
  });
  return false;
});

$('#saveForm').submit(function() {
  if ($('#saveForm input[name=replaceBtn]').hasClass('active')) {
    $('#saveForm input[name=replaceFile]').val(1);
  }
  var formData = $(this).serialize();
  $.ajax({
    type: "POST",
    url: "action.php",
    data: formData,
    beforeSend: function() {
      $('#saveForm input[name=saveBtn]').val('saving...').attr('disabled', 'disabled');
      $('#saveForm input[name=replaceBtn]').attr('disabled', 'disabled');
    },
    success: function(data) {
      if (data) {
        $('#saveForm input[name=saveBtn]').val('saved');
      } else {
        $('#saveForm input[name=saveBtn]')
                .val('file already exists')
                .removeAttr('disabled')
                .removeClass('btn-success')
                .addClass('btn-danger');
        $('#saveForm input[name=replaceBtn]').removeAttr('disabled');
      }
    },
    error: function() {
    }
  });
  return false;
});

$('#selectClass').click(function() {
  if (window.getSelection) {
    var range = document.createRange();
    range.selectNode(document.getElementById('classCode'));
    window.getSelection().addRange(range);
  }
});

$('#saveForm input[name=replaceBtn]').click(function(){
  $('#saveForm input[name=saveBtn]')
          .removeClass('btn-danger')
          .addClass('btn-success')
          .val($('#saveForm input[name=saveBtnLabel]').val());
});