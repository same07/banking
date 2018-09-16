<template>
	<div class="container">
		<div class="jumbotron">
			<h1 class="display-4" v-once>{{siteName}}</h1>
			<p class="lead">Bank System</p>
		</div>
		<div class="row" v-if="role === 'manager-bank'">
			<div class="col-4">
				<div class="card">
					<ul class="list-group list-group-flush">
						<li class="list-group-item">
							<b>Saldo on Bank</b>
							<p class="lead" v-if="!loading">{{ saldo }}</p>
							<p class="lead" v-if="loading">Please Wait...</p>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-4">
				<div class="card">
					<ul class="list-group list-group-flush">
						<li class="list-group-item">
							<b>Total Customer</b>
							<p class="lead">{{ customer }}</p>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import {siteName} from './../../config';
	import {mapGetters} from 'vuex';
	import {api} from "../../config";
	export default {
		data() {
			return {
				siteName: siteName,
				saldo : 0,
				loading : false,
				customer : 0
			}
		},
		computed: mapGetters([
			'isLoggedIn',
			'role'
		]),
		beforeMount() {
			this.getSaldo();
			this.getCustomer();
		},
		methods : {
			getSaldo() {
				this.loading = true;
				axios.get(api.getBankSaldo)
					.then(res => {
						this.loading = false;
						this.saldo = res.data;
					})
					.catch(err => {
						this.loading = false;
					});
			},
			getCustomer() {
				axios.get(api.getTotalCustomer)
					.then(res => {
						this.customer = res.data;
					})
					.catch(err => {
					});
			}
		}
	}
</script>
