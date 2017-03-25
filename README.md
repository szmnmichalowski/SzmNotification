## SzmNotification

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

SzmNotification is a [Zend Framework 2/3](http://framework.zend.com/) controller plugin which stores notification in session container (same as FlashMessanger). 
It is designed to work with one of the following notifiction helpers:
- [SzmNoty](https://github.com/szmnmichalowski/SzmNoty) - jQuery plugin [http://ned.im/noty/](http://ned.im/noty/)

## Installation

You can install this module via composer
**1.** Add this project into your composer.json
```
"require": {
    "szmnmichalowski/szm-notification": "dev-master"
}
```
**2.** Update your dependencies
```
$ php composer.phar update
```

**3.** Add module to your **application.config.php**. It requires `Zend\Session`
```
return array(
    'modules' => array(
        'Zend\Session',
        'SzmNotification' // <- Add this line
    )
);
```

## Usage

This plugin has defined 4 types of notifications by default:
- info
- success
- warning
- error

But it is possible to add notification under custom type

#### How to use it

Examples of use:
```
$this->notification()->add('info', 'Lorem ipsum');
$this->notification()->has('info');
$this->notification()->get('info');

$this->notification()->addInfo('Lorem ipsum');
$this->notification()->hasInfo();
$this->notification()->getInfo();
```

#### Available methods:

Global methods:
- `add($type, $text)` - Add notification 
- `has($type)` - Check if namespace contains any notification added in previous request
- `hasCurrent($type)` - Check if namespace contains any notification added during this request
- `get($type)` - Return notifications from previous request
- `getCurrent($type)` - Return notifications from current request
- `getAll()` - Return all notifications from previous request
- `getAllCurrent()` - Return all notifications from current request

Following methods are available for each type (including custom type). Just replace `*` with notification type:
- `add*($text)` - Add notification
- `has*()` - Check if namespace contains any notification added in previous request
- `hasCurrent*()` - Check if namespace contains any notification added during this request
- `get*()` - Return notifications from previous request
- `getCurrent*()` - Return notifications from current request

Examples:
```
$this-notification()->addCustomType('This is custom type notification');
$this-notification()->addFoo('This is custom type notification');
$this-notification()->addBar('This is custom type notification');

$this-notification()->getCustomType();
$this-notification()->getFoo();
$this-notification()->getCurrentBar();
```