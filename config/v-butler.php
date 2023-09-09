<?php

return [
    'doorbell' => [
        'speakers' => array_filter(explode(',', env('DOORBELL_SPEAKERS'))),
    ],
];
