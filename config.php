<?php

return [
    'wordsPerMinute' => 200,
    'readingTimeTemplate' => "{{ min < 5 ? 'less than 5 minutes'|t : min ~ ' ' ~ 'minutes'|t }}",
];
