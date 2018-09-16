import Home from './components/home/Home.vue';
import Login from './components/login/Login.vue';
import ProfileWrapper from './components/profile/ProfileWrapper.vue';
import Profile from './components/profile/Profile.vue';
import EditProfile from './components/profile/edit-profile/EditProfile.vue';
import EditPassword from './components/profile/edit-password/EditPassword.vue';
import TransactionWrapper from './components/transaction/TransactionWrapper.vue';
import Transaction from './components/transaction/Transaction.vue';
import TransactionTopUp from './components/transaction/top-up/TransactionTopUp.vue';
import TransactionWithdraw from './components/transaction/withdraw/TransactionWithdraw.vue';
import TransactionTransfer from './components/transaction/transfer/TransactionTransfer.vue';
import TransactionMutation from './components/transaction/mutation/TransactionMutation.vue';
import TransactionRequestCard from './components/transaction/request/TransactionRequestCard.vue';
import Register from './components/register/Register.vue';

import CustomerWrapper from './components/customer/CustomerWrapper.vue';
import Customer from './components/customer/Customer.vue';
import CustomerAccount from './components/customer/account/CustomerAccount.vue';
import CardRequest from './components/customer/card/CardRequest.vue';

export default [
	{
		path: '/',
		name: 'index',
		component: Home,
		meta: {}
	},
	{
		path: '/login',
		name: 'login',
		component: Login,
		meta: {requiresGuest: true}
	},
	{
		path: '/register',
		name: 'register',
		component: Register,
		meta: {requiresGuest: true}
	},
	{
		path: '/profile',
		component: ProfileWrapper,
		children: [
			{
				path: '',
				name: 'profile',
				component: Profile,
				meta: {requiresAuth: true}
			},
			{
				path: 'edit-profile',
				name: 'profile.editProfile',
				component: EditProfile,
				meta: {requiresAuth: true}
			},
			{
				path: 'edit-password',
				name: 'profile.editPassword',
				component: EditPassword,
				meta: {requiresAuth: true}
			},
			{
				path: '*',
				redirect: {
					name: 'profile'
				}
			}
		]
	},
	{
		path : '/transaction',
		component: TransactionWrapper,
		children : [
			{
				path: '',
				name: 'transaction',
				component: Transaction,
				meta: {requiresAuth: true}
			},
			{
				path: 'top-up',
				name: 'transaction.topUp',
				component: TransactionTopUp,
				meta: {requiresAuth: true}
			},
			{
				path: 'withdraw',
				name: 'transaction.withdraw',
				component: TransactionWithdraw,
				meta: {requiresAuth: true}
			},
			{
				path: 'transfer',
				name: 'transaction.transfer',
				component: TransactionTransfer,
				meta: {requiresAuth: true}
			},
			{
				path: 'mutation',
				name: 'transaction.mutation',
				component: TransactionMutation,
				meta: {requiresAuth: true}
			},
			{
				path: 'request_card',
				name: 'transaction.request_card',
				component: TransactionRequestCard,
				meta: {requiresAuth: true}
			},
			{
				path: '*',
				redirect: {
					name: 'transaction'
				}
			}
		]
	},
	{
		path : '/customer',
		component: CustomerWrapper,
		children : [
			{
				path: '',
				name: 'customer',
				component: Customer,
				meta: {requiresAuth: true}
			},
			{
				path: 'account',
				name: 'customer.account',
				component: CustomerAccount,
				meta: {requiresAuth: true}
			},
			{
				path: 'account_request',
				name: 'customer.account_request',
				component: CardRequest,
				meta: {requiresAuth: true}
			},
			{
				path: '*',
				redirect: {
					name: 'customer'
				}
			}
		]
	}
];