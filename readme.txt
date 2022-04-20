=== Password Protected ===
Contributors: wpexpertsio
Tags: password, protect, password protect, login
Requires at least: 4.6
Tested up to: 5.9.1
Requires PHP: 5.6
Stable tag: 2.5.1
License: GPLv2 or later

A very simple way to quickly password protect your WordPress site with a single password.

== Description ==

A very simple way to quickly password protect your WordPress site with a single password.

This plugin only protects your WordPress generated content. It **does not protect images or uploaded files** so if you enter and exact URL to in image file it will still be accessible.

Features include:

* Password protect your WordPress site with a single password.
* Option to allow access to feeds.
* Option to allow administrators access without entering password.
* Works with Mark Jaquith's [Login Logo](https://wordpress.org/plugins/login-logo/) plugin.
* Works with the [Uber Login Logo](https://wordpress.org/plugins/uber-login-logo/) plugin.

> Please note, this plugin works by setting a cookie to allow access to the site. If you are using a caching plugin or web hosting such as WP Engine that has in-built caching, you will need to configure the caching service to be disabled if the Password Protected cookie is set.

= Translations =

If you would like to translate this plugin you can easily contribute at the [Translating WordPress](https://translate.wordpress.org/projects/wp-plugins/password-protected/) page. The stable plugin needs to be 95% translated for a language file to be available to download/update via WordPress.

== Installation ==

To install and configure this plugin...

1. Upload or install the plugin through your WordPress admin.
2. Activate the plugin via the 'Plugins' admin menu.
3. Configure the password options in the Password Protected settings.

= Upgrading =

If you are upgrading manually via FTP rather that through the WordPress automatic upgrade link, please de-activate and re-activate the plugin to ensure the plugin upgrades correctly.

== Frequently Asked Questions ==

= How can I change the WordPress logo to a different image? =
Install and configure the [Login Logo](https://wordpress.org/plugins/login-logo/) plugin by Mark Jaquith or the [Uber Login Logo](https://wordpress.org/plugins/uber-login-logo/) plugin. This will change the logo on your password entry page AND also your admin login page.

= How can I enable feeds while the site is password protected? =
In the settings, check the 'Allow Feeds' checkbox.

= Can I prevent administrators having to enter password? =
In the settings, check the 'Allow Administrators' checkbox.

= I cannot preview my changes in the Theme Customizer =
You must be an administrator (have the manage_options capability) and in the Password Protected settings, check the 'Allow Administrators' checkbox.

= How can I log out? =
Just add a "password-protected=logout" query to your URL.
eg. http://www.example.com/?password-protected=logout

= I have forgotten the password. How can I disable the plugin? =
If you go to your WordPress admin login page `/wp-login.php` and it shows the admin login fields, you should still be able to login and disable the plugin.

If the admin login screen insteads shows the Password Protected field, you will need to access your site via SFTP/SSH and delete the Password Protected plugin folder in the plugins folder `wp-content/plugins/password-protected`.

= How can I redirect to a different domain name when logging out? =
If passing a redirect URL using 'redirect_to' when logging out you need you may need to use the [allowed domain names](https://codex.wordpress.org/Plugin_API/Filter_Reference/allowed_redirect_hosts) filter to allow redirecting to an external domain.

= Where can I report bugs and issues? =
Please log issues and bugs on the plugin's [GitHub page](https://github.com/benhuson/password-protected/issues).
You can also submit suggested enhancements if you like.

= How can I contribute? =
If you can, please [fork the code](https://github.com/benhuson/password-protected) and submit a pull request via GitHub. If you're not comfortable using Git, then please just submit it to the issues link above.

= How can I translate this plugin? =
If you would like to translate this plugin you can easily contribute at the [Translating WordPress](https://translate.wordpress.org/projects/wp-plugins/password-protected/) page. The stable plugin needs to be 90% translated for a language file to be available to download/update via WordPress.

== Screenshots ==

1. Login page perfectly mimicks the WordPress login.
2. Password Protected settings page.

== Changelog ==

= 2.5.1 =
- Fix - Author name conflict resolved

= 2.5 =
- Deprecate `wp_no_robots` and replace with `wp_robots_no_robots` for WordPress 5.7+

= 2.4 =
- Add a Nocache header to the login page redirect to prevent the browser from caching the redirect page. Props [De'Yonte W.](https://github.com/rxnlabs)
- Remove ‘password-protected’ query from redirects on successful login or logout.
- Check "redirect_to" query var is set in hidden form field. Props [Matthias Kittsteiner](https://wordpress.org/support/users/kittmedia/).
- Add favicon to password protected login page.

= 2.3 =
- Adds `password_protected_cookie_name` filter for the cookie name. Props [Jose Castaneda](https://github.com/jocastaneda).
- Let developers override the capability needed to see the options page via a `password_protected_options_page_capability` filter. Props [Nicola Peluchetti](https://github.com/nicoladj77).
- Don't use a "testcookie" POST query as it is blocked by Namecheap (and possibly other hosts).
- Fix warnings in W3 validator - script and style “type” attribute not required. Props [@dianamurcia](https://github.com/dianamurcia).
- Translations now via [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/password-protected/).
- Updated URL references. Props [Garrett Hyder](https://github.com/garretthyder).

= 2.2.5 =
- Added `password_protected_login_password_title` filter to allow customizing the "Password" label on the login form. Props [Jeremy Herve](https://github.com/jeherve).
- Fix stray "and" in readme. Props [Viktor Szépe](https://github.com/szepeviktor).
- Update Portuguese translation. Props [Jonathan Hult](https://github.com/jhult).
- Update Russian translation. Props [Alexey Chumakov](https://github.com/achumakov).

= 2.2.4 = 
- Check that `$_SERVER['REMOTE_ADDR']` is set.

= 2.2.3 =
- Restrict REST-API-access only if password protection is active.
- Added viewport meta tag to login page.
- Added `password_protected_show_login` filter.
- Cookie name is not editable in the admin so display just for reference.
- Use default WordPress text domain for “Remember Me” and “Log In” buttons.

= 2.2.2 =
- Change locked admin bar icon to green.
- Fix REST option and always allow access to REST API for logged in users.

= 2.2.1 =
* Fixed PHP error when calculating cookie expiration date.

= 2.2 =
* Added admin bar icon to indicate wether password protection is enabled/disabled.
* Option to show "Remember me" checkbox. Props [Christian Güdel](https://github.com/cguedel).
* REST API access disabled if password not entered.
* Admin option to allow REST API access.
* More robust checking of password hashes.

= 2.1 =
* Update caching notes for WP Engine and W3 Total Cache plugin.
* Tested up to WordPress 4.8

= 2.0.3 =
* Declare methods as public or private and use PHP5 constructors.
* Show user's IP address beside "Allow IP Addresses" admin setting.
* Add CHANGELOG.md and README.md

= 2.0.2 =
* Check allowed IP addresses are valid when saving.
* Only redirect to [allowed domain names](https://codex.wordpress.org/Plugin_API/Filter_Reference/allowed_redirect_hosts) when logging out.

= 2.0.1 =
* Split logout functionality into separate function.
* Security fix: Use a more complex password hash for cookie key. Props Marcin Bury, [Securitum](http://securitum.pl).

= 2.0 =
* Added [password_protected_logout_link](https://github.com/benhuson/password-protected/wiki/password_protected_logout_link-Shortcode) shortcode.
* Load 'password-protected-login.css' in theme folder if it exists.
* Added [password_protected_stylesheet_file](https://github.com/benhuson/password-protected/wiki/password_protected_stylesheet_file) filter to specify alternate stylesheet location.
* Added is_user_logged_in(), login_url(), logout_url() and logout_link() methods.
* Added Basque, Czech, Greek, Lithuanian and Norwegian translations.
* Better handling of login/out redirects when protection is not active on home page.

= 1.9 =
* Fixed "Allow Users" functionality with is_user_logged_in(). Props PatRaven.
* Added option for allowed IP addresses which can bypass the password protection.
* Added 'password_protected_is_active' filter.

= 1.8 =
* Support for adding "password-protected-login.php" in theme directory.
* Allow filtering of the 'redirect to' URL via the 'password_protected_login_redirect_url' filter.
* Added 'password_protected_login_messages' action to output errors and messages in template.
* Updated translations.
* Use current_time( 'timestamp' ) instead of time() to take into account site timezone.
* Check login earlier in the template_redirect action.

= 1.7.2 =
* Fix always allow access to robots.txt.
* Added 'password_protected_login_redirect' filter.
* Updated translations.

= 1.7.1 =
* Fix login template compatibility for WordPress 3.9

= 1.7 =
* Remove JavaScript that disables admin RSS checkbox.
* Added 'password_protected_theme_file' filter to allow custom login templates.
* Add option to allow logged in users.

= 1.6.2 =
* Set login page not to index if privacy setting is on.
* Allow redirection to a different URL when logging out using 'redirect_to' query and full URL.

= 1.6.1 =
* Language updates by wp-translations.org (Arabic, Dutch, French, Persian, Russian).

= 1.6 =
* Robots.txt is now always accessible.
* Added support for Uber Login Logo plugin.

= 1.5 =
* Added note about WP Engine compatibility to readme.txt
* Requires WordPress 3.1+
* Settings now have their own page.
* Fixed an open redirect vulnerability. Props Chris Campbell.

= 1.4 =
* Add option to allow administrators to use the site without logging in.
* Use DONOTCACHEPAGE to try to prevent some caching issues.
* Added a contextual help tab for WordPress 3.3+.
* Updated login screen styling for WordPress 3.5 compatibility.
* Options are now on the 'Reading' settings page in WordPress 3.5

= 1.3 =
* Added checkbox to allow access to feeds when protection is enabled.
* Prepare for WordPress 3.5 Settings API changes.
* Added 'password_protected_before_login_form' and 'password_protected_after_login_form' actions.
* Added 'password_protected_process_login' filter to make it possible to extend login functionality.
* Now possible to use 'pre_update_option_password_protected_password' filter to use password before it is encrypted and saved.
* Ready for [translations](http://codex.wordpress.org/I18n_for_WordPress_Developers).

= 1.2.2 =
* Show login error messages.
* Escape 'redirect_to' attribute. Props A. Alagha.

= 1.2.1 =
* Added a "How to log out?" FAQ.
* Only disable feeds when protection is active.

= 1.2 =
* Use cookies instead of sessions.

= 1.1 =
* Encrypt passwords in database.

= 1.0 =
* First Release. If you spot any bugs or issues please [log them here](https://github.com/benhuson/password-protected/issues).

== Upgrade Notice ==

= 2.5.1 =
Author name conflict resolved

= 2.5 =
Fixes robots tag for WordPress 5.7+

= 2.4 =
Fixes to help with caching issues and favicon on login page.

= 2.3 =
Fixed an issue with "testcookie" on some hosts. Added `password_protected_cookie_name` and `password_protected_options_page_capability` filters.

= 2.2.5 =
Added `password_protected_login_password_title` filter to allow customizing the "Password" label on the login form.

= 2.2.4 =
Check that `$_SERVER['REMOTE_ADDR']` is set.

= 2.2.3 =
Restrict REST-API-access only if password protection is active. Added viewport meta tag to login page.

= 2.2.2 =
Fix REST option and always allow access to REST API for logged in users. Change locked admin bar icon to green.

= 2.2.1 =
Fixed PHP error when calculating cookie expiration date.

= 2.2 =
Added admin bar icon to indicate wether password protection is enabled/disabled and disable REST API access (admin option to allow).

= 2.1 =
Update caching notes for WP Engine and W3 Total Cache plugin.

= 2.0.3 =
Show user's IP address beside "Allow IP Addresses" admin setting. Declare methods as public or private and use PHP5 constructors.

= 2.0.2 =
Only redirect to [allowed domain names](https://codex.wordpress.org/Plugin_API/Filter_Reference/allowed_redirect_hosts) when logging out.

= 2.0.1 =
Security fix: Use a more complex password hash for cookie key.

= 2.0 =
Added 'password_protected_logout_link' shortcode and use 'password-protected-login.css' in theme folder if it exists.

= 1.9 =
Fixed "Allow Users" functionality and added option to allowed IP addresses which can bypass the password protection.

= 1.8 =
Support for adding "password-protected-login.php" in theme directory and allow filtering of the 'redirect to' URL via the 'password_protected_login_redirect_url' filter.

= 1.7.2 =
Added 'password_protected_login_redirect' filter.

= 1.7.1 =
Fix login template compatibility for WordPress 3.9

= 1.7 =
Added 'password_protected_theme_file' filter and option to allow logged in users.

= 1.6.2 =
Allow redirection to a different URL when logging out.

= 1.6 =
Added support for Uber Login Logo plugin.

= 1.5 =
Fixes an open redirect vulnerability. Settings now have own page.

= 1.4 =
Administrators can use the site without logging in. WordPress 3.5 compatible.

= 1.3 =
Allow access to feeds. Ready for translation.

= 1.2 =
Use cookies instead of sessions.

= 1.1 =
Passwords saved encrypted.
