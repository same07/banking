<template>
	<div>
		<h3 class="mb-4">Withdraw</h3>
        <div class="card">
			<div class="card-body">
				<transaction-withdraw-form @withdrawSuccess="withdrawSuccess"></transaction-withdraw-form>
			</div>
		</div>
	</div>
</template>

<script>
	import TransactionWithdrawForm from './TransactionWithdrawForm.vue'
	import {mapActions} from 'vuex';

	export default {
		components: {
            'transaction-withdraw-form': TransactionWithdrawForm
		},
		methods: {
			withdrawSuccess() {
				this.$router.push({name: 'transaction'});
			}
		}
	}
</script>