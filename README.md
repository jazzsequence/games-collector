# Games Collector #
[![Build Status](https://travis-ci.org/jazzsequence/games-collector.svg?branch=develop)](https://travis-ci.org/jazzsequence/games-collector) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jazzsequence/games-collector/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/jazzsequence/games-collector/?branch=develop) [![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](http://www.gnu.org/licenses/gpl-3.0)


**Contributors:**      [jazzsequence](https://github.com/jazzsequence)  
**Donate link:**       https://www.paypal.me/jazzsequence/  
**Tags:**  
**Requires at least:** 4.4  
**Tested up to:**      5.2.4  
**Stable tag:**        1.3.6  
**License:**           GPLv3  
**License URI:**       http://www.gnu.org/licenses/gpl-3.0.html  
**Demo:**              https://jazzsequence.com/games/

## Description ##

Catalog all your tabletop (or other) games in your WordPress site and display a list of games in your collection.

You can checkout a live [demo](https://jazzsequence.com/games/) of the plugin (with some custom CSS added) [on my blog](https://jazzsequence.com/games/).

## Installation ##

### Manual Installation ###

1. [Download the latest version](https://github.com/jazzsequence/games-collector/releases/tag/1.0.0) from the [Releases](https://github.com/jazzsequence/games-collector/releases/latest) page.
2. Unzip and upload the entire `/games-collector` directory to the `/wp-content/plugins/` directory.
3. Activate Games Collector through the 'Plugins' menu in WordPress.
4. Create a new page, name it whatever you like, and add the shortcode to the page content to display your games list: `[games-collector]`.

## Frequently Asked Questions ##


## Screenshots ##
![Games Collector new game screen](https://i1.wp.com/jazzsequence.com/wp-content/uploads/2017/02/Screenshot-2017-02-03-14.48.26.png)

![Games Collector admin](https://jazzsequence.com/wp-content/uploads/2017/02/Screenshot-2017-02-03-15.06.17.png)

![Games Collector add from BGG](https://i.imgur.com/4bRt36J.gif)

![Games Collector Gutenberg block](https://user-images.githubusercontent.com/991511/45578321-f6ec5780-b83d-11e8-949e-cb56907af0a5.png)

## Changelog ##

### 1.3.6 ###
* Added handling for when CMB2 (or Extended CPTs) is not loaded correctly. [[#73](https://github.com/jazzsequence/games-collector/pull/73)]

### 1.3.5 ###
* Added actual `composer.json` file so the plugin could be installed via Packagist. [[#71](https://github.com/jazzsequence/games-collector/pull/71)]

### 1.3.4 ###
* use the jQuery implementation of Isotope

### 1.3.3 ###
* require isotope as a dependency for our isotope loader

### 1.3.2 ###
* Update version for new Composer build

### 1.3.1 ###
* Uses composer for dependencies and updates paths

### 1.3.0 ###
* Added first Gutenberg block! Now you can add your games list in Gutenberg rather than using a shortcode. More Gutenberg blocks to come.

### 1.2.0 ###
* Added integration with Board Game Geek API. Games can now be added by searching BGG for matching titles and information imported and automatically added to new games.
* Fixed an issue where games with an indeterminate max number of players was displaying a 0 value (e.g. `2 - 0 players`) and combined that with games with an unrealistically large number of players (e.g. `2 - 99 players`) to display `{{min_players}}+ players` e.g. `2+ players`.
* Refactored the `gc_number_players` filter to only filter the actual number of players and added a `gc_number_players_output` filter which can filter the entire output (what `gc_number_players` previously did).
* Fixed an issue where only the highest difficulty was displaying in the dropdown.
* Fixed a display issue where games that can only be played with a specific number of players were still displaying the min/max player numbers. Changed to just display the number of players, e.g. `2 players` instead of `2 - 2 players`.
* Dropped support for `hhvm` and php 5.x. Require minimum of PHP 7.0.
* Scrutinizer CI integration for code coverage and quality checking.
* Cleaned up some code as a result of Scrutinizer sniffs.

### 1.1.0 ###
* Add activation hook that will create a Games page with the shortcode on plugin activation.
* Fixed some display errors and undefined notices on titles.
* Added more filters for display output. Now buttons can be filtered individually and therefore disabled.
* Switched to SVG images for game info icons which required some CSS changes.
* Cleaned up spacing for game attributes.
* Added [Shortcode UI](https://wordpress.org/plugins/shortcode-ui/) integration.
* Added shortcode that allows you to display a single or multiple specific games using `[games-collector-list gc_game="1,2,3"]`.

### 1.0.0 ###
* abstracted display elements into smaller functions and made them filterable
* added capability of SVGs to be output in base64-encoded or raw svg XML markup

### 0.2 ###
* Integrated Travis CI
* Added unit tests
* Adjusted filters for sorting
* Changed attribute link in game list
* Changed ordering of games (alphabetically _ascending_)
* Changed the post type to remove links to single game entry in admin
* Added front end styles and SVG icons
* Added shortcode
* Added WordPress filters

### 0.1 ###
* First release

## Upgrade Notice ##

### 0.1 ###
First Release
