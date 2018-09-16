<template>
	<div>
		<h3 class="mb-4">Account</h3>
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
                <b-button variant="danger" v-on:click="block(data.item.id)" :disabled="loading" v-if="data.item.status === 'active'">
                    Block
                </b-button>
                <b-button variant="success" v-on:click="active(data.item.id)" :disabled="loading" v-if="data.item.status === 'blocked'">
                    Active
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
                        key : 'status',
                        label : 'Status'
                    },
                    {
                        key : 'action',
                        label : 'Action'
                    }
                ],
			}
		},
		beforeMount() {
			this.getAccount();
		},
		methods : {
			getAccount() {
				this.loading = true;
				axios.get(api.allAccount)
					.then(res => {
						this.loading = false;
						this.items = res.data;
					})
					.catch(err => {
						(err.response.data.error) && this.$noty.error(err.response.data.error);
						this.loading = false;
					});
            },
            block(id) {
                this.loading = true;
				axios.post(api.blockAccount, { id : id})
					.then(res => {
                        this.loading = false;
                        this.$noty.success('Success');
                        this.getAccount();
					})
					.catch(err => {
						(err.response.data.error) && this.$noty.error(err.response.data.error);
						this.loading = false;
					});
            },
            active(id) {
                this.loading = true;
				axios.post(api.approveAccount, { id : id})
					.then(res => {
                        this.loading = false;
                        this.$noty.success('Success');
                        this.getAccount();
					})
					.catch(err => {
						(err.response.data.error) && this.$noty.error(err.response.data.error);
						this.loading = false;
					});
            }
		}
	}
</script>
