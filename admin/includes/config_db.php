<?php
function getSchema()
{
    return [
        'news' => [
          'menuName' => 'Новости',
          'fields'  => [
                'title' => [
                 'name' => 'Заголовок новости',
                  'element' => 'input',
                  'type' => 'text',
                  'required' => true,
                ],
               'text' => [
                 'name' => 'Текст новости',
                  'element' => 'textarea',
                  'type' => 'text',
                  'required' => true,
                ],
               'date' => [
                 'name' => 'Дата загрузки новости',
                  'element' => 'input',
                  'type' => 'date',
                  'required' => true,
                ],
               'img' => [
                 'name' => 'Картинки новости',
                  'element' => 'input',
                  'type' => 'file',
                  'required' => true,
                ],
            ],
        ],
    
        'events' => [
          'menuName' => 'Мероприятия',
          'fields'  => [
              'title' => [
               'name' => 'Заголовок мероприятия',
                'element' => 'input',
                'type' => 'text',
                'required' => true,
              ],
             'text' => [
               'name' => 'Текст мероприятия',
                'element' => 'textarea',
                'type' => 'text',
                'required' => true,
              ],
             'date' => [
               'name' => 'Дата мероприятия',
                'element' => 'input',
                'type' => 'date',
                'required' => true,
              ],
             'img' => [
               'name' => 'Картинки мероприятия',
                'element' => 'input',
                'type' => 'file',
                'required' => true,
              ],
            ],
        ],
    
        'works' => [
          'menuName' => 'Работы студентов',
          'fields'  => [
             'tags' => [
               'name' => 'Предмет',
                'element' => 'input',
                'type' => 'hidden',
               'data' => [
                  'Искусственный интеллект',
                  'Графический дизайн',
                  'Робототехника',
                  'ЧПУ программирование',
                  'Векторная графика',
                  'Информатика',
                  'Основы электроники',
                ],
               'selectOne' => true,
                'required' => true,
              ],
          
             'img' => [
               'name' => 'Изображения/фотографии',
                'element' => 'input',
                'type' => 'file',
                'required' => true,
              ],
            ],
        ],
    
        'photos' => [
          'menuName' => 'Фотогалерея',
          'fields'  => [
         'img' => [
           'name' => 'Изображения/фотографии',
            'element' => 'input',
            'type' => 'file',
            'required' => true,
          ],
        ],
        ],
    
        'teachers' => [
          'menuName' => 'Преподаватели',
          'fields'  => [
          'title' => [
           'name' => 'Имя преподователя',
            'element' => 'input',
            'type' => 'text',
            'required' => true,
          ],
         'tags' => [
           'name' => 'Предмет',
            'element' => 'input',
            'type' => 'hidden',
           'data' => [
              'Искусственный интеллект',
              'Графический дизайн',
              'Робототехника',
              'ЧПУ программирование',
              'Векторная графика',
              'Информатика',
              'Основы электроники',
            ],
           'selectOne' => true,
            'required' => true,
          ],
         'img' => [
           'name' => 'Фотография',
            'element' => 'input',
            'type' => 'file',
            'required' => true,
          ],
         'text' => [
           'name' => 'Описание',
            'element' => 'textarea',
            'type' => 'text',
            'required' => true,
          ],
        ],
        ],
    
        'lessons' => [
          'menuName' => 'Предметы',
          'fields'  => [
          'title' => [
           'name' => 'Название предмета',
            'element' => 'input',
            'type' => 'text',
            'required' => true,
          ],
         'tags' => [
           'name' => 'Предмет',
            'element' => 'input',
            'type' => 'hidden',
           'data' => [
              'Искусственный интеллект',
              'Графический дизайн',
              'Робототехника',
              'ЧПУ программирование',
              'Векторная графика',
              'Информатика',
              'Основы электроники',
            ],
           'selectOne' => true,
            'required' => true,
          ],
         'text' => [
           'name' => 'Описание',
            'element' => 'textarea',
            'type' => 'text',
            'required' => true,
          ],
         'img' => [
           'name' => 'Фотография',
            'element' => 'input',
            'type' => 'file',
            'required' => true,
          ],
        ],
        ],
    
        'creativity' => [
          'menuName' => 'Творчество',
          'fields'  => [
          'title' => [
           'name'  => 'title', 
            'element' => 'input',
            'type' => 'text',
            'required' => true,
          ],
         'text' => [
           'name' => 'Описание',
            'element' => 'textarea',
            'type' => 'text',
            'required' => true,
          ],
         'img' => [
           'name' => 'Изображения',
            'element' => 'input',
            'type' => 'file',
            'required' => true,
          ],
        ],
      ],
    ];
    
}