<template>
	<div>
		<h3 class="mb-4">Request Card</h3>
        <div class="card">
			<div class="card-body">
				<transaction-top-up-form @success="success"></transaction-top-up-form>
			</div>
		</div>
	</div>
</template>

<script>
	import TransactionRequestCardForm from './TransactionRequestCardForm.vue'
	import {mapActions} from 'vuex';

	export default {
		components: {
            'transaction-top-up-form': TransactionRequestCardForm
		},
		methods: {
			success() {
				this.$router.push({name: 'transaction'});
			}
		}
	}
</script>