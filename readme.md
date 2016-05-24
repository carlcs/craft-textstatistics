# Text Statistics plugin for Craft CMS

This Craft plugin provides Twig functions to display information about your texts. It allows you to measure the readability of your content, calculate the average reading time or access other text statistics like sentence count.

## Installation

The plugin is available on Packagist and can be installed using Composer. You can also download the [latest release][1] and copy the files into craft/plugins/textstatistics/.

```
$ composer require carlcs/craft-textstatistics
```

  [1]: https://github.com/carlcs/craft-textstatistics/releases/latest

## Settings

The plugin can be configured with a craft/config/textstatistics.php config file to set the default template used to render the reading time string and to set a default words per minute value.

## TextStatisticsModel

When you generate text statistics using the getTextStatistics function the plugin returns a TextStatisticsModel with the following properties populated. Most of the data is calculated with the excellent [Text Statistics][2] library by Dave Child. For information about the provided readability scores have a look these Wikipedia articles:

- [Gunning Fog Index][3]
- [Flesch Kincaid Reading Ease][4]

#### Properties

- `textLength`
- `letterCount`
- `syllableCount`
- `wordCount`
- `sentenceCount`
- `readingTime`
- `readingTimeMinutes`
- `readingTimeString`
- `gunningFog`
- `fleschKincaid`

  [2]: https://github.com/DaveChild/Text-Statistics
  [3]: https://en.wikipedia.org/wiki/Gunning_fog_index
  [4]: https://en.wikipedia.org/wiki/Flesch–Kincaid_readability_tests

## Templating Examples

#### A single article

```twig
{% do addToTextStatistics(entry.text) %}

{% set statistics = getTextStatistics() %}
{{ statistics.readingTimeString }}

{# outputs "21 minutes" #}
```

#### Adjust WPM according to text language

```twig
{% set wpm = {
    'en': 200,
    'fr': 180,
} %}

{% set statistics = getTextStatistics(null, wpm[craft.locale]) %}
{{ statistics.readingTimeString }}

{# outputs "moins de 5 minutes" #}
```

#### Usage with a Matrix field

```twig
{% for block in entry.article %}

    {% if block.type == 'text' %}
        {% do addToTextStatistics(block.text) %}
        {{ block.text }}
    {% endif %}

    {% if block.type == 'image' %}
        {% set image = block.image.first() %}
        {% if image %}
            {% do addToReadingTime(10) %}
            <img src="{{ image.getUrl() }}" alt="{{ image.title }}">
        {% endif %}
    {% endif %}

{% endfor %}

{% set statistics = getTextStatistics() %}
```

## Twig functions

#### addToTextStatistics( text, articleId )

Adds text to the data storage so you can later perform text statistics calculations from it.

- **`text`** (required) – The text to add to the data storage for text statistics. It can contain HTML tags, the plugin removes them before doing its calculations.
- **`articleId`** (default `''`) – An ID to identify the article. Only required if you have multiple articles for text statistics on the same page.

```twig
{% do addToTextStatistics(entry.myRichTextField) %}
```

Alternativly you can use the Twig filter.

```twig
{{ entry.myRichTextField|addToTextStatistics }}
```

#### addToReadingTime( seconds, articleId )

Adds time to the data storage to further influence the reading time returned with the text statistics.

- **`seconds`** (required) – Time in seconds to add to the data storage for text statistics.
- **`articleId`** (default `''`) – An ID to identify the article. Only required if you have multiple articles for text statistics on the same page.

```twig
{% do addToReadingTime(15) %}
```

#### getTextStatistics( articleId, wpm, readingTimeTemplate )

Performs the text statistics calculations for an article and returns the result as a TextStatisticsModel.

- **`articleId`** (default `''`) – An ID to identify the article. Only required if you have multiple articles for text statistics on the same page.
- **`wpm`** (default `200`) – The average reading speed used for the reading time calculations in words per minute units.
- **`readingTimeTemplate`** (default ) – The Twig template code used to render for the reading time string.

```twig
{% set statistics = getTextStatistics() %}
{{ statistics.gunningFog }}

{# outputs "12.1" #}
```

## Requirements

- PHP 5.4+
