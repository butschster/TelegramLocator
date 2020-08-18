<?php

return [
    'command' => [
        'for_manager' => 'Управление',
        'for_user' => 'Пользовательские команды',
        'fallback' => 'Извините, я вас не понял. Используйте команду /help для получения списка доступных команд.',
        'empty_list_of_commands' => 'Команды не найдены.',
        'invalid_data' => 'Данные не прошли валидацию.',
        'error' => 'Что-то пошло не так!',
        'manager' => [
            'register_required' => 'Для начала вы должны создать аккаунт. Используйте команду /register.',
            'register' => 'Регистрация в системе',
            'account_exists' => 'Вы уже зарегистрированы в системе.',
            'registered' => 'Привет :username! Добро пожаловать в наш сервис! Мы ценим вашу приватность и поэтому мы не храним информацию о вас.'
        ],
        'user' => [
            'unauthorized' => 'Нет доступа! Используйте команду /auth для авторизации в комнате.',
            'only_current_location' => 'Можно сохранять только свое текущее местоположение.',
        ],
        'help' => [
            'description' => 'Список доступных команд'
        ],
        'location' => [
            'help' => 'Просто отправьте своё текущее местоположение боту с мобильного телефона.',
            'invalid_lat' => 'The :attribute must be a valid latitude, with a limit of 20 digits after a decimal point',
            'invalid_lng' => 'The :attribute must be a valid longitude, with a limit of 20 digits after a decimal point'
        ],
        'create_room' => [
            'description' => 'Регистрация новой комнаты',
            'token' => 'Telegram bot API token',
            'room_exists' => 'Комната с таким токеном существует.',
            'invalid_token' => 'Не верный telegram bot token.',
            'registered' => 'Комната @:name успешно зарегистрирована.'
        ],
        'delete_room' => [
            'description' => 'Удалении комнаты и всех данных',
            'token' => 'Telegram bot API token',
            'deleted' => 'Комната удалена.',
            'room_not_found' => 'Комната не найдена.'
        ],
        'search_nearest_room' => [
            'description' => 'Поиск публичных комнат рядом с переданным местоположением',
            'nothing_found' => 'Ничего не найдено рядом с вами.',
            'found_rooms' => 'Мы нашли [:total] комнат рядом с вами.'
        ],
        'room_auth' => [
            'description' => 'Авторизация в комнате по паролю',
            'password' => 'Пароль',
            'auth_not_require' => 'Авторизация не требуется.',
            'incorrect_password' => 'Не верный пароль. Попробуйте еще раз!',
            'authenticated' => 'Вы успешно авторизованы!'
        ],
        'download_points' => [
            'description' => 'Скачать список точек в формате GeoJson',
            'result' => 'Вы можете скачать файл по этой ссылке: :link'
        ],
        'get_info' => [
            'description' => 'Получение информации о комнате',
        ],
        'make_anonymous' => [
            'description' => 'Сделать комнату анонимной',
            'updated' => 'Готово.'
        ],
        'make_private' => [
            'description' => 'Сделать комнату приватной',
            'updated' => 'Готово.'
        ],
        'make_public' => [
            'description' => 'Сделать комнату публичной',
            'updated' => 'Готово.'
        ],
        'remove_password' => [
            'description' => 'Удалить пароль для комнаты',
            'updated' => 'Готово.'
        ],
        'set_password' => [
            'description' => 'Установить пароль для комнаты',
            'password' => 'Пароль',
            'updated' => 'Готово.'
        ],
        'set_points_lifetime' => [
            'description' => 'Установить время жизни переданных кооржинат.',
            'hours' => 'Время в часах. (0 - infinitely. Max 87600 - 10 years.)',
            'updated' => 'Готово.'
        ],
        'store_user_location' => [
            'description' => 'Предать своё местоположение',
            'stored' => 'Ваше местоположение сохранено. [lat: :lat, lng: :lng]',
            'slow_down' => 'Не так часто...'
        ],
        'update_room_title' => [
            'description' => 'Обновить название комнаты',
            'arg' => 'Название',
            'updated' => 'Готово.'
        ],
        'update_room_description' => [
            'description' => 'Обновить описание комнаты',
            'arg' => 'Описание',
            'updated' => 'Готово.'
        ],
        'update_room_location' => [
            'description' => 'Обновить местоположение комнаты',
            'updated' => 'Готово.'
        ]
    ]
];