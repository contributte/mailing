# Mailing

## Content

- [Installation - how to install](#installation)
- [Configuration - layouts, templates, options](#configuration)
- [Usage - building & sending](#usage)

## Info

The main goal of this package is send emails easily. It has 4 main classes, `MailBuilder`, `MailBuilderFactory`, `MailSender` and `MailTemplateFactory`.
You can override each of them.

## Installation

At first, you have to register extension.

```yaml
extensions:
    mailing: Contributte\Mailing\DI\MailingExtension
```

## Configuration

Default configuration looks like this.

```yaml
mailing:
    template:
        defaults:
            layout: @@default
        config:
            layout: @@default 
```

Templating and template options are under key **template**. At this moment, there's a default theme (https://github.com/leemunroe/responsive-html-email-template/), simple but good looking.
This default default layout is located in this package, you don't need to change anything. Unless you want your own layout.

- The `defaults` should be untouched and it can be considered as base class. Your theme will be extending the default one.
- The `config` can be considered as child class, define your own theme.

Typical configuration would be override the default theme with some extra features. 

```yaml
template:
    defaults:
        layout: @@default
    config:
        layout: @@mylayout
```

> There are double `@` because of NEON resolving.

## Usage

Example is better then 1k words.

### Builder

```php
/** @var Contributte\Mailing\IMailBuilderFactory @inject */
public $mailBuilderFactory;
```

Thanks to `MailBulderFactory` we use create `MailBuilder` to setup and finally send email.

```php
// Builder
$mail = $this->mailBuilderFactory->create();
$mail->setSubject('It is awesome');
$mail->addTo($user->email);

// Template
$mail->setTemplateFile(__DIR__ . '/../../resources/awesome.latte');
$mail->setParameters([
    'username' => $user->logname,
]);

// Sending
$mail->send();
```

At first moment it looks the `MailBuilder` break the SRP, but it's not true. `MailBulderFactory` creates the `MailBuilder`
and provide the `MailSender` and `MailTemplate`. The `MailBuilder` is just tiny wrapper/builder with enjoyable API.

### Template

Each template has many internal variables:

- `$_defaults` - refer default configuration
- `$_config` - refer custom configuration
- `$_mail` - refer mail configuration (can overrides subject, from, bcc, etc..)

```smarty
{layout $_config->layout}

{block #header}
    Awesome emails.
{/block}

{block #content}
    Hello!
{/block}
```

Each template has many blocks, take a look to source.

