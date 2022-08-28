ALTER TABLE `admin_wallet` CHANGE COLUMN `payment_mode` `payment_mode` ENUM(
		'BRAINTREE',
		'CASH',
		'CARD',
		'PAYPAL',
		'PAYPAL-ADAPTIVE',
		'PAYUMONEY',
		'PAYTM',
		'DEBIT_MACHINE',
		'VOUCHER',
		'CONTRACT'
	) NOT NULL;
ALTER TABLE `fleet_wallet` CHANGE COLUMN `payment_mode` `payment_mode` ENUM(
		'BRAINTREE',
		'CASH',
		'CARD',
		'PAYPAL',
		'PAYPAL-ADAPTIVE',
		'PAYUMONEY',
		'PAYTM',
		'DEBIT_MACHINE',
		'VOUCHER',
		'CONTRACT'
	) NOT NULL;
ALTER TABLE `provider_wallet` CHANGE COLUMN `payment_mode` `payment_mode` ENUM(
		'BRAINTREE',
		'CASH',
		'CARD',
		'PAYPAL',
		'PAYPAL-ADAPTIVE',
		'PAYUMONEY',
		'PAYTM',
		'DEBIT_MACHINE',
		'VOUCHER',
		'CONTRACT'
	) NOT NULL;
ALTER TABLE `users` CHANGE COLUMN `payment_mode` `payment_mode` ENUM(
		'BRAINTREE',
		'CASH',
		'CARD',
		'PAYPAL',
		'PAYPAL-ADAPTIVE',
		'PAYUMONEY',
		'PAYTM',
		'DEBIT_MACHINE',
		'VOUCHER',
		'CONTRACT'
	) NOT NULL;
ALTER TABLE `user_wallet` CHANGE COLUMN `payment_mode` `payment_mode` ENUM(
		'BRAINTREE',
		'CASH',
		'CARD',
		'PAYPAL',
		'PAYPAL-ADAPTIVE',
		'PAYUMONEY',
		'PAYTM',
		'DEBIT_MACHINE',
		'VOUCHER',
		'CONTRACT'
	) NOT NULL;
ALTER TABLE `documents`
ADD COLUMN `shown_to` VARCHAR(50) NOT NULL DEFAULT 'Motoboy'
AFTER `type`;