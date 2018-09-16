<template>
	<div>
		<h3 class="mb-4">Transfer</h3>
        <div class="card">
			<div class="card-body">
				<transaction-transfer-form @transferSuccess="transferSuccess"></transaction-transfer-form>
			</div>
		</div>
	</div>
</template>

<script>
	import TransactionTransferForm from './TransactionTransferForm.vue'
	import {mapActions} from 'vuex';

	export default {
		components: {
            'transaction-transfer-form': TransactionTransferForm
		},
		methods: {
			transferSuccess() {
				this.$router.push({name: 'transaction'});
			}
		}
	}
</script>