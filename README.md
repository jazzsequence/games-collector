# Games Collector #
[![Build Status](https://travis-ci.org/jazzsequence/games-collector.svg?branch=develop)](https://travis-ci.org/jazzsequence/games-collector) [![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](http://www.gnu.org/licenses/gpl-3.0)


**Contributors:**      [jazzsequence](https://github.com/jazzsequence)  
**Donate link:**       https://www.paypal.me/jazzsequence/  
**Tags:**  
**Requires at least:** 4.4  
**Tested up to:**      4.7  
**Stable tag:**        1.0.0  
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


## Changelog ##

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
