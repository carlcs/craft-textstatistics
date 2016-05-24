<?php
namespace Craft;

class TextStatisticsTwigExtension extends \Twig_Extension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'Text Statistics';
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getTextStatistics', [craft()->textStatistics, 'getTextStatistics']),
            new \Twig_SimpleFunction('addToTextStatistics', [craft()->textStatistics, 'addToTextStatistics']),
            new \Twig_SimpleFunction('addToReadingTime', [craft()->textStatistics, 'addToReadingTime']),
        ];
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('addToTextStatistics', [craft()->textStatistics, 'addToTextStatistics']),
        ];
    }
}
