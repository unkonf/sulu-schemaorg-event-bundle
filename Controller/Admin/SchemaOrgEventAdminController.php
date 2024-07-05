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

namespace UnKonf\Sulu\SchemaOrgEventBundle\Controller\Admin;

use UnKonf\Sulu\SchemaOrgEventBundle\Common\DoctrineListRepresentationFactory;
use UnKonf\Sulu\SchemaOrgEventBundle\Entity\Event;
use UnKonf\Sulu\SchemaOrgEventBundle\Entity\Location;
use UnKonf\Sulu\SchemaOrgEventBundle\Entity\Offer;
use UnKonf\Sulu\SchemaOrgEventBundle\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @phpstan-type SchemaOrgEventData array{
 *     id: int|null,
 *     webspace_key: string|null,
 *     name: string,
 *     startDate: string,
 *     endDate: string,
 *     location_name: string,
 *     location_street: string,
 *     location_zipCode: string,
 *     location_city: string,
 *     location_country: string,
 *     offer_name: string,
 *     offer_price: int,
 *     offer_currency: string,
 *     offer_validFrom: string,
 *     offer_url: string
 * }
 */
class SchemaOrgEventAdminController extends AbstractController
{
    public function __construct(
        private readonly EventRepository $repository,
        private readonly DoctrineListRepresentationFactory $doctrineListRepresentationFactory,
    ) {
    }

    #[Route(path: 'schemaorgevent/{id}', name: 'app.get_schemaorgevent', methods: ['GET'])]
    public function getAction(int $id): Response
    {
        $entity = $this->repository->findById($id);
        if (!$entity instanceof Event) {
            throw new NotFoundHttpException();
        }

        return $this->json($this->getDataForEntity($entity));
    }

    #[Route(path: 'schemaorgevent/{id}', name: 'app.put_schemaorgevent', methods: ['PUT'])]
    public function putAction(int $id, Request $request): Response
    {
        $entity = $this->repository->findById($id);
        if (!$entity instanceof Event) {
            throw new NotFoundHttpException();
        }

        /** @var SchemaOrgEventData $data */
        $data = $request->toArray();
        $this->mapDataToEntity($data, $entity);

        $this->repository->save($entity);

        return $this->json($this->getDataForEntity($entity));
    }

    #[Route(path: 'schemaorgevent', name: 'app.post_schemaorgevent', methods: ['POST'])]
    public function postAction(Request $request): Response
    {
        $entity = $this->repository->create();

        /** @var SchemaOrgEventData $data */
        $data = $request->toArray();
        $data['webspace_key'] = $request->get('webspace');
        $this->mapDataToEntity($data, $entity);

        $this->repository->save($entity);

        return $this->json($this->getDataForEntity($entity), 201);
    }

    #[Route(path: 'schemaorgevent/{id}', name: 'app.delete_schemaorgevent', methods: ['DELETE'])]
    public function deleteAction(int $id): Response
    {
        $this->repository->remove($id);

        return $this->json(null, 204);
    }

    #[Route(path: 'schemaorgevent', name: 'app.get_schemaorgevent_list', methods: ['GET'])]
    public function getListAction(): Response
    {
        $listRepresentation = $this->doctrineListRepresentationFactory->createDoctrineListRepresentation(
            Event::RESOURCE_KEY,
        );

        return $this->json($listRepresentation->toArray());
    }

    /**
     * @param Event $entity
     * @return SchemaOrgEventData
     */
    protected function getDataForEntity(Event $entity): array
    {
        return [
            'id' => $entity->getId(),
            'webspace_key' => $entity->getWebspaceKey(),
            'name' => $entity->getName() ?? '',
            'startDate' => $entity->getStartDate(),
            'endDate' => $entity->getEndDate(),
            'location_name' => $entity->getLocation()->getName(),
            'location_street' => $entity->getLocation()->getStreet(),
            'location_zipCode' => $entity->getLocation()->getZipCode(),
            'location_city' => $entity->getLocation()->getCity(),
            'location_country' => $entity->getLocation()->getCountry(),
            'offer_name' => $entity->getOffer()->getName(),
            'offer_price' => $entity->getOffer()->getPrice(),
            'offer_currency' => $entity->getOffer()->getCurrency(),
            'offer_validFrom' => $entity->getOffer()->getValidFrom(),
            'offer_url' => $entity->getOffer()->getUrl(),
        ];
    }

    /**
     * @param SchemaOrgEventData $data
     * @param Event $entity
     */
    protected function mapDataToEntity(array $data, Event $entity): void
    {
        $location = new Location();
        $location->setName($data['location_name']);
        $location->setStreet($data['location_street']);
        $location->setZipCode($data['location_zipCode']);
        $location->setCity($data['location_city']);
        $location->setCountry($data['location_country']);

        $offer = new Offer();
        $offer->setName($data['offer_name']);
        $offer->setPrice($data['offer_price']);
        $offer->setCurrency($data['offer_currency']);
        $offer->setValidFrom($data['offer_validFrom']);
        $offer->setUrl($data['offer_url']);

        $entity->setWebspaceKey($data['webspace_key']);
        $entity->setName($data['name']);
        $entity->setStartDate($data['startDate']);
        $entity->setEndDate($data['endDate']);
        $entity->setLocation($location);
        $entity->setOffer($offer);
    }
}
