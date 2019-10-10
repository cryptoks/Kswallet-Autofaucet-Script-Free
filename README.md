# Kswallet - Cryptocurrency Autofaucet Free

[![N|Solid](https://kswallet.net/images/logo.png)](https://kswallet.net/images/logo.png)



Autofaucet script created from Kswallet Dev's for free.
https://www.kswallet.net

For Kswallet API KEYS please contact us in this email:
contact@kswallet.net

# Features
  - Super Secure System
  - Use URL Shorteners (for more profit)
  - Amazing ADS system (You will x2 your earnings)
  - AntiadBlock script
  - Iphub Integration
  - Cool Referal System
  - Ban address Option
  - Ban Ip Option
  - PopAds File
  - Manage rewards,timer,user session,referesh timer easly
  - SEO friendly
  - PDO Usage
  - Clean and fast Code
  
### How to install Autofaucet
First you need to create a table in mysql name it whatever name you want and upload this SQL file
```sh
sql/db_table.sql
```
After this step setup your database
```sh
database/db.php
```

After database connection now you have to work in file and give all needed informations (API KEYS,website informations,rewards,percentages,timers,sessions etc.)
```sh
settings/configs.php
```
If you are using SSL please go to
```sh
libs/solvemedia.php
```
and in line 116 define $use_ssl = true
```php
function solvemedia_get_html ($pubkey, $error = null, $use_ssl = false)
```

### How can i add ads ?
You can add ads in file
```sh
extensions/ads.php
```
for example you want to add more than 2 ads in one place
```php
$ads468x60_1 = array('<div class="ui banner test ad" data-text="ads1"></div>','<div class="ui banner test ad" data-text="ads2"></div>');
```
You can add pop ads in file
```sh
extensions/popads.php
```

### How can i Ban someone Address or IP
If you want to ban someone from using your autfaucet you should go to
```sh
banned/ban_address.php
```
or
```sh
banned/ban_ip.php
```
and just add needed informations in array.



License
----

MIT
