<?php
/*
 * This file is part of the Sulu Schema.org Event bundle.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace UnKonf\Sulu\SchemaOrgEventBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Offer
{
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private string $name;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private int $price;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private string $currency;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private string $validFrom;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private string $url;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getValidFrom(): string
    {
        return $this->validFrom;
    }

    public function setValidFrom(string $validFrom): void
    {
        $this->validFrom = $validFrom;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }
}
