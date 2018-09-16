<template>
	<div>
		<form @submit.prevent="confirmPassword">
			<div class="form-group">
				<label for="account">Account</label>
				<v-select :options="options" label="rekening_number" v-model="data.selected">
                    <template slot="option" slot-scope="option">
                        {{ option.rekening_number }}
                    </template>
                </v-select>
			</div>
			<div class="form-group">
				<label for="amount">Amount</label>
				<input
					type="text"
					class="form-control"
					:class="{'is-invalid' : error.amount}"
					id="amount"
					v-model="data.amount"
					:disabled="loading"
				/>
				<div class="invalid-feedback" v-show="error.amount">{{ error.amount }}</div>
                
			</div>
			<button type="submit" class="btn btn-primary" :disabled="loading">
				<span v-show="loading">Saving</span>
				<span v-show="!loading">Save</span>
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
					amount: '',
					customer_account_id: ''
                },
                data : {
                    amount: '',
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
                axios.get(api.getAccount)
					.then(res => {
						this.loading = false;
						this.options = res.data;
					})
					.catch(err => {
						(err.response.data.error) && this.$noty.error(err.response.data.error);
					});
            },
            confirmPassword() {
                this.$swal({
                    title: 'Please Input your password?',
                    input: 'password',
                    inputPlaceholder: 'Enter your password',
                    showCloseButton: true,
                    showLoaderOnConfirm: true,
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    preConfirm: (value) => {
                        return axios.post(api.checkPassword, {password : value})
                            .then((response) => {
                                return response.data;
                            })
                            .catch(err => {
                                this.$swal.showValidationError('Your Password is wrong!');
                            });
                    },
                    allowOutsideClick: () => !this.$swal.isLoading()
                }).then(
                    (result) => {
                        if (result.value) {
                            this.saveWithdraw();
                        }
                    }
                );
            },
			saveWithdraw() {
                if (this.data.selected.id === undefined) {
                    this.$noty.error('Please select your account number');
                    return false;
                }
                const parameter = {
                    amount : this.data.amount,
                    customer_account_id : this.data.selected.id
                };
				this.loading = true;
				axios.post(api.withdraw, parameter)
					.then((res) => {
						this.loading = false;
						this.$noty.success('Withdraw has been saved');
						this.$emit('withdrawSuccess');
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
				this.error.amount = errors.amount ? errors.amount[0] : null;
				this.error.customer_account_id = errors.customer_account_id ? errors.customer_account_id[0] : null;
			},
			clearErrors() {
				this.error.amount = null;
				this.error.customer_account_id = null;
			}
		}
	}
</script>
