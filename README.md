# MailboxLayerClient

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

This library is a PHP client for [MailboxLayer](https://mailboxlayer.com), it's useful to validate if a email address provided by a user is valid, exists or to have some useful informations about an email address.

## Structure

```
src/
tests/
vendor/
```

## Install

**This package requires PHP >= 7.1.** 

Via Composer

``` bash
$ composer require xylis/mailboxlayer-client
```

## Basic Usage

```php
$client = new Xylis\MailboxLayer\Client('YOUR_API_KEY');

// Check if the email is valid
$client->validate('email@ddress.com')->isValid();
```

## Documentation

### Client constructor

```php
$client = new Xylis\MailboxLayer\Client(string $apiKey, bool $smtpCheck = true, bool $prettyFormat = false, bool $catchAllCheck = false)
```

- If you put $smtpCheck to *false* (*true* by default), the api won't check if email address exist
- If you put $prettyFormat to *true* (*false* by default), the api will return prettified JSON result (use only for debugging)
- If you put $catchAllCheck to *true* (*false* by default), the api will check if the addresses you check are catch-all mailboxes.

Please do read the [MailboxLayer Documentation](https://mailboxlayer.com/documentation) to understand how their API works.

___

```validate(string $email)``` returns an email object so you can do this way :
```php
$email = $client->validate('email@address.com');

// Check if the email is valid
$email->isFormatValid();

// Check if the email format is valid
$email->isFormatValid();

// Check if the email format is valid
$email->isMxFound();

// Check if email address exists, return false if not
$email->isSmtpValid();

// Get the email address (return email@ddress.com)
$email->getMail();

// Get the user from email address (return email)
$email->getUser();

// Get the domain from email address (return ddress.com)
$email->getDomain();

// Get suggestion if the email address is misspelled 
$email->getSuggestion();

// Get quality score between 0 (bad) and 1 (good)
$email->getQualityScore();

// Check if email address is free delivered (domains like gmail.com and yahoo.com)
$email->isFreeDeliveredEmailAddress();

// Check if email address is disposable (trash & temporary mailbox)
$email->isDisposable();

// Check if email address is a role mail address (like support)
$email->isRoleEmailAddress();
```

How the ```validate``` function works : 
```php
$client->validate(string $emailAddress, bool $bypassFilterValidation = false);
```

If `$bypassFilterValidation` is set to *true* (*false* by default), the client won't verify if `$emailAddress` himself is an email, so the client will make the API request to the API anyway.
`$bypassFilterValidation` is set to *false* gain in performance and avoid useless results

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email [Julien RAVIA](mailto:julien.ravia@gmail.com) instead of using the issue tracker.

## Credits

- [Julien RAVIA][link-author]
- [All Contributors][link-contributors]

## License Information

* GNU GPL v3
* You can find a copy of this software here: https://github.com/JulienRAVIA/MailboxLayerClient

[ico-version]: https://img.shields.io/packagist/v/xylis/mailboxlayer-client.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-GPL-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/JulienRAVIA/MailboxLayerClient/master.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/xylis/mailboxlayer-client.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/xylis/mailboxlayer-client
[link-travis]: https://travis-ci.org/JulienRAVIA/MailboxLayerClient
[link-downloads]: https://packagist.org/packages/xylis/mailboxlayer-client
[link-author]: https://github.com/JulienRAVIA
[link-contributors]: ../../contributors
