<template>
	<div>
		<h3 class="mb-4">Account Request</h3>
		<b-table striped hover :items="items" :fields="fields">
            <template slot="customer" slot-scope="data">
                {{ data.value.name}}
            </template>
            <template slot="phone" slot-scope="data">
                {{ data.item.customer.phone}}
            </template>
            <template slot="type" slot-scope="data">
                {{ data.value.name }}
            </template>
            <template slot="action" slot-scope="data">
                <b-button v-on:click="approve(data.item.id)" :disabled="loading">
                    Approve
                </b-button>
            </template>
        </b-table>
	</div>
</template>

<script>
	import {mapState} from 'vuex'
	import {api} from "../../../config";

	export default {
		data() {
			return {
				loading : false,
                items : [],
                fields: [
                    {
                        key : 'customer',
                        label : 'Customer Name'
                    },
                    {
                        key : 'phone',
                        label : 'Customer Phone'
                    },
                    {
                        key : 'card_number',
                        label : 'Card Number'
                    },
                    {
                        key : 'type',
                        label : 'Type'
                    },
                    {
                        key : 'action',
                        label : 'Action'
                    }
                ],
			}
		},
		beforeMount() {
			this.getRequestAccount();
		},
		methods : {
			getRequestAccount() {
				this.loading = true;
				axios.get(api.requestAccount)
					.then(res => {
						this.loading = false;
						this.items = res.data;
					})
					.catch(err => {
						(err.response.data.error) && this.$noty.error(err.response.data.error);
						this.loading = false;
					});
            },
            approve(id) {
                this.loading = true;
				axios.post(api.approveAccount, { id : id})
					.then(res => {
                        this.loading = false;
                        this.$noty.success('Success');
                        this.getRequestAccount();
					})
					.catch(err => {
						(err.response.data.error) && this.$noty.error(err.response.data.error);
						this.loading = false;
					});
            }
		}
	}
</script>
