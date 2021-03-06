<?php

namespace Styleguide\Repositories;

use App\Repositories\PromoRepository as Repository;

class PromoRepository extends Repository
{
    /**
     * {@inheritdoc}
     */
    public function getRequestData(array $data)
    {
        // Define the pages that have under menu promos: page_id => quanity
        $under_menu_page_ids = [
            100 => 2, // Styleguide
        ];

        // Only pull under_menu promos if they match the page_ids that are specified
        $under_menu = isset($under_menu_page_ids[$data['page']['id']]) ? app('Factories\UnderMenu')->create($under_menu_page_ids[$data['page']['id']]) : null;

        // Define the pages that have hero images: page_id => quanity
        $hero_page_ids = [
            1 => 1, // HeroFull
            2 => 3, // HeroFullRotate
            3 => 1, // HeroFullMenu
            101101 => 1, // Homepage
            105100100 => 1, // Hero Contained
            105100101 => 3, // Hero Contained (Rotate)
        ];

        // Only pull hero promos if they match the pages_ids that are specificed
        $hero = isset($hero_page_ids[$data['page']['id']]) ? app('Factories\HeroImage')->create($hero_page_ids[$data['page']['id']]) : null;

        $accordion_page_ids = [
            108100 => 5,
        ];

        // Only pull accordion for childpage template
        $accordion = isset($accordion_page_ids[$data['page']['id']]) ? app('Factories\Accordion')->create($accordion_page_ids[$data['page']['id']]) : null;

        return [
            // Contact footer
            'contact' => app('Factories\FooterContact')->create(1),

            // Social footer
            'social' => app('Factories\FooterSocial')->create(7),

            // Hero
            'hero' => $hero,

            // Under menu
            'under_menu' => $under_menu,

            // Accordion child page
            'accordion_page' => $accordion,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getHomepagePromos()
    {
        return [
            // Example Group
            //'key' => app('Factories\YourFactory')->create(5),
        ];
    }
}
