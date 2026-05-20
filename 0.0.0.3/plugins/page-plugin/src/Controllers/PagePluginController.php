<?php

namespace Plugins\PagePlugin\Controllers;

use Flex\Core\Controllers\BaseController;
use Flex\Core\Routing\View;

class PagePluginController extends BaseController
{
    public function home()
    {
        $data = [
            'title' => 'Начало | Flex CMS',
            'hero_title' => 'Добре дошли в бъдещето на управлението на съдържание',
            'hero_text' => 'Flex CMS ви позволява да изграждате мощни модулни системи с лекота.',
            'cards' => [
                ['icon' => '🚀', 'title' => 'Бързина', 'desc' => 'Оптимизиран рутер и лека архитектура.'],
                ['icon' => '🧩', 'title' => 'Модулност', 'desc' => 'Добавяйте функционалност чрез плъгини.'],
                ['icon' => '🎨', 'title' => 'Гъвкавост', 'desc' => 'Пълен контрол над вашите лейаути.']
            ]
        ];

        $this->render(View::make('home', $data));
    }

    public function about()
    {
        $data = [
            'title' => 'За автора | Flex CMS',
            'author' => [
                'name' => 'Кристиан Костадинов',
                'role' => 'Full-Stack Developer & Creator of Flex CMS',
                'bio' => 'Кристиан е софтуерен разработчик с фокус върху създаването на изчистени и ефективни дигитални решения. Неговата страст към PHP и модерните уеб архитектури води до създаването на Flex CMS – система, създадена за бързина и модулност.',
                'website' => 'https://kriskata.com',
                'skills' => ['PHP', 'Laravel', 'TailwindCSS', 'JavaScript', 'System Architecture']
            ],
            'history' => 'Flex CMS е роден от желанието на Кристиан да предложи на разработчиците инструмент, който не ги ограничава, а им дава солидна основа за надграждане. Проектът съчетава опита му в изграждането на мащабируеми приложения с философията за "чист код".'
        ];

        $this->render(View::make('about', $data));
    }
}