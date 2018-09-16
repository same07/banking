const apiDomain = Laravel.apiDomain + '/api';
export const siteName = Laravel.siteName;

export const api = {
	clientSecret : 'uSCVtUnO49yEfUFlKrY6vOnqGLbidterN6tXm9Ba',
	clientId : 2,
	login: '/oauth/token',
	register : apiDomain + '/register',
	checkPassword : apiDomain + '/user/check/password',
	currentUser: apiDomain + '/user',
	getAccount : apiDomain + '/user/account',
	deposit : apiDomain + '/transaction/deposit',
	withdraw : apiDomain + '/transaction/withdraw',
	transfer : apiDomain + '/transaction/transfer',
	checkRekening : apiDomain + '/transaction/account/check',
	mutation : apiDomain + '/transaction/mutation',
	newAccount : apiDomain + '/customer/request',
	getAccountType : apiDomain + '/account/type',

	getBankSaldo : apiDomain + '/bank/saldo',
	getTotalCustomer : apiDomain + '/bank/customer/total',
	getCustomer : apiDomain + '/bank/customer',
	findAccount : apiDomain + '/bank/account/find',
	allAccount : apiDomain + '/bank/account',
	requestAccount : apiDomain + '/bank/account/request',
	approveAccount : apiDomain + '/bank/account/approve',
	blockAccount : apiDomain + '/bank/account/block',

	updateUserProfile: apiDomain + '/user/profile/update',
	updateUserPassword: apiDomain + '/user/password/update'
};
