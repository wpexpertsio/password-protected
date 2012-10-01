=== Password Protected ===
Contributors: husobj
Donate link: http://www.benhuson.co.uk/donate/
Tags: password, protect, password protect, login
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 1.3
License: GPLv2 or later

A very simple way to quickly password protect your WordPress site with a single password. Integrates seamlessly into your WordPress privacy settings.

== Description ==

A very simple way to quickly password protect your WordPress site with a single password. Integrates seamlessly into your WordPress privacy settings.

Features include:

* Password protect your WordPress site with a single password.
* Integrates seamlessly into your WordPress privacy settings.
* Works with Mark Jaquith's [Login Logo](http://wordpress.org/extend/plugins/login-logo/) plugin.
            
== Installation ==

To install and configure this plugin...

1. Upload or install the plugin through your WordPress admin.
2. Activate the plugin via the 'Plugins' admin menu.
3. Configuration the password in your WordPress Privacy settings.

= Upgrading =

If you are upgrading manually via FTP rather that through the WordPress automatic upgrade link, please de-activate and re-activate the plugin to ensure the plugin upgrades correctly.

== Frequently Asked Questions ==

= How can I change the Wordpress logo to a different image? =

Install and configure the [Login Logo](http://wordpress.org/extend/plugins/login-logo/) plugin by Mark Jaquith. This will change the logo on your password entry page AND also your admin login page.

= How can I enable feeds while the site is password protected? =

In the settings, check the 'Allow Feeds' checkbox.

= How can I log out? =

Just add a "password-protected=logout" query to your URL.
eg. http://www.example.com/?password-protected=logout

= Where can I report bugs and issues? =

Please log issues and bugs on the plugin's [GitHub page](https://github.com/benhuson/password-protected/issues).
You can also submit suggested enhancements if you like.

= How can I contribute? =

If you can, please [fork the code](https://github.com/benhuson/password-protected) and submit a pull request via GitHub. If you're not comfortable using Git, then please just submit it to the issues link above.

== Screenshots ==

1. Login page perfectly mimicks the WordPress login.
2. Integrates seamlessly into your WordPress privacy settings.

== Changelog ==

= 1.3 =

* Added checkbox to allow access to feeds when protection is enabled.
* Prepare for WordPress 3.5 Settings API changes.
* Added 'password_protected_before_login_form' and 'password_protected_after_login_form' actions.
* Added 'password_protected_process_login' filter to make it possible to extend login functionality.
* Now possible to use 'pre_update_option_password_protected_password' filter to use password before it is encrypted and saved.
* Ready for [translations](http://codex.wordpress.org/I18n_for_WordPress_Developers).

= 1.2.2 =

* Escape 'redirect_to' attribute. Props A. Alagha.
* Show login error messages.

= 1.2.1 =

* Only disable feeds when protection is active.
* Added a "How to log out?" FAQ.

= 1.2 =

* Use cookies instead of sessions.

= 1.1 =

* Encrypt passwords in database.

= 1.0 =

* First Release. If you spot any bugs or issues please [log them here](https://github.com/benhuson/password-protected/issues).

== Upgrade Notice ==

= 1.3 =
Allow access to feeds. Ready for translation.

= 1.2 =
Use cookies instead of sessions.

= 1.1 =
Passwords saved encrypted.