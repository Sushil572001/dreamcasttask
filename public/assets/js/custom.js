$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

function handleServerError(formName, errors) {
    $("form[name='" + formName + "']").find('.text-danger.serverside_error').remove();
    $.each(errors, function (field, messages) {
        var $input = $("form[name='" + formName + "']").find("[name='" + field + "']");
        $.each(messages, function (index, message) {
            $input.after('<span class="text-danger serverside_error">' + message + '</span>');
        });
    });
}

$.validator.addMethod('email_rule', function (value, element) {
  var emailRegex = /^([a-zA-Z0-9_\-\.]+)\+?([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
  return this.optional(element) || emailRegex.test(value);
}, 'Please enter a valid Email.');

$(document).on('keypress', '.only_number', function (e) {
  var charCode = (e.which) ? e.which : event.keyCode
  if (String.fromCharCode(charCode).match(/[^0-9]/g))
    return false;
});