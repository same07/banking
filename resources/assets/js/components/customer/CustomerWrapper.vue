<template>
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-3">
				<nav class="nav flex-column nav-pills mb-4">
					<router-link :to="{name: 'customer'}" class="nav-link" activeClass="active" exact>
						Customer
					</router-link>
					<router-link :to="{name: 'customer.account'}" class="nav-link" activeClass="active" exact>
						Account Maintenance
					</router-link>
					<router-link :to="{name: 'customer.account_request'}" class="nav-link" activeClass="active" exact>
						Account Request
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
