# Simple Web Registration Portal
A server side website that allows you to create a simple PHP-based web registattion portal.

## Structure
- websiteFiles folder: contains all php files of a Web registation portal
- database.sql: contains template MySql DB

## Requirements
- You can use any PHP+MySQL solution such as: LAMP, XAMP, OpenServer, etc.

## Instalallation steps
- Step 1: Database
The file creates a table called my_user_table with columns for my_id, my_login, my_password, my_fio, my_email, my_phone, my_address, and my_birthday. The table uses the MyISAM storage engine and the utf8mb4 character set and collation.
Following the creation of the table, the file inserts data into the my_user_table table.
Finally, the file sets the primary key and auto-increment values for the my_id column of the my_user_table table.

  1. Create a new DB from your MySql panel
  2. Import database.sql to it
  3. Add new username or use existing one
  4. Assign username for a DB

- Step 2: PHP files
  1. Copy localhost folder content into your local or remote web server folder
  2. Edit secret_folder/db_config.php file.
    2.1 Set USER, PASSWORD, HOST, DATABASE fields accoding to the information from Step 1.
  
- Step 3: Run Web Registaration Portal by entering it's domain name or IP-address into browser.

## Security measures used:
- Using password hashing:
  Function password_hash($my_password, PASSWORD_DEFAULT) takes variable $my_password and itself generates and adds to it "Salt" and encrypts it all   with "bcrypt" algorithm. This function is used in do_register.php and do_edit.php files.

- Checking input data for SQL injections and XSS vulnerabilities:
  
