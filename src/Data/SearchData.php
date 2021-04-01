<?php


namespace App\Data;


use App\Entity\Campus;

class SearchData
{

    public ?string $q = '';

    public ?Campus $campus;

    public ?\DateTime $max;

    public ?\DateTime $min;

    public ?bool $isOrganizedBy;

    public ?bool $isRegisteredTo;

    public ?bool $isNotRegisteredTo;

    public ?bool $isOver;

}