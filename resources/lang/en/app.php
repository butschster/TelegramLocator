<?php

return [
    'command' => [
        'for_manager' => 'Manager commands',
        'for_user' => 'User commands',
        'fallback' => 'Sorry, I did not understand these commands. Use /help command to get a list of available commands.',
        'empty_list_of_commands' => 'Commands not found',
        'invalid_data' => 'Invalid data',
        'not_enough_arguments' => 'Not enough arguments',
        'invalid_bot_token' => 'Invalid Telegram bot token',
        'error' => 'Sorry, something went wrong',
        'manager' => [
            'register_required' => 'You should register an account. Use /register command.',
            'register' => 'Register as a manager',
            'account_exists' => 'Your account has been already registered.',
            'registered' => <<<EOL
Hello :username! Welcome to our service! We appreciate your privacy and that\'s why we don\'t store any information about you.
Now you can create rooms and share its with your community.
EOL

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
            'description' => 'Register a new room',
            'token' => 'Telegram bot API token',
            'room_exists' => 'Room with given token exists.',
            'invalid_token' => 'Invalid telegram bot token. Please try again!',
            'registered' => 'Room @:name successful registered. You can create :total more rooms.',
            'reached_max_rooms' => 'You\'ve reached max amount of rooms (:max). You can delete old rooms or send request to increase max amount of rooms.'
        ],
        'delete_room' => [
            'description' => 'Delete room and all points',
            'token' => 'Telegram bot API token',
            'deleted' => 'Room deleted.',
            'room_not_found' => 'Room not found.'
        ],
        'check_signature' => [
            'signature' => 'Room signature.',
            'description' => 'This command will help you checking room belonging to our service. Use /info command in your room to get signature.',
            'valid' => 'Room signature is valid.',
            'invalid' => 'This signature is not valid. Don\'t use this room.',
            'out_of_date' => 'This signature is out of date. If you got it just now from room info, then dont\'t use this room.'
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
            'field' => [
                'id' => 'ID',
                'title' => 'Title',
                'description' => 'Description',
                'total_points' => 'Total points',
                'points_lifetime' => 'Points lifetime',
                'points_noise' => 'Points noise',
                'anonymous' => 'Is anonymous',
                'public' => 'Is public',
                'password_required' => 'Is password required',
                'last_activity' => 'Last activity',
                'points_geojson_url' => 'Points GeoJson URL',
                'points_map_url' => 'Map URL',
                'signature' => 'Signature'
            ],
            'value' => [
                'points_lifetime' => ':hours hrs.',
                'points_lifetime_infinitely' => 'infinitely',
                'points_noise' => ':jitter m.',
                'yes' => 'Yes',
                'no' => 'No',
            ],
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
        'set_jitter' => [
            'description' => 'Add noise to lat,long',
            'jitter' => 'Radius (m.)',
            'updated' => 'Done.'
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
        ],
        'send_message' => [
            'description' => 'Send your message',
            'message' => 'Your message.',
            'sent' => 'Your message was sent.',
            'point_not_found' => 'At first you should send your location'
        ],
        'start' => [
            'message' => <<<EOL
Hello, :username! This is :type room, where you can share your location with other users from this room.
First of all you should know signature of this room: `:signature`
You can check it with :bot.
Use /info command to get information about room.
Use /help command to get commands list.
EOL

        ]
    ],
];
