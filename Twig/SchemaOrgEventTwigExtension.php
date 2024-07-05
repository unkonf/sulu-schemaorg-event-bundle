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

namespace UnKonf\Sulu\SchemaOrgEventBundle\Twig;

use Sulu\Component\Webspace\Analyzer\RequestAnalyzerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use UnKonf\Sulu\SchemaOrgEventBundle\Repository\EventRepository;

class SchemaOrgEventTwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly RequestAnalyzerInterface $requestAnalyzer,
        private readonly EventRepository $repository
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('schema_org_event', [$this, 'loadEvent']),
        ];
    }

    public function loadEvent($webspaceKey = null)
    {
        if ($webspaceKey === null) {
            $webspaceKey = $this->requestAnalyzer->getWebspace()->getKey();
        }

        $event = $this->repository->findByWebspaceKey($webspaceKey);
        if ($event === null) {
            return '';
        }

        $schemaOrgEventContent = [
            "@context" => "https://schema.org",
            "@type" => "Event",
            "eventStatus" => "https://schema.org/EventScheduled",
            "eventAttendanceMode" => "https://schema.org/OfflineEventAttendanceMode",
            "location" => [
                "@type" => "Place",
                "address" => [
                    "@type" => "PostalAddress",
                ],
            ],
            "offers" => [
                "@type" => "Offer",
                "availability" => "https://schema.org/InStock",
            ]
        ];

        if ($event->getName() !== '') {
            $schemaOrgEventContent['name'] = $event->getName();
        }

        if ($event->getStartDate() !== '') {
            $schemaOrgEventContent['startDate'] = $event->getName();
        }

        if ($event->getEndDate() !== '') {
            $schemaOrgEventContent['endDate'] = $event->getName();
        }

        if ($event->getLocation()->getName() !== '') {
            $schemaOrgEventContent['location']['name'] = $event->getLocation()->getName();
        }

        if ($event->getLocation()->getStreet() !== '') {
            $schemaOrgEventContent['location']['address']['streetAddress'] = $event->getLocation()->getStreet();
        }

        if ($event->getLocation()->getCity() !== '') {
            $schemaOrgEventContent['location']['address']['addressLocality'] = $event->getLocation()->getCity();
        }

        if ($event->getLocation()->getZipCode() !== '') {
            $schemaOrgEventContent['location']['address']['postalCode'] = $event->getLocation()->getZipCode();
        }

        if ($event->getLocation()->getCountry() !== '') {
            $schemaOrgEventContent['location']['address']['addressCountry'] = $event->getLocation()->getCountry();
        }

        if ($event->getOffer()->getName() !== '') {
            $schemaOrgEventContent['offers']['name'] = $event->getOffer()->getName();
        }

        if ($event->getOffer()->getPrice() !== '') {
            $schemaOrgEventContent['offers']['price'] = $event->getOffer()->getPrice();
        }

        if ($event->getOffer()->getCurrency() !== '') {
            $schemaOrgEventContent['offers']['priceCurrency'] = $event->getOffer()->getCurrency();
        }

        if ($event->getOffer()->getValidFrom() !== '') {
            $schemaOrgEventContent['offers']['validFrom'] = $event->getOffer()->getValidFrom();
        }

        if ($event->getOffer()->getUrl() !== '') {
            $schemaOrgEventContent['offers']['url'] = $event->getOffer()->getUrl();
        }

        return '<script type="application/ld+json">'.json_encode($schemaOrgEventContent, JSON_FORCE_OBJECT).'</script>';
    }
}
