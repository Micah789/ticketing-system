import Parsley from 'parsleyjs'

window.Parsley.on('form:validate', function (e) {
  // disabled the submit button to stop double submissions
  e.$element.find('button[type="submit"]').attr('disabled', true).addClass('is-loading')
});

window.Parsley.on('form:validated', function (e) {

  console.log('form:validated')

  if (!e.validationResult) {

    // disabled the submit button to stop
    e.$element.find('button[type="submit"]').attr('disabled', false).removeClass('is-loading')

  }
});
