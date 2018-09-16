<template>
	<div>
		<h3 class="mb-4">Customer</h3>
		<b-table striped hover :items="items" :fields="fields">
        </b-table>
	</div>
</template>

<script>
	import {mapState} from 'vuex'
	import {api} from "../../config";

	export default {
		data() {
			return {
				loading : false,
                items : [],
                fields: [
                    {
                        key : 'name',
                        label : 'Name'
                    },
                    {
                        key : 'email',
                        label : 'Email'
                    },
                    {
                        key : 'phone',
                        label : 'Phone'
                    },
                    {
                        key : 'address',
                        label : 'Address'
                    }
                ],
			}
		},
		beforeMount() {
			this.getCustomer();
		},
		methods : {
			getCustomer() {
				this.loading = true;
				axios.get(api.getCustomer)
					.then(res => {
						this.loading = false;
						this.items = res.data;
					})
					.catch(err => {
						(err.response.data.error) && this.$noty.error(err.response.data.error);
						this.loading = false;
					});
			}
		}
	}
</script>
