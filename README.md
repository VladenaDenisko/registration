# Simple Web Registration Portal
A server side website that allows you to create a simple PHP-based web registattion portal.

## Structure
- localhost: contains all php files of a Web registation portal
- database.sql: contains template MySql DB

## Requirements
- You can use any PHP+MySQL solution such as: LAMP, XAMP, OpenServer, etc.

## Instalallation steps
- Step 1: Database
  1. Create a new DB from your MySql panel
  2. Import database.sql to it
  3. Add new username or use existing one
  4. Assign username for a DB

- Step 2: PHP files
  1. Copy localhost folder content into your local or remote web server folder
  2. Edit secret_folder/db_config.php file.
    2.1 Set USER, PASSWORD, HOST, DATABASE fields accoding to the information from Step 1.
  
- Step 3: Run Web Registaration Portal by entering it's domain name or IP-address into browser.
