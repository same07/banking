<template>
	<div class="container">
		<div class="row">
            <div class="col-12 col-md-6 col-lg-8">
				<div class="page-header">
					<h1 class="display-4">Register</h1>
				</div>
				<div class="py-4">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur corporis deserunt, dolorem eligendi esse eum illo illum inventore libero minus nam numquam officiis praesentium quas quasi repudiandae sed tempore voluptatibus?</p>
				</div>
			</div>
			<div class="col-12 col-md-6 col-lg-4">
				<div class="card">
					<div class="card-body">
						<register-form @registerSuccess="registerSuccess"></register-form>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import RegisterForm from './RegisterForm.vue'
	export default {
		components: {
			'register-form': RegisterForm
		},
		methods: {
			registerSuccess() {
				this.$router.push({name: 'index'});
			}
		}
	}
</script>
