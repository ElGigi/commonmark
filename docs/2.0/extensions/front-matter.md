---
layout: default
title: Front Matter Extension
description: The Front Matter extension automatically parses YAML front matter from your Markdown.
redirect_from: /extensions/front-matter/
---

# Front Matter Extension

The `FrontMatterExtension` adds the ability to parse YAML front matter from the Markdown document and include that in the return result.

## Installation

This extension is bundled with `league/commonmark`. This library can be installed via Composer:

~~~bash
composer require league/commonmark
~~~

See the [installation](/2.0/installation/) section for more details.

You will also need to install `symfony/yaml` version 2.3 or higher to use this extension:

~~~bash
composer require symfony/yaml
~~~

## Front Matter Syntax

This extension follows the [Jekyll Front Matter syntax](https://jekyllrb.com/docs/front-matter/). The front matter must be the first thing in the file and must take the form of valid YAML set between triple-dashed lines. Here is a basic example:

```md
---
layout: post
title: I Love Markdown
tags:
  - test
  - example
---

# Hello World!
```

This will produce a front matter array similar to this:

```php
$parsedFrontMatter = [
    'layout' => 'post',
    'title' => 'I Love Markdown',
    'tags' => [
        'test',
        'example',
    ],
];
```

And the HTML output will only contain the one heading:

```html
<h1>Hello World!</h1>
```

## Usage

Configure your `Environment` as usual and add the `FrontMatterExtension`:

```php
<?php
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\RenderedContentWithFrontMatter;

// Obtain a pre-configured Environment with all the CommonMark parsers/renderers ready-to-go
$environment = Environment::createCommonMarkEnvironment();

// Add the extension
$environment->addExtension(new FrontMatterExtension());

// Set your configuration if needed
$config = [
    // ...
];

// Instantiate the converter engine and start converting some Markdown!
$converter = new CommonMarkConverter($config, $environment);

$markdown = <<<MD
---
layout: post
title: I Love Markdown
tags:
  - test
  - example
---

# Hello World!
MD;

$result = $converter->convertToHtml($markdown);

// Check if front matter is present:
if ($result instanceof RenderedContentWithFrontMatter) {
    $frontMatter = $result->getFrontMatter();
}

// Output the HTML using any of these:
echo $result;               // implicit string cast
// or:
echo (string) $result;      // explicit string cast
// or:
echo $result->getContent();
```
