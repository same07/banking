<template>
	<div>
		<h3 class="mb-4">Dashboard</h3>
		<p>
			<b-button :to="{name: 'transaction.request_card'}">
				Request New Card
			</b-button>
		</p>
		<div class="row">
			<div class="col-4" v-for="(item, index) in data">
				<div class="card">
					<div class="card-header">
						{{ item.card_number }}
							<span class="badge badge-pill badge-primary" v-if="item.status === 'active'">{{ item.status }}</span>
							<span class="badge badge-pill badge-danger" v-if="item.status !== 'active'">{{ item.status }}</span>
					</div>
						<ul class="list-group list-group-flush">
							<li class="list-group-item">
								<b>Account Number</b>
								<p class="lead">{{ item.rekening_number }}</p>
								<hr/>
								<b>Saldo</b>
								<p class="lead">{{ item.total_saldo }}</p>
							</li>
						</ul>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import {mapState} from 'vuex'
	import {api} from "../../config";

	export default {
		data() {
			return {
				loading : false,
				data : []
			}
		},
		beforeMount() {
			this.getSaldo();
		},
		methods : {
			getSaldo() {
				this.loading = true;
				axios.get(api.getAccount)
					.then(res => {
						this.loading = false;
						this.data = res.data;
					})
					.catch(err => {
						(err.response.data.error) && this.$noty.error(err.response.data.error);
						this.loading = false;
					});
			},
			requestCard() {
				alert('asd');
			}
		}
	}
</script>
