<template>
	<div>
		<form @submit.prevent="confirmTransfer">
			<div class="form-group">
				<label for="account">Account</label>
				<v-select :options="options" label="rekening_number" v-model="data.selected">
                    <template slot="option" slot-scope="option">
                        {{ option.rekening_number }}
                    </template>
                </v-select>
			</div>
			<div class="form-group">
				<label for="rekening_number">Account Number</label>
				<input
					type="text"
					class="form-control"
					:class="{'is-invalid' : error.rekening_number}"
					id="rekening_number"
					v-model="data.rekening_number"
					:disabled="loading"
				/>
				<div class="invalid-feedback" v-show="error.rekening_number">{{ error.rekening_number }}</div>
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
				<span v-show="loading">Checking</span>
				<span v-show="!loading">Transfer</span>
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
                    customer_account_id: '',
                    rekening_number : ''
                },
                data : {
                    amount: '',
                    rekening_number : '',
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
            confirmTransfer() {
                if (this.data.selected.id === undefined) {
                    this.$noty.error('Please select your account number');
                    return false;
                }
                this.loading = true;
                const parameter = {
                    'rekening_number' : this.data.rekening_number
                };
                axios.post(api.checkRekening, parameter)
					.then((res) => {
                        this.$swal({
                            title: 'Are you sure?',
                            html: "<p>Account Owner : <b>" + res.data + "</b></p><p>Total Transfer : <b>" + this.data.amount + "</b></p>",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, Transfer!'
                        }).then((result) => {
                            if (result.value) {
                                this.saveTransfer();
                            }
                            if (result.dismiss) {
                                this.loading = false;
                            }
                        });
					})
					.catch(err => {
                        this.$swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Account number not found',
                        })
						this.loading = false;
					});
            },
			saveTransfer() {
                
                const parameter = {
                    amount : this.data.amount,
                    customer_account_id : this.data.selected.id,
                    rekening_number : this.data.rekening_number
                };
				this.loading = true;
				axios.post(api.transfer, parameter)
					.then((res) => {
						this.loading = false;
						this.$noty.success('Transfer Success');
						this.$emit('transferSuccess');
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
                this.error.rekening_number = errors.rekening_number ? errors.rekening_number[0] : null;
				this.error.customer_account_id = errors.customer_account_id ? errors.customer_account_id[0] : null;
			},
			clearErrors() {
				this.error.amount = null;
                this.error.customer_account_id = null;
                this.error.rekening_number = null;
			}
		}
	}
</script>
