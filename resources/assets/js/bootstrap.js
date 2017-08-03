
window._ = require('lodash');
window.Vue = require('vue');

// Vue authorization helper method
Vue.prototype.authorize = function(handler){
	// can add additional admin privileges here
	let user = window.App.user;

	return user ? handler(user) : false;
};

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap-sass');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.App.csrfToken;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Events bus
window.events = new Vue();

window.flash = function(message){
	window.events.$emit('flash', message);
}