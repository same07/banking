<template>
	<div>
		<h3 class="mb-4">Mutation</h3>
        <form @submit.prevent="selectAccount">
			<div class="form-group">
				<label for="account">Account</label>
				<v-select :options="options" label="rekening_number" v-model="data.selected">
                    <template slot="option" slot-scope="option">
                        {{ option.rekening_number }}
                    </template>
                </v-select>
			</div>
            <button type="submit" class="btn btn-primary" :disabled="loading">
				<span v-show="loading">Please Wait</span>
				<span v-show="!loading">Select</span>
			</button>
        </form>
        <h3>Report for account {{ data.selected.rekening_number }}</h3>
        <b-table striped hover :items="items" :fields="fields">
            <template slot="debit" slot-scope="data">
                <span v-if="data.item.debit > 0">{{ data.item.debit }}</span>
            </template>
            <template slot="credit" slot-scope="data">
                <span v-if="data.item.credit > 0">{{ data.item.credit }}</span>
            </template>
        </b-table>
	</div>
</template>

<script>
	import {mapActions} from 'vuex';
    import {api} from "../../../config";
    import VueSelect from 'vue-select';
	export default {
        data() {
			return {
				loading: false,
                data : {
					selected: ''
                },
                options : [],
                items : [],
                fields: [
                    {
                        key : 'created_at',
                        label : 'Date'
                    },
                    {
                        key : 'credit',
                        label : 'Debit'
                    },
                    {
                        key : 'debit',
                        label : 'Credit'
                    },
                    {
                        key : 'saldo',
                        label : 'Saldo'
                    },
                ],
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
            selectAccount() {
                if (this.data.selected.id === undefined) {
                    this.$noty.error('Please select your account number');
                    return false;
                }
                this.loading = true;
                const parameter = {
                    customer_account_id : this.data.selected.id
                };
                axios.post(api.mutation, parameter)
					.then(res => {
                        this.loading = false;
                        let saldo = 0;
                        res.data.transaction_detail.forEach(element => {
                            if (element.credit > 0) {
                                saldo -= element.credit*1;
                            } else {
                                saldo += element.debit*1;
                            }
                            element.saldo = parseFloat(saldo).toFixed(2);
                        });
						this.items = res.data.transaction_detail;
					})
					.catch(err => {
						(err.response.data.error) && this.$noty.error(err.response.data.error);
					});
            }
		}
	}
</script>