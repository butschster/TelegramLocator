<?php

return [
    'command' => [
        'for_manager' => 'Manager commands',
        'for_user' => 'User commands',
        'fallback' => 'Sorry, I did not understand these commands. Use /help command to get a list of available commands.',
        'empty_list_of_commands' => 'Commands not found',
        'invalid_data' => 'Invalid data',
        'error' => 'Sorry, something went wrong',
        'manager' => [
            'register_required' => 'You should register an account. Use /register command.',
            'register' => 'Register as a manager',
            'account_exists' => 'Your account has been already registered.',
            'registered' => 'Hello :username! Welcome to our service! We appreciate your privacy and that\'s why we don\'t store any information about you.'
        ],
        'user' => [
            'unauthorized' => 'Unauthorized! Please use /auth for authentication.',
            'only_current_location' => 'You can only store your current location.',
        ],
        'help' => [
            'description' => 'list of available commands'
        ],
        'location' => [
            'help' => 'Just send your current location from mobile phone.',
            'invalid_lat' => 'The :attribute must be a valid latitude, with a limit of 20 digits after a decimal point',
            'invalid_lng' => 'The :attribute must be a valid longitude, with a limit of 20 digits after a decimal point'
        ],
        'create_room' => [
            'description' => 'Create a new room',
            'token' => 'Telegram bot API token',
            'room_exists' => 'Room with given token exists.',
            'invalid_token' => 'Invalid telegram bot token. Please try again!',
            'registered' => 'Room @:name successful registered.'
        ],
        'delete_room' => [
            'description' => 'Delete room and all points',
            'token' => 'Telegram bot API token',
            'deleted' => 'Room deleted.',
            'room_not_found' => 'Room not found.'
        ],
        'search_nearest_room' => [
            'description' => 'Search public rooms in 1km radius near by coordinates',
            'nothing_found' => 'Nothing found near you.',
            'found_rooms' => 'We found [:total] public rooms near by you.'
        ],
        'room_auth' => [
            'description' => 'Authenticate user by password',
            'password' => 'Room password',
            'auth_not_require' => 'You don\'t need auth.',
            'incorrect_password' => 'Incorrect password. Please try again!',
            'authenticated' => 'Authenticated!'
        ],
        'download_points' => [
            'description' => 'Download points in GeoJson format',
            'result' => 'You can download file here: :link'
        ],
        'get_info' => [
            'description' => 'Get information about room',
        ],
        'make_anonymous' => [
            'description' => 'Make room anonymous',
            'updated' => 'Done.'
        ],
        'make_private' => [
            'description' => 'Make room private',
            'updated' => 'Done. Room is private now.'
        ],
        'make_public' => [
            'description' => 'Make room public',
            'updated' => 'Done. Room is public now.'
        ],
        'remove_password' => [
            'description' => 'Remove room password',
            'updated' => 'Password for room removed.'
        ],
        'set_password' => [
            'description' => 'Set room password',
            'password' => 'Room password',
            'updated' => 'Password for room set.'
        ],
        'set_points_lifetime' => [
            'description' => 'Set room points lifetime.',
            'hours' => 'Lifetime in hours. (0 - infinitely. Max 87600 - 10 years.)',
            'updated' => 'Points lifetime changed.'
        ],
        'store_user_location' => [
            'description' => 'Store user location',
            'stored' => 'Your location has been stored. [lat: :lat, lng: :lng]',
            'slow_down' => 'Slow down...'
        ],
        'update_room_title' => [
            'description' => 'Update room title',
            'arg' => 'Room title',
            'updated' => 'Room title updated.'
        ],
        'update_room_description' => [
            'description' => 'Update room title',
            'arg' => 'Room description',
            'updated' => 'Room description updated.'
        ],
        'update_room_location' => [
            'description' => 'Update room location',
            'updated' => 'Room location updated.'
        ]
    ]
];
