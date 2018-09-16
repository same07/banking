import $ from 'jquery';
import Vue from 'vue';
import VueNoty from 'vuejs-noty';
import axios from 'axios';
import VueSweetalert2 from 'vue-sweetalert2';
import BootstrapVue from 'bootstrap-vue'


window.$ = window.jQuery = $;
window.axios = axios;
require('bootstrap');

Vue.use(VueNoty, {
	progressBar: false,
	layout: 'bottomRight',
	theme: 'bootstrap-v4',
	timeout: 3000
});
Vue.use(VueSweetalert2);
Vue.use(BootstrapVue);

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import router from './router';
import store from './store/index';
import App from './components/App.vue';
import jwtToken from './helpers/jwt-token';

axios.interceptors.request.use(config => {
	config.headers['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
	config.headers['X-Requested-With'] = 'XMLHttpRequest';

	if (jwtToken.getToken()) {
		config.headers['Authorization'] = 'Bearer ' + jwtToken.getToken();
	}

	return config;
}, error => {
	return Promise.reject(error);
});

axios.interceptors.response.use(response => {
	return response;
}, error => {
	let errorResponseData = error.response.data;

	const errors = ["token_invalid", "token_expired", "token_not_provided", "Unauthorized."];

	if (errorResponseData.error && errors.includes(errorResponseData.error)) {
		store.dispatch('unsetAuthUser')
			.then(() => {
				jwtToken.removeToken();
				router.push({name: 'login'});
			});
	}

	return Promise.reject(error);
});

Vue.component('app', App);

const app = new Vue({
	router,
	store
}).$mount('#app');
