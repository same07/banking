<template>
	<div>
		<form @submit.prevent="saveRequest">
			<div class="form-group">
				<label for="type">Account Type</label>
				<v-select :options="options" label="name" v-model="data.selected">
                    <template slot="option" slot-scope="option">
                        {{ option.name }}
                    </template>
                </v-select>
			</div>
            <div class="form-group">
				<label for="pin">pin</label>
				<input
					type="text"
					class="form-control"
					:class="{'is-invalid' : error.pin}"
					id="pin"
					v-model="data.pin"
					:disabled="loading"
				/>
				<div class="invalid-feedback" v-show="error.pin">{{ error.pin }}</div>
                
			</div>
			<button type="submit" class="btn btn-primary" :disabled="loading">
				<span v-show="loading">Requesting</span>
				<span v-show="!loading">Request</span>
			</button>
		</form>
	</div>
</template>

<script>
	import {mapState} from 'vuex'
	import {api} from "../../../config";
    import VueSelect from 'vue-select';
	export default {
		data() {
			return {
				loading: false,
				error: {
                    pin : '',
					account_type_id: ''
                },
                data : {
                    pin : '',
					selected: ''
                },
                options : []
			};
        },
        components: {
            'v-select' : VueSelect
        },
        beforeMount() {
			this.loadAccount();
		},
		methods: {
            loadAccount() {
                axios.get(api.getAccountType)
					.then(res => {
						this.loading = false;
						this.options = res.data;
					})
					.catch(err => {
						(err.response.data.error) && this.$noty.error(err.response.data.error);
					});
            },
			saveRequest() {
                if (this.data.selected.id === undefined) {
                    this.$noty.error('Please select account type');
                    return false;
                }
                const parameter = {
                    pin : this.data.pin,
                    account_type_id : this.data.selected.id
                };
				this.loading = true;
				axios.post(api.newAccount, parameter)
					.then((res) => {
						this.loading = false;
						this.$noty.success('Thanks for your request, your account will be review in a day.');
						this.$emit('success');
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
				this.error.pin = errors.pin ? errors.pin[0] : null;
				this.error.account_type_id = errors.account_type_id ? errors.account_type_id[0] : null;
			},
			clearErrors() {
				this.error.pin = null;
				this.error.account_type_id = null;
			}
		}
	}
</script>
