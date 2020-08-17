// bootstrap our js application
require('./bootstrap')

// include the complete Foundation js framework
import { Foundation } from 'foundation-sites'

require('./libs/fastclick')
require('./libs/smoothscroll')
require('./libs/parsley')

require('./components/search')
require('./components/recaptcha')


$(function () {

  // initialise foundation
  $(document).foundation()

})
