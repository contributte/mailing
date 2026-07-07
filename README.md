![](https://heatbadger.now.sh/github/readme/contributte/mailing/)

<p align=center>
  <a href="https://github.com/contributte/mailing/actions"><img src="https://badgen.net/github/checks/contributte/mailing/master?cache=300"></a>
  <a href="https://coveralls.io/r/contributte/mailing"><img src="https://badgen.net/coveralls/c/github/contributte/mailing?cache=300"></a>
  <a href="https://packagist.org/packages/contributte/mailing"><img src="https://badgen.net/packagist/dm/contributte/mailing"></a>
  <a href="https://packagist.org/packages/contributte/mailing"><img src="https://badgen.net/packagist/v/contributte/mailing"></a>
</p>
<p align=center>
  <a href="https://packagist.org/packages/contributte/mailing"><img src="https://badgen.net/packagist/php/contributte/mailing"></a>
  <a href="https://github.com/contributte/mailing"><img src="https://badgen.net/github/license/contributte/mailing"></a>
  <a href="https://bit.ly/ctteg"><img src="https://badgen.net/badge/support/gitter/cyan"></a>
  <a href="https://bit.ly/cttfo"><img src="https://badgen.net/badge/support/forum/yellow"></a>
  <a href="https://contributte.org/partners.html"><img src="https://badgen.net/badge/sponsor/donations/F96854"></a>
</p>

<p align=center>
Website 🚀 <a href="https://contributte.org">contributte.org</a> | Contact 👨🏻‍💻 <a href="https://f3l1x.io">f3l1x.io</a> | Twitter 🐦 <a href="https://twitter.com/contributte">@contributte</a>
</p>

Send emails easily in Nette applications with `MailBuilder`, `MailBuilderFactory`, `MailSender` and `MailTemplateFactory`.

## Versions

| State       | Version | Branch   | Nette | PHP     |
|-------------|---------|----------|-------|---------|
| dev         | `^0.8`  | `master` | 3.1+  | `>=8.2` |
| stable      | `^0.7`  | `master` | 3.1+  | `>=8.1` |

## Installation

To install latest version of `contributte/mailing` use [Composer](https://getcomposer.org).

```bash
composer require contributte/mailing
```

Register the extension in your NEON configuration.

```neon
extensions:
	mailing: Contributte\Mailing\DI\MailingExtension
```

## Configuration

Default configuration looks like this:

```neon
mailing:
	template:
		defaults:
			layout: @@default
		config:
			layout: @@default
```

Templating and template options are under key **template**. At this moment, there's a default theme (https://github.com/leemunroe/responsive-html-email-template/), simple but good looking.
This default layout is located in the package, you don't need to change anything. Unless you want your own layout.

- The `defaults` should be untouched and it can be considered as base class. Your theme will be extending the default one.
- The `config` can be considered as child class, define your own theme.

Typical configuration would override the default theme with some extra features.

```neon
template:
	defaults:
		layout: @@default
	config:
		layout: @@mylayout
```

> There are double `@` because of NEON resolving.

## Usage

The example below shows the typical `MailBuilder` workflow.

### Builder

```php
/** @var Contributte\Mailing\IMailBuilderFactory @inject */
public $mailBuilderFactory;
```

Thanks to the `MailBuilderFactory`, we create the `MailBuilder` to set up and send the email.

```php
// Builder
$mail = $this->mailBuilderFactory->create();
$mail->setSubject('It is awesome');
$mail->addTo($user->email);
$mail->addBcc($user->email);
$mail->addCcs($user->email);

// Template
$mail->setTemplateFile(__DIR__ . '/../../resources/awesome.latte');
$mail->setParameters([
	'username' => $user->logname,
]);

// Sending
$mail->send();
```

At first glance, it looks like the `MailBuilder` breaks the SRP, but that's not the case. The `MailBuilderFactory` creates the `MailBuilder`
and provides the `IMailSender` and `IMailTemplateFactory`. The `MailBuilder` is just a tiny wrapper/builder with an enjoyable API.

### Template

Each template has many internal variables:

- `$_defaults` - refer default configuration
- `$_config` - refer custom configuration
- `$_mail` - refer mail configuration (can overrides subject, from, bcc, etc..)

```latte
{layout $_config->layout}

{block #header}
    Awesome emails.
{/block}

{block #content}
    Hello!
{/block}
```

Each template has many blocks, take a look at the source.

## Development

See [how to contribute](https://contributte.org) to this package. This package is currently maintained by these authors.

<a href="https://github.com/f3l1x">
    <img width="80" height="80" src="https://avatars2.githubusercontent.com/u/538058?v=3&s=80">
</a>

-----

Consider to [support](https://contributte.org/partners) **contributte** development team.
Also thank you for using this package.
