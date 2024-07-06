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

namespace UnKonf\Sulu\SchemaOrgEventBundle\Admin;

use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Admin\View\ToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ViewBuilderFactoryInterface;
use Sulu\Bundle\AdminBundle\Admin\View\ViewCollection;
use Sulu\Bundle\PageBundle\Admin\PageAdmin;
use Sulu\Component\Security\Authorization\PermissionTypes;
use Sulu\Component\Security\Authorization\SecurityCheckerInterface;
use Sulu\Component\Security\Authorization\SecurityCondition;
use UnKonf\Sulu\SchemaOrgEventBundle\Entity\Event;

class SchemaOrgEventAdmin extends Admin
{
    final public const SYSTEM = 'UnKonf';
    final public const SECURITY_CONTEXT = 'unkonf.schemaorgevent';
    final public const SCHEMAORGEVENT_LIST_KEY = 'schemaorgevent';
    final public const SCHEMAORGEVENT_LIST_VIEW = 'unkonf.schemaorgevent_list';

    public function __construct(
        private readonly ViewBuilderFactoryInterface $viewBuilderFactory,
        private readonly SecurityCheckerInterface    $securityChecker
    ) {
    }

    public function configureViews(ViewCollection $viewCollection): void
    {
        $securityCondition = new SecurityCondition(static::SECURITY_CONTEXT, null, null, null, self::SYSTEM);

        $toolbarActions = [];

        if ($this->securityChecker->hasPermission($securityCondition, PermissionTypes::ADD)) {
            $toolbarActions[] = new ToolbarAction('sulu_admin.add');
        }

        if ($this->securityChecker->hasPermission($securityCondition, PermissionTypes::DELETE)) {
            $toolbarActions[] = new ToolbarAction('sulu_admin.delete');
        }

        if ($this->securityChecker->hasPermission($securityCondition, PermissionTypes::VIEW)) {
            $viewCollection->add(
                $this->viewBuilderFactory
                    ->createFormOverlayListViewBuilder(static::SCHEMAORGEVENT_LIST_VIEW, '/schemaorgevent')
                    ->setResourceKey(Event::RESOURCE_KEY)
                    ->setListKey(self::SCHEMAORGEVENT_LIST_KEY)
                    ->addListAdapters(['table'])
                    ->addAdapterOptions(['table' => ['skin' => 'light']])
                    ->addRouterAttributesToListRequest(['webspace'])
                    ->addRouterAttributesToFormRequest(['webspace'])
                    ->disableSearching()
                    ->setFormKey('schemaorgevent_details')
                    ->setTabTitle('schemaorgevent.title')
                    ->setTabOrder(2048)
                    ->addToolbarActions($toolbarActions)
                    ->setParent(PageAdmin::WEBSPACE_TABS_VIEW)
                    ->addRerenderAttribute('webspace')
            );
        }
    }

    public function getSecurityContexts()
    {
        return [
            self::SYSTEM => [
                'SchemaOrgEvent' => [
                    static::SECURITY_CONTEXT => [
                        PermissionTypes::VIEW,
                        PermissionTypes::ADD,
                        PermissionTypes::DELETE,
                    ],
                ],
            ],
        ];
    }

    public function getConfigKey(): ?string
    {
        return 'unkonf.schemaorgevent';
    }
}
