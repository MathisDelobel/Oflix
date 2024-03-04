<?php

namespace App\DataFixtures;


class AppProvider
{
    private $reactions = [
        'Rire' => 'smile',
        'Pleurer' => 'cry',
        'Réfléchir' => 'think',
        'Dormir' => 'sleep',
        'Rever' => 'dream',
    ];

    public function getReactions()
    {
        return $this->reactions;
    }
}