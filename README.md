Password Protected
==================

A very simple way to quickly password protect your WordPress site with a single password.

This plugin only protects your WordPress generated content. It **does not protect images or uploaded files** so if you enter and exact URL to in image file it will still be accessible.

Features include:

- Password protect your WordPress site with a single password.
- Option to allow access to feeds.
- Option to allow administrators access without entering password.
- *New* 👉 Now you can customize the whole password protected screen including the background, font, logo, color e.t.c.

> Please note, this plugin works by setting a cookie to allow access to the site. If you are using a caching plugin or web hosting such as WP Engine that has in-built caching, you will need to configure the caching service to be disabled if the Password Protected cookie is set.

Translations
------------

If you would like to translate this plugin you can easily contribute at the [Translating WordPress](https://translate.wordpress.org/projects/wp-plugins/password-protected/) page. The stable plugin needs to be 95% translated for a language file to be available to download/update via WordPress.

Installation
------------

To install and configure this plugin...

1. Upload or install the plugin through your WordPress admin.
1. Activate the plugin via the 'Plugins' admin menu.
1. Configure the password options in the Password Protected settings.

Upgrading
---------

If you are upgrading manually via FTP rather that through the WordPress automatic upgrade link, please de-activate and re-activate the plugin to ensure the plugin upgrades correctly.

Frequently Asked Questions
--------------------------

__How can I change the WordPress logo to a different image?__  
Install and configure the [Login Logo](https://wordpress.org/plugins/login-logo/) plugin by Mark Jaquith or the [Uber Login Logo](https://wordpress.org/plugins/uber-login-logo/) plugin. This will change the logo on your password entry page AND also your admin login page.

__How can I enable feeds while the site is password protected?__  
In the settings, check the 'Allow Feeds' checkbox.

__Can I prevent administrators having to enter password?__  
In the settings, check the 'Allow Administrators' checkbox.

__I cannot preview my changes in the Theme Customizer__  
You must be an administrator (have the manage_options capability) and in the Password Protected settings, check the 'Allow Administrators' checkbox.

__How can I log out?__  
Just add a "password-protected=logout" query to your URL.
eg. http://www.example.com/?password-protected=logout

__I have forgotten the password. How can I disable the plugin?__
If you go to your WordPress admin login page `/wp-login.php` and it shows the admin login fields, you should still be able to login and disable the plugin.

If the admin login screen insteads shows the Password Protected field, you will need to access your site via SFTP/SSH and delete the Password Protected plugin folder in the plugins folder `wp-content/plugins/password-protected`.

__How can I redirect to a different domain name when logging out?__  
If passing a redirect URL using 'redirect_to' when logging out you need you may need to use the [allowed domain names](https://codex.wordpress.org/Plugin_API/Filter_Reference/allowed_redirect_hosts) filter to allow redirecting to an external domain.

__Where can I report bugs and issues?__  
Please log issues and bugs on the plugin's [GitHub page](https://github.com/benhuson/password-protected/issues).
You can also submit suggested enhancements if you like.

__How can I contribute?__  
If you can, please [fork the code](https://github.com/benhuson/password-protected) and submit a pull request via GitHub. If you're not comfortable using Git, then please just submit it to the issues link above.

__How can I translate this plugin?__  
If you would like to translate this plugin you can easily contribute at the [Translating WordPress](https://translate.wordpress.org/projects/wp-plugins/password-protected/) page. The stable plugin needs to be 95% translated for a language file to be available to download/update via WordPress.

Upgrade Notice
--------------
### 2.5.3
- Improved Settings HTML structure
- Added Note regarding compatibility with login designer within dashboard

### 2.5.2
Made compatibility with [login designer](https://wp.org/plugins/login-designer); Now you can customize the password-protected screen with the customizer using login designer plugin.

### 2.5
Fixes robots tag for WordPress 5.7+

### 2.4
Fixes to help with caching issues and favicon on login page.

### 2.3
Fixed an issue with "testcookie" on some hosts. Added `password_protected_cookie_name` and `password_protected_options_page_capability` filters.

### 2.2.5
Added `password_protected_login_password_title` filter to allow customizing the "Password" label on the login form.

### 2.2.4
Check that `$_SERVER['REMOTE_ADDR']` is set.

### 2.2.3
Restrict REST-API-access only if password protection is active. Added viewport meta tag to login page.

### 2.2.2
Fix REST option and always allow access to REST API for logged in users. Change locked admin bar icon to green.

### 2.2.1
Fixed PHP error when calculating cookie expiration date.

### 2.2
Added admin bar icon to indicate wether password protection is enabled/disabled. Options to enable REST API access and show "Remember me" checkbox.

### 2.1
Update caching notes for WP Engine and W3 Total Cache plugin.

### 2.0.3
Show user's IP address beside "Allow IP Addresses" admin setting. Declare methods as public or private and use PHP5 constructors.

### 2.0.2
Only redirect to [allowed domain names](https://codex.wordpress.org/Plugin_API/Filter_Reference/allowed_redirect_hosts) when logging out.

### 2.0.1
Security fix: Use a more complex password hash for cookie key.

### 2.0
Added 'password_protected_logout_link' shortcode and use 'password-protected-login.css' in theme folder if it exists.

### 1.9
Fixed "Allow Users" functionality and added option to allowed IP addresses which can bypass the password protection.

### 1.8
Support for adding "password-protected-login.php" in theme directory and allow filtering of the 'redirect to' URL via the 'password_protected_login_redirect_url' filter.

### 1.7.2
Added 'password_protected_login_redirect' filter.

### 1.7.1
Fix login template compatibility for WordPress 3.9

### 1.7
Added 'password_protected_theme_file' filter and option to allow logged in users.

### 1.6.2
Allow redirection to a different URL when logging out.

### 1.6
Added support for Uber Login Logo plugin.

### 1.5
Fixes an open redirect vulnerability. Settings now have own page.

### 1.4
Administrators can use the site without logging in. WordPress 3.5 compatible.

### 1.3
Allow access to feeds. Ready for translation.

### 1.2
Use cookies instead of sessions.

### 1.1
Passwords saved encrypted.

Changelog
---------

View a list of all plugin changes in [CHANGELOG.md](https://github.com/wpexpertsio/password-protected/blob/master/CHANGELOG.md).
