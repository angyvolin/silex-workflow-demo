<?php

return array(
    'article' => array(
        'type' => 'workflow',
        'supports' => array(
            'Entity\\Article',
        ),
        'places' => array(
            'draft',
            'wait_for_journalist',
            'approved_by_journalist',
            'wait_for_spellchecker',
            'approved_by_spellchecker',
            'published',
        ),
        'transitions' => array(
            'request_review' => array(
                'from' => 'draft',
                'to' => array(
                    'wait_for_journalist',
                    'wait_for_spellchecker',
                ),
            ),
            'journalist_approval' => array(
                'from' => 'wait_for_journalist',
                'to' => 'approved_by_journalist',
            ),
            'spellchecker_approval' => array(
                'from' => 'wait_for_spellchecker',
                'to' => 'approved_by_spellchecker',
            ),
            'publish' => array(
                'from' => array(
                    'approved_by_journalist',
                    'approved_by_spellchecker'
                ),
                'to' => 'published',
            ),
        ),
    ),
    'task' => array(
        'type' => 'state_machine',
        'supports' => array(
            'Entity\\Task',
        ),
        'places' => array(
            'backlog',
            'in_progress',
            'done',
        ),
        'transitions' => array(
            'processing' => array(
                'from' => 'backlog',
                'to' => 'in_progress',
            ),
            'done' => array(
                'from' => 'in_progress',
                'to' => 'done',
            ),
        ),
    ),

    'straight' => array(
        'type' => 'state_machine',
        'supports' => array(
            'stdClass', # Just a hack
        ),
        'places' => array('a', 'b', 'c', 'd'),
        'transitions' => array(
            't1' => array(
                'from' => 'a',
                'to' => 'b',
            ),
            't2' => array(
                'from' => 'b',
                'to' => 'c',
            ),
            't3' => array(
                'from' => 'c',
                'to' => 'd',
            ),
        ),
    ),
    'round_trip' => array(
        'type' => 'workflow',
        'supports' => array(
            'stdClass', # Just a hack
        ),
        'places' => array('a', 'b', 'c'),
        'transitions' => array(
            't1' => array(
                'from' => 'a',
                'to' => 'b',
            ),
            't2' => array(
                'from' => 'b',
                'to' => 'c',
            ),
            't3' => array(
                'from' => 'c',
                'to' => 'a',
            ),
        ),
    ),
    'or' => array(
        'type' => 'workflow',
        'supports' => array(
            'stdClass', # Just a hack
        ),
        'places' => array('a', 'b', 'c', 'd'),
        'transitions' => array(
            't1' => array(
                'from' => 'a',
                'to' => 'b',
            ),
            't2' => array(
                'from' => 'a',
                'to' => 'c',
            ),
            't3' => array(
                'from' => 'b',
                'to' => 'd',
            ),
            't4' => array(
                'from' => 'c',
                'to' => 'd',
            ),
        ),
    ),
    'and' => array(
        'type' => 'workflow',
        'supports' => array(
            'stdClass', # Just a hack
        ),
        'places' => array('a', 'b', 'c', 'd', 'e', 'f'),
        'transitions' => array(
            't1' => array(
                'from' => 'a',
                'to' => array('b', 'c'),
            ),
            't2' => array(
                'from' => 'b',
                'to' => 'd',
            ),
            't3' => array(
                'from' => 'c',
                'to' => 'e',
            ),
            't4' => array(
                'from' => array('d', 'e'),
                'to' => 'f',
            ),
        ),
    ),
    'wtf' => array(
        'type' => 'workflow',
        'supports' => array(
            'stdClass', # Just a hack
        ),
        'places' => array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k'),
        'transitions' => array(
            't1' => array(
                'from' => 'a',
                'to' => 'b',
            ),
            't2' => array(
                'from' => 'b',
                'to' => 'c',
            ),
            't3' => array(
                'from' => 'c',
                'to' => 'd',
            ),
            't4' => array(
                'from' => 'b',
                'to' => 'e',
            ),
            't5' => array(
                'from' => 'b',
                'to' => 'f'
            ),
            't6' => array(
                'from' => array('c', 'd'),
                'to' => array('f', 'g'),
            ),
            't7' => array(
                'from' => 'e',
                'to' => 'h',
            ),
            't8' => array(
                'from' => array('e', 'g', 'i'),
                'to' => 'h',
            ),
            't9' => array(
                'from' => array('f', 'g'),
                'to' => array('i', 'j'),
            ),
            't10' => array(
                'from' => 'h',
                'to' => 'k',
            ),
        ),
    ),
);
