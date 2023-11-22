=== Password Protected - Ultimate Plugin to Password Protect Your WordPress Content with Ease ===
Contributors: wpexpertsio
Tags: password, maintenance mode, coming soon page, password protect, login
Requires at least: 4.6
Tested up to: 6.4.1
Requires PHP: 5.6
Stable tag: 2.6.5.1
License: GPLv2 or later

The ultimate password protection plugin that protects your WordPress site with a single password.

== Description ==

[Live Demo](https://tastewp.com/new/?pre-installed-plugin-slug=password-protected)

Password Protected for WordPress allows you to secure your website with a single password. The ultimate password protection plugin protects your WordPress categories, posts, products, and more with the simplest of ease. 

Password Protected does not protect images or uploaded files, so if you enter an exact URL of an image file, it will still be accessible.

= Password Protected Features =

* Easy to set up - Password protect your WordPress site with a single password.
* Set a description - Display text (description or instructions) above or below the password field.
* reCaptcha v2/v3 - You can enable Google reCAPTCHA v2 or V3 to increase bot security measures.  
* Allow administrators  - Option to allow administrators access without entering the password.
* Allow logged-in users - Option to give logged-in users access to the website.
* Allow RSS Feeds - Option to allow access to feeds.
* Allow Rest API Access - Allow admin to access pages and posts.
* Customization – Customize the password-protected screen, including the background, font, logo, and colors (using [Login Designer](https://wp.org/plugins/login-designer))

> Please note, this plugin works by setting a cookie to allow access to the site. If you are using a caching plugin or web hosting such as WP Engine that has in-built caching, you will need to configure the caching service to be disabled if the Password Protected cookie is set.

= Password Protected Pro =

[Password Protected Pro](https://passwordwp.com/pricing/?utm_source=wp_org&utm_medium=readme) is a [Kinsta recommended plugin](https://kinsta.com/blog/password-protect-wordpress-site/) that offers powerful features that will take your WordPress website password protected to the next level.

* Exclude page posts & post types - Option to exclude specific pages and post types from password protection.
* Limit login attempts - Limit the user’s attempts to enter a password for a specified interval.
* Lockdown time - Set a time (in minutes) during which users can not enter the password after their login attempts are limited.
* Usage limits - Set a usage limit after which a password can not be used.
* Status control - You can change the status of the password (Active, Deactivated, Expired).
* Manage multiple passwords - Edit, activate, deactivate, or delete passwords (individual or bulk action).
* Set Expiry Dates - Options to select the expiry date for specific passwords
* Activity Log Reports - View the Activity Logs of each user, including their IP address, browser, status, date, and time of password attempts.
* Priority Support - Our team of support professionals will make sure to handle your queries on high priority.

= Detect hackers and bots from abusing password protection with reCAPTCHA =
Google reCAPTCHA v2 or v3 empowers your WordPress website to prevent password abuse against automated software, bots, hackers, etc. This anti-spam tool will allow any real user to access your website easily.

= Password protect any post type =
You can include or exclude any post type from password protection. All of this can be done from the back end using a single password. 

= Password protect your WordPress site with a single password =
Password Protected has the ability to secure your entire website with a single password. Everything from pages to posts will also be protected.

= Display password protected content in RSS feeds =
You can allow RSS feeds to show a login page after which user accessing the feed can view the password protected content. Disabling the option will restrict any user's access to the website even if the RSS feed is public.  

= Password usage limits and complexities =
Limits users from entering the password using Password Protected’s Usage Limit counter. Password greater than that limit can not be applicable on the login page.

Usage limits can also be restricted by setting a password expiry from the calendar settings. This prevents users from re-using any given password. Regular password changes mitigate the risk of any security breach.

Limiting password attempts prevents security issues like the Brute Force attack, where hackers keep trying to guess your password until they get it right. A complimentary solution to this feature is the Lockdown Timer, which resets the user’s right to log in after exceeding their login attempt limit. 

= Monitor and review activity logs for Password Protected =
Password Protected’s Activity Log is similar to an audit log that gives you a record of the events that have taken place on your website. To provide you with a better understanding, here is a list of the details you will find in the activity log:

* Filter options for passwords used in a specific range (All-time, Today, Yesterday, This Week, This Month)
* IP addresses of the system from where the passwords were attempted.
* Country names from where the passwords were attempted.
* Browser names where the password was attempted. 
* Status of the password attempts (successful or failed)

The admin can also perform the search operation on the activity log. You can search by IP, Country, Browser, and Status. For e.g., searching Success will search all the passwords with successful attempts, and searching Failure will search all the orders with failed attempts.

= Documentation and support =
* Password Protected [Technical Documentation](https://passwordwp.com/documentation/?utm_source=wp_org&utm_medium=readme)
* You can open a support ticket [here](https://objectsws.atlassian.net/servicedesk/customer/portal/18)

= Translations =
If you would like to translate this plugin, you can easily contribute to the [Translating WordPress](https://translate.wordpress.org/projects/wp-plugins/password-protected/) page. The stable plugin needs to be 95% translated for a language file to be available to download/update via WordPress.

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
2. Login page with reCaptcha v3
3. Login page with reCaptcha v2
4. Password Protected general settings page.
4. Password Protected advanced settings page.

== Changelog ==
= 2.6.5.1 =
- Ensured seamless compatibility with the latest WordPress version

= 2.6.5 =
- Fixed - [Login Designer](https://wp.org/plugins/login-designer) compatibility issues.

= 2.6.4 =
- Fixed - Added compatibility for PHP version 8.2

= 2.6.3.2 =
- Update - Feedback library updated

= 2.6.3.1 =
- Fix - Parse error related to PHP version 7.2

= 2.6.3 =
- New - Added Freemius SDK integration.
- New - Added functionality to login with transient if the cookies are blocked.
- Fix – Fixed Redirect Issue from excluded page to password protected page.

= 2.6.2 =
- Fix – Parse error related to PHP version 7.2

= 2.6.1 =
- Fix – Parse error related to PHP version 7.2
- Update - Link to official Google Re-captcha documentation

= 2.6.0 =
- Improved admin settings interface and introduced NEW tabs structure.
- NEW: Added Google Recaptcha v2 and v3 to make it more secure.
- NEW: Added Password Protected top-level admin menu for ease.
- NEW: Added option to add text above password Field.
- NEW: Added option to add text below password Field.

= 2.5.3 =
- Improved Settings HTML structure
- Added Note regarding compatibility with login designer within dashboard

= 2.5.2 =
- Made compatibility with [Login Designer](https://wp.org/plugins/login-designer); Now you can customize the password-protected screen with the customizer using login designer plugin.

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
