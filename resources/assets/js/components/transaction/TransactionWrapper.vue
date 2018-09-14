<template>
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-3">
				<nav class="nav flex-column nav-pills mb-4">
					<router-link :to="{name: 'transaction'}" class="nav-link" activeClass="active" exact>
						View Dashboard
					</router-link>
					<router-link :to="{name: 'transaction.topUp'}" class="nav-link" activeClass="active" exact>
						Top Up
					</router-link>
					<router-link :to="{name: 'transaction.withdraw'}" class="nav-link" activeClass="active" exact>
						Withdraw
					</router-link>
                    <router-link :to="{name: 'transaction.mutation'}" class="nav-link" activeClass="active" exact>
						Mutation
					</router-link>
				</nav>
			</div>
			<div class="col-12 col-md-9">
				<transition name="fade" mode="out-in">
					<router-view></router-view>
				</transition>
			</div>
		</div>
	</div>
</template>

<script>
	export default {}
</script>
