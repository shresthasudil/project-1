/* Create users table for the Number Guesser Game
 * id is a Primary Key for this table and set to Auto Increment.
 * first_name as user's first name
 * last_name as user's last name
 * email as user's email and also used for login into the site
 * password as user's login password.
*/
CREATE TABLE `users` (
	`id` INT(25) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`first_name` VARCHAR(70) NOT NULL,
	`last_name` VARCHAR(70) NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL);
