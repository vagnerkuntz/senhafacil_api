CREATE DATABASE senhafacil;

use senhafacil;

CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    PRIMARY KEY(`id`)
);
ALTER TABLE `users` ADD `secret_mfa` varchar(255) NOT NULL;
ALTER TABLE `users`
ADD `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
ADD `updated_at` timestamp NULL ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

