<?php
/**
 * Created by PhpStorm.
 * User: ruslana
 * Date: 19.04.16
 * Time: 1:25
 */
namespace Agere\User\Block\Grid;

use DoctrineModule\Persistence\ProvidesObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

use ZfcDatagrid\Column;
use ZfcDatagrid\Column\Style;
use ZfcDatagrid\Column\Type;
use ZfcDatagrid\Action;

use Agere\ZfcDataGrid\Block\AbstractGrid;

class UserGrid extends AbstractGrid implements ObjectManagerAwareInterface {

    use ProvidesObjectManager;

    protected $createButtonTitle = 'Добавить';
    protected $backButtonTitle = '';

    public function init() {

        $grid = $this->getDataGrid();
        $grid->setId('user');
        $grid->setTitle('Пользователи');
        $grid->setRendererName('jqGrid');

        $colId = $this->add([
            'name' => 'Select',
            'construct' => ['id', 'user'],
            'identity' => true,
        ])->getDataGrid()->getColumnByUniqueId('user_id');

        /*$this->add([
            'name' => 'Select',
            'construct' => ['id', 'user'],
            'label' => 'Id',
            'width' => 1,

            'identity' => false,
        ]);*/

        $this->add([
            'name' => 'Select',
            'construct' => ['fio', 'user'],
            'label' => 'Фио',
            'translation_enabled' => true,
            'width' => 2,
            'formatters' => [
                [
                    'name' => 'Link',
                    'link' => ['href' => '/user/edit', 'placeholder_column' => $colId] // special config
                ],
            ],
        ]);

        $this->add([
            'name' => 'Select',
            'construct' => ['email', 'user'],
            'label' => 'Email',
            'translation_enabled' => true,
            'width' => 2,
        ]);

        $this->add([
            'name' => 'Select',
            'construct' => ['role', 'role'],
            'label' => 'Роль',
            'identity' => false,
            'width' => 2,
        ]);

        return $grid;
    }

    /*public function initToolbar() {
        $grid = $this->getDataGrid();
        $toolbar = $this->getToolbar();
        $route = $this->getRouteMatch();

        // Додати кнопки експорту (відображається лівороч)
        $grid->getResponse()->setVariable('exportRenderers', [
            'PHPExcel' => 'Excel',
            'csv' => 'CSV',
            'tcpdf' => 'PDF'
        ]);

        // додати звичайну кнопку (відображається праворуч)
        $toolbar->addButton('create', [
            'title' => 'Sync',
            'value' => [
                'default' => [ // route name
                    'controller' => $route->getParam('controller'), // route params
                    'action' => 'sync',
                ]
            ],
            'class' => 'btn btn-success btn-xs',
        ]);

        // Додати drop down елементи. Кожен елемент має окрему ланку (відображаються по центрі)
        $toolbar->createActionPanel('Standard')
            ->addAction('Delete', [$route->getMatchedRouteName() => [
                'controller' => $route->getParam('controller'),
                'action' => 'delete',
            ]])->addAction('Change status', [$route->getMatchedRouteName() => [
                'controller' => $route->getParam('controller'),
                'action' => 'changeStatus',
            ]], ['group' => 'prop', 'position' => 50]);

        return $toolbar;
    }*/

}