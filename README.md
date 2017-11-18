# Monk [![Build Status](https://travis-ci.org/brenoalvs/monk.svg?branch=master)](https://travis-ci.org/brenoalvs/monk)
<a href="https://wordpress.org/plugins/monk/">
<img src=".wordpress-org/banner-1544x500.png" width="1544"/>
</a>

Welcome!
This is the official repository on GitHub of Monk, a WordPress translation plugin for your content reach the world. Here you can follow the project development and see the new things that are being made. Feel free to look the sources, issues and most importantly, contribute.

Download Monk [here](https://wordpress.org/plugins/monk/)

## Asking for support ##

Please use this repository only for core related issues, like bugs, PRs and contributions. If you need user related help, please visit our [support page on WordPress.org](https://wordpress.org/support/plugin/monk).

## Documentation ##

+ [Getting started with Monk](https://github.com/brenoalvs/monk/wiki/getting-started)
+ [How our translations work](https://github.com/brenoalvs/monk/wiki/how-translations-work)
+ [Languages in the URLs](https://github.com/brenoalvs/monk/wiki/url-translations)

## Contributing to Monk ##

If you find any error or have suggestions, contribute with them to improve Monk. They are all welcome, but before anything please take a minute to read our [contributor guidelines](https://github.com/brenoalvs/monk/blob/master/.github/CONTRIBUITING.md) and [code of conduct](https://github.com/brenoalvs/monk/blob/master/.github/code_of_conduct.md).

## Tests ##

Tests are in `tests` directory. To run them you need to install some requirements and configure the test suite.

1. Install PHPUnit (https://github.com/sebastianbergmann/phpunit#installation).
2. Install WP-CLI (http://wp-cli.org/#install).
3. `cd` to plugin's directory.
4. Install and configure the test environment running `bin/install-wp-tests.sh <db-name> <db-user> <db-pass> [db-host] [wp-version]`. E.g.: `bin/install-wp-tests.sh wordpress_test root '' localhost latest`
5. Run `phpunit` and let the tests begin!

*Note:* Be sure to have `svn` command installed on your system. 
*Note:* If anything fails running install-wp-tests.sh you'll have to delete /tmp/wordpress* and drop the wordpress_test database to undo what the script does.
