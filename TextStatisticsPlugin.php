<?php
namespace Craft;

class TextStatisticsPlugin extends BasePlugin
{
    public function getName()
    {
        return 'Text Statistics';
    }

    public function getVersion()
    {
        return '1.0.0';
    }

    public function getSchemaVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'carlcs';
    }

    public function getDeveloperUrl()
    {
        return 'https://github.com/carlcs';
    }

    public function getDocumentationUrl()
    {
        return 'https://github.com/carlcs/craft-textstatistics';
    }

    public function getReleaseFeedUrl()
    {
        return 'https://github.com/carlcs/craft-textstatistics/raw/master/releases.json';
    }

    // Public Methods
    // =========================================================================

    public function init()
    {
        require_once(CRAFT_PLUGINS_PATH.'textstatistics/vendor/autoload.php');
    }

    public function addTwigExtension()
    {
        Craft::import('plugins.textstatistics.twigextensions.TextStatisticsTwigExtension');
        return new TextStatisticsTwigExtension();
    }
}
