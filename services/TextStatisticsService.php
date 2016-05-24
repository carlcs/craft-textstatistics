<?php
namespace Craft;

use DaveChild\TextStatistics\TextStatistics;
use DaveChild\TextStatistics\Text;
use DaveChild\TextStatistics\Syllables;

class TextStatisticsService extends BaseApplicationComponent
{
    // Properties
    // =========================================================================

    /**
     * @var \DaveChild\TextStatistics
     */
    protected $textStatistics;

    /**
     * @var array
     */
    protected $articlesData = [];

    // Public Methods
    // =========================================================================

    /**
     * Stores additional reading time for an article's text statistics.
     *
     * @param int $seconds
     * @param string $articleId
     *
     * @return null
     */
    public function addToReadingTime($seconds, $articleId = '')
    {
        $articleData = &$this->getArticleDataById($articleId);

        $articleData['additionalTime'] += $seconds;
    }

    /**
     * Stores additional text for an article's text statistics.
     *
     * @param int $text
     * @param string $articleId
     *
     * @return null
     */
    public function addToTextStatistics($text, $articleId = '')
    {
        $articleData = &$this->getArticleDataById($articleId);

        $articleData['text'] .= $text;
    }

    /**
     * Returns the text statistics for an article.
     *
     * @param string $articleId
     * @param string $template
     *
     * @return TextStatisticsModel
     */
    public function getTextStatistics($articleId = '', $wpm = null, $readingTimeTemplate = null)
    {
        if (!isset($this->articlesData[$articleId])) {
            return null;
        }

        $articleData = &$this->getArticleDataById($articleId);

        $wordCount = Text::wordCount($articleData['text']);
        $wpm = $wpm ?: craft()->config->get('wordsPerMinute', 'textStatistics');

        // Calculate times in seconds
        $textReadingTime = $wordCount / $wpm * 60;
        $additionalTime = $articleData['additionalTime'];
        $totalTime = ceil($textReadingTime + $additionalTime);

        $readingTime = $this->getDateIntervalFromSeconds($totalTime);
        $readingTimeMinutes = ceil($totalTime / 60);

        // Render Twig template for the readingTimeString
        $variables = [
            'iv' => $readingTime,
            'min' => $readingTimeMinutes,
        ];

        $template = $readingTimeTemplate ?: craft()->config->get('readingTimeTemplate', 'textStatistics');
        $readingTimeString = craft()->templates->renderString($template, $variables);

        // Populate Statistics model
        $model = new TextStatisticsModel();

        $model->textLength    = Text::textLength($articleData['text']);
        $model->letterCount   = Text::letterCount($articleData['text']);
        $model->syllableCount = Syllables::syllableCount($articleData['text']);
        $model->wordCount     = $wordCount;
        $model->sentenceCount = Text::sentenceCount($articleData['text']);

        $model->readingTime        = $readingTime;
        $model->readingTimeMinutes = $readingTimeMinutes;
        $model->readingTimeString  = $readingTimeString;

        $textStatistics = $this->getTextStatisticsClass();

        $model->gunningFog    = $textStatistics->gunningFogScore($articleData['text']);
        $model->fleschKincaid = $textStatistics->fleschKincaidReadingEase($articleData['text']);

        if (!$model->validate()) {
            throw new Exception('There was an error while generating the Text Statistics.');
        }

        return $model;
    }

    // Protected Methods
    // =========================================================================

    /**
     * Returns our TextStatistics instance.
     *
     * @return \DaveChild\TextStatistics\TextStatistics
     */
    protected function getTextStatisticsClass()
    {
        if (!$this->textStatistics) {
            $this->textStatistics = new TextStatistics;
        }

        return $this->textStatistics;
    }

    /**
     * Returns article data by article ID
     *
     * @param string $articleId
     *
     * @return array
     */
    protected function &getArticleDataById($articleId)
    {
        if (!isset($this->articlesData[$articleId])) {
            $this->articlesData[$articleId] = [
                'text' => '',
                'additionalTime' => 0,
            ];
        }

        return $this->articlesData[$articleId];
    }

    /**
     * Creates a DateInterval object from seconds.
     *
     * @param int $seconds
     *
     * @return \Craft\DateInterval
     */
    protected function getDateIntervalFromSeconds($seconds)
    {
        $d1 = new DateTime();
        $d2 = new DateTime();
        $d2->add(new DateInterval('PT'.$seconds.'S'));

        return $d2->diff($d1);
    }
}
