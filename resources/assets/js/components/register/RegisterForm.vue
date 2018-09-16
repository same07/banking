<template>
	<div>
		<form @submit.prevent="register">
            <div class="form-group">
				<label for="name">Name</label>
				<input
					type="text"
					class="form-control"
					:class="{'is-invalid' : error.name}"
					id="name"
					v-model="form.name"
					autocomplete="off"
					:disabled="loading"
				/>
				<div class="invalid-feedback" v-show="error.name">{{ error.name }}</div>
			</div>
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
				<label for="re_password">Re Password</label>
				<input
					type="password"
					class="form-control"
					:class="{'is-invalid' : error.re_password}"
					id="re_password"
					v-model="form.re_password"
					:disabled="loading"
				/>
				<div class="invalid-feedback" v-show="error.re_password">{{ error.re_password }}</div>
			</div>
            <div class="form-group">
				<label for="password">Phone</label>
				<input
					type="text"
					class="form-control"
					:class="{'is-invalid' : error.phone}"
					id="phone"
					v-model="form.phone"
					:disabled="loading"
				/>
				<div class="invalid-feedback" v-show="error.phone">{{ error.phone }}</div>
			</div>
            <div class="form-group">
				<label for="password">Address</label>
				<textarea
					class="form-control"
					:class="{'is-invalid' : error.address}"
					id="address"
					v-model="form.address"
					:disabled="loading"
				></textarea>
				<div class="invalid-feedback" v-show="error.address">{{ error.address }}</div>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-block" :disabled="loading">
					<span v-show="loading">Logging in</span>
					<span v-show="!loading">Register</span>
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
                    password: null,
                    address : null,
                    phone : null,
                    name : null,
                    re_password : null
				},
				error: {
					email: null,
                    password: null,
                    address: null,
                    phone : null,
                    name : null,
                    re_password : null
				}
			}
		},
		methods: {
			register() {
                if (this.form.password !== this.form.re_password) {
                    this.error.re_password = 'Password not match';
                    return false;
                }
				this.loading = true;
				axios.post(api.register, this.form)
					.then(res => {
                        this.$noty.success('Register Success!');
                        this.$emit('registerSuccess', res.data);
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
                this.error.phone = errors.phone ? errors.phone[0] : null;
                this.error.address = errors.address ? errors.address[0] : null;
                this.error.name = errors.name ? errors.name[0] : null;
                this.error.re_password = errors.re_password ? errors.re_password[0] : null;
			},
			clearErrors() {
				this.error.email = null;
                this.error.password = null;
                this.error.phone = null;
                this.error.address = null;
                this.error.name = null;
                this.error.re_password = null;
			}
		}
	}
</script>
