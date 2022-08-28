ALTER TABLE `user_requests` ADD `paradas` TEXT NULL AFTER `d_longitude`;

ALTER TABLE `user_requests` ADD `staticmap` TEXT NULL AFTER `paradas`;

ALTER TABLE `user_requests` ADD `cliente_volta` VARCHAR(255) NULL AFTER `paradas`;