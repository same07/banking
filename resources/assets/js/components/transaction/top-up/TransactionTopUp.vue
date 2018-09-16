<template>
	<div>
		<h3 class="mb-4">Deposit</h3>
        <div class="card">
			<div class="card-body">
				<transaction-top-up-form @depositSuccess="depositSuccess"></transaction-top-up-form>
			</div>
		</div>
	</div>
</template>

<script>
	import TransactionTopUpForm from './TransactionTopUpForm.vue'
	import {mapActions} from 'vuex';

	export default {
		components: {
            'transaction-top-up-form': TransactionTopUpForm
		},
		methods: {
			depositSuccess() {
				this.$router.push({name: 'transaction'});
			}
		}
	}
</script>