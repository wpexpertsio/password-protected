# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [2.5] - 2021-09-01

### Fixed
- Deprecate `wp_no_robots` and replace with `wp_robots_no_robots` for WordPress 5.7+

## [2.4] - 2020-09-24

### Fixed
- Add a Nocache header to the login page redirect to prevent the browser from caching the redirect page. Props [De'Yonte W.](https://github.com/rxnlabs)
- Remove ‘password-protected’ query from redirects on successful login or logout.
- Check "redirect_to" query var is set in hidden form field. Props [Matthias Kittsteiner](https://wordpress.org/support/users/kittmedia/).
- Add favicon to password protected login page.

## [2.3] - 2020-05-17

### Added
- Adds `password_protected_cookie_name` filter for the cookie name. Props [Jose Castaneda](https://github.com/jocastaneda).
- Let developers override the capability needed to see the options page via a `password_protected_options_page_capability` filter. Props [Nicola Peluchetti](https://github.com/nicoladj77).

### Fixed
- Don't use a "testcookie" POST query as it is blocked by Namecheap (and possibly other hosts).
- Fix warnings in W3 validator - script and style “type” attribute not required. Props [@dianamurcia](https://github.com/dianamurcia).

### Changed
- Translations now via [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/password-protected/).
- Updated URL references. Props [Garrett Hyder](https://github.com/garretthyder).

## [2.2.5] - 2019-06-04

### Added
- Added `password_protected_login_password_title` filter to allow customizing the "Password" label on the login form. Props [Jeremy Herve](https://github.com/jeherve).

### Changed
- Fix stray "and" in readme. Props [Viktor Szépe](https://github.com/szepeviktor).
- Update Portuguese translation. Props [Jonathan Hult](https://github.com/jhult).
- Update Russian translation. Props [Alexey Chumakov](https://github.com/achumakov).

## [2.2.4] - 2019-02-22

### Fixed
Check that `$_SERVER['REMOTE_ADDR']` is set.

## [2.2.3] - 2019-01-10

### Fixed
- Restrict REST-API-access only if password protection is active.

### Added
- Added viewport meta tag to login page.
- Added `password_protected_show_login` filter.

### Changed
- Cookie name is not editable in the admin so display just for reference.
- Use default WordPress text domain for “Remember Me” and “Log In” buttons.

## [2.2.2] - 2018-05-29

### Changed
- Change locked admin bar icon to green.

### Fixed
- Fix REST option and always allow access to REST API for logged in users.

## [2.2.1] - 2018-05-27

### Fixed
- Fixed PHP error when calculating cookie expiration date.

## [2.2] - 2018-05-25

### Added
- Admin bar icon to indicate wether password protection is enabled/disabled.
- Option to show "Remember me" checkbox. Props [Christian Güdel](https://github.com/cguedel).
- REST API access disabled if password not entered.
- Admin option to allow REST API access.

### Security
- More robust checking of password hashes.

## [2.1] - 2017-07-27

### Added
- Update caching notes for WP Engine and W3 Total Cache plugin.
- Tested up to WordPress 4.8

## [2.0.3] - 2015-03-23

### Added
- Show user's IP address beside "Allow IP Addresses" admin setting.
- Add CHANGELOG.md and README.md

### Changed
- Declare methods as public or private and use PHP5 constructors.

## [2.0.2] - 2015-10-29

### Added
- Check allowed IP addresses are valid when saving.

### Changed
- Only redirect to [allowed domain names](https://codex.wordpress.org/Plugin_API/Filter_Reference/allowed_redirect_hosts) when logging out.

## [2.0.1] - 2015-07-24

### Changed
- Split logout functionality into separate function.

### Security
- Use a more complex password hash for cookie key. Props Marcin Bury, [Securitum](http://securitum.pl).

## [2.0] - 2015-03-26

### Added
- Added [password_protected_logout_link](https://github.com/benhuson/password-protected/wiki/password_protected_logout_link-Shortcode) shortcode.
- Load 'password-protected-login.css' in theme folder if it exists.
- Added [password_protected_stylesheet_file](https://github.com/benhuson/password-protected/wiki/password_protected_stylesheet_file) filter to specify alternate stylesheet location.
- Added is_user_logged_in(), login_url(), logout_url() and logout_link() methods.
- Added Basque, Czech, Greek, Lithuanian and Norwegian translations.

### Changed
- Better handling of login/out redirects when protection is not active on home page.

## [1.9] - 2014-12-17

### Fixed
- Fixed "Allow Users" functionality with is_user_logged_in(). Props PatRaven.

### Added
- Added option for allowed IP addresses which can bypass the password protection.
- Added 'password_protected_is_active' filter.

## [1.8] - 2014-10-07

### Added
- Support for adding "password-protected-login.php" in theme directory.
- Allow filtering of the 'redirect to' URL via the 'password_protected_login_redirect_url' filter.
- Added 'password_protected_login_messages' action to output errors and messages in template.
- Updated translations.

### Changed
- Use current_time( 'timestamp' ) instead of time() to take into account site timezone.
- Check login earlier in the template_redirect action.

## [1.7.2] - 2014-06-05

### Fixed
- Fix always allow access to robots.txt.

### Added
- Added 'password_protected_login_redirect' filter.
- Updated translations.

## [1.7.1] - 2014-03-17

### Fixed
- Fix login template compatibility for WordPress 3.9

## [1.7] - 2014-02-27

### Fixed
- Remove JavaScript that disables admin RSS checkbox.

### Added
- Added 'password_protected_theme_file' filter to allow custom login templates.
- Add option to allow logged in users.

## [1.6.2] - 2014-01-10

### Changed
- Set login page not to index if privacy setting is on.
- Allow redirection to a different URL when logging out using 'redirect_to' query and full URL.

## [1.6.1] - 2013-11-13

### Added
- Language updates by wp-translations.org (Arabic, Dutch, French, Persian, Russian).

## [1.6] - 2013-07-04

### Fixed
- Robots.txt is now always accessible.

### Added
- Added support for Uber Login Logo plugin.

## [1.5] - 2013-02-21

### Added
- Added note about WP Engine compatibility to readme.txt

### Changed
- Requires WordPress 3.1+
- Settings now have their own page.

### Security
- Fixed an open redirect vulnerability. Props Chris Campbell.

## [1.4] - 2013-02-10

### Added
- Add option to allow administrators to use the site without logging in.
- Use DONOTCACHEPAGE to try to prevent some caching issues.
- Added a contextual help tab for WordPress 3.3+.

### Changed
- Updated login screen styling for WordPress 3.5 compatibility.
- Options are now on the 'Reading' settings page in WordPress 3.5

## [1.3] - 2012-10-01

### Added
- Added checkbox to allow access to feeds when protection is enabled.
- Prepare for WordPress 3.5 Settings API changes.
- Added 'password_protected_before_login_form' and 'password_protected_after_login_form' actions.
- Added 'password_protected_process_login' filter to make it possible to extend login functionality.
- Now possible to use 'pre_update_option_password_protected_password' filter to use password before it is encrypted and saved.
- Ready for [translations](http://codex.wordpress.org/I18n_for_WordPress_Developers).

## [1.2.2] - 2012-07-30

### Added
- Show login error messages.

### Security
- Escape 'redirect_to' attribute. Props A. Alagha.

## [1.2.1] - 2012-05-25

### Added
- Added a "How to log out?" FAQ.

### Changed
- Only disable feeds when protection is active.

## [1.2] - 2012-04-14

### Changed
- Use cookies instead of sessions.

## [1.1] - 2012-02-12

### Security
- Encrypt passwords in database.

## [1.0] - 2012-02-01

### Added
- First Release. If you spot any bugs or issues please [log them here](https://github.com/benhuson/password-protected/issues).

[Unreleased]: https://github.com/benhuson/password-protected/compare/2.5...HEAD
[2.4]: https://github.com/benhuson/password-protected/compare/2.4...2.5
[2.4]: https://github.com/benhuson/password-protected/compare/2.3...2.4
[2.3]: https://github.com/benhuson/password-protected/compare/2.2.5...2.3
[2.2.5]: https://github.com/benhuson/password-protected/compare/2.2.4...2.2.5
[2.2.4]: https://github.com/benhuson/password-protected/compare/2.2.3...2.2.4
[2.2.3]: https://github.com/benhuson/password-protected/compare/2.2.2...2.2.3
[2.2.2]: https://github.com/benhuson/password-protected/compare/2.2.1...2.2.2
[2.2.1]: https://github.com/benhuson/password-protected/compare/2.2...2.2.1
[2.2]: https://github.com/benhuson/password-protected/compare/2.1...2.2
[2.1]: https://github.com/benhuson/password-protected/compare/2.0.3...2.1
[2.0.3]: https://github.com/benhuson/password-protected/compare/2.0.2...2.0.3
[2.0.2]: https://github.com/benhuson/password-protected/compare/2.0.1...2.0.2
[2.0.1]: https://github.com/benhuson/password-protected/compare/2.0...2.0.1
[2.0]: https://github.com/benhuson/password-protected/compare/1.9...2.0
[1.9]: https://github.com/benhuson/password-protected/compare/1.8...1.9
[1.8]: https://github.com/benhuson/password-protected/compare/1.7.2...1.8
[1.7.2]: https://github.com/benhuson/password-protected/compare/1.7.1...1.7.2
[1.7.1]: https://github.com/benhuson/password-protected/compare/1.7...1.7.1
[1.7]: https://github.com/benhuson/password-protected/compare/1.6.2...1.7
[1.6.2]: https://github.com/benhuson/password-protected/compare/1.6.1...1.6.2
[1.6.1]: https://github.com/benhuson/password-protected/compare/1.6...1.6.1
[1.6]: https://github.com/benhuson/password-protected/compare/1.5...1.6
[1.5]: https://github.com/benhuson/password-protected/compare/1.4...1.5
[1.4]: https://github.com/benhuson/password-protected/compare/1.3...1.4
[1.3]: https://github.com/benhuson/password-protected/compare/1.2.2...1.3
[1.2.2]: https://github.com/benhuson/password-protected/compare/1.2.1...1.2.2
[1.2.1]: https://github.com/benhuson/password-protected/compare/1.2...1.2.1
[1.2]: https://github.com/benhuson/password-protected/compare/1.1...0.7.11.2
[1.1]: https://github.com/benhuson/password-protected/compare/1.0...1.1
[1.0]: https://github.com/benhuson/password-protected/tree/1.0
