<?php

namespace Styleguide\Pages;

class HeaderTitleDouble extends Page
{
    /**
     * {@inheritdoc}
     */
    public function getPageData()
    {
        return app('Factories\Page')->create(1, [
            'page' => [
                'controller' => 'HeaderTitleDoubleController',
                'title' => 'Subtitle',
                'id' => 102100101,
                'content' => [
                    'main' => '',
                ],
            ],
            'menu' => [
                'id' => 1,
            ],
        ]);
    }
}
