if($('#token').length) {
  grecaptcha.ready(function(){
    grecaptcha.execute('6Lcb4qwZAAAAAC97oOCfqwsliZ74b3veJJl1fXZn', {action: 'submit'}).then(
      function(token) {
        console.log(token);
        $('#token').val(token);
      });
  });
}