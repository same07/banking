<template>
	<div>
		<form @submit.prevent="login">
			<div class="form-group">
				<label for="email">Email</label>
				<input
					type="email"
					class="form-control"
					:class="{'is-invalid' : error.email}"
					id="email"
					v-model="form.email"
					autocomplete="off"
					:disabled="loading"
				/>
				<div class="invalid-feedback" v-show="error.email">{{ error.email }}</div>
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input
					type="password"
					class="form-control"
					:class="{'is-invalid' : error.password}"
					id="password"
					v-model="form.password"
					:disabled="loading"
				/>
				<div class="invalid-feedback" v-show="error.password">{{ error.password }}</div>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-block" :disabled="loading">
					<span v-show="loading">Logging in</span>
					<span v-show="!loading">Login</span>
				</button>
			</div>
		</form>
	</div>
</template>

<script>
	import {api} from "../../config";
	import jwtToken from "../../helpers/jwt-token";

	export default {
		data() {
			return {
				loading: false,
				form: {
					email: null,
					password: null
				},
				error: {
					email: null,
					password: null
				}
			}
		},
		methods: {
			login() {
				this.loading = true;
				const parameter = {
					client_secret : api.clientSecret,
					grant_type : 'password',
					client_id : api.clientId,
					username : this.form.email,
					password : this.form.password
				};
				axios.post(api.login, parameter)
					.then(res => {
						jwtToken.setToken(res.data.access_token);
						this.getProfile();
					})
					.catch(err => {
						(err.response.data.error) && this.$noty.error(err.response.data.error);

						(err.response.data.errors)
							? this.setErrors(err.response.data.errors)
							: this.clearErrors();

						this.loading = false;
					});
			},
			setErrors(errors) {
				this.error.email = errors.email ? errors.email[0] : null;
				this.error.password = errors.password ? errors.password[0] : null;
			},
			clearErrors() {
				this.error.email = null;
				this.error.password = null;
			},
			getProfile() {
				axios.get(api.currentUser)
					.then(res => {
						this.loading = false;
						this.$noty.success('Welcome back!');
						this.$emit('loginSuccess', res.data);
					})
					.catch(err => {
						(err.response.data.error) && this.$noty.error(err.response.data.error);

						(err.response.data.errors)
							? this.setErrors(err.response.data.errors)
							: this.clearErrors();

						this.loading = false;
					});
			}
		}
	}
</script>
