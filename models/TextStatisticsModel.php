<?php
namespace Craft;

class TextStatisticsModel extends BaseComponentModel
{
    /**
     * Defines this model's attributes.
     *
     * @return array
     */
    protected function defineAttributes()
    {
        return [
            'textLength'    => AttributeType::Number,
            'letterCount'   => AttributeType::Number,
            'syllableCount' => AttributeType::Number,
            'wordCount'     => AttributeType::Number,
            'sentenceCount' => AttributeType::Number,

            'readingTime'        => AttributeType::Mixed,
            'readingTimeMinutes' => AttributeType::Number,
            'readingTimeString'  => AttributeType::String,

            'gunningFog'    => array(AttributeType::Number, 'decimals' => 4),
            'fleschKincaid' => array(AttributeType::Number, 'decimals' => 4),
        ];
    }
}
