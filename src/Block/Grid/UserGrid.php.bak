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

use Agere\Grid\Block\AbstractGrid;

class UserGrid extends AbstractGrid implements ObjectManagerAwareInterface {

    use ProvidesObjectManager;

    protected $createButtonTitle = 'Добавить';
    protected $backButtonTitle = '';

    public function init() {

        /** @var ProductRepository $repository */
        $grid = $this->getDataGrid();
        $route = $this->getRouteMatch();
        $view = $this->getViewRenderer();

        $grid->setId('user');
        $grid->setTitle('Пользователи');

        $colId = new Column\Select('id', 'user');
        $colId->setIdentity();
        $grid->addColumn($colId);


        $deleteUrl = $view->url($route->getMatchedRouteName(), [
            'controller' => $route->getParam('controller'),
            'action' => 'delete'
        ]);
        $massAction = new Action\Mass();
        $massAction->setTitle('Удалить');
        $massAction->setLink($deleteUrl);
        $grid->addMassAction($massAction);

        $editUrl = $view->url($route->getMatchedRouteName(), [
            'controller' => $route->getParam('controller'),
            'action' => 'edit'
        ]);
        $formatter = <<<FORMATTER
function (value, options, rowObject) {
	return '<a href="{$editUrl}/' + rowObject.user_id + '" >' + value + '</a>';
}
FORMATTER;

        $col = new Column\Select('fio', 'user');
        $col->setLabel('Фио');
        $col->setTranslationEnabled();
        $col->setWidth(2);
        $col->setRendererParameter('formatter', $formatter, 'jqGrid');
        $grid->addColumn($col);

        $col = new Column\Select('email', 'user');
        $col->setLabel('Email');
        $col->setTranslationEnabled();
        $col->setWidth(2);
        $grid->addColumn($col);

        $col = new Column\Select('role', 'role');
        $col->setLabel('Email');
        $col->setTranslationEnabled();
        $col->setWidth(2);
        $grid->addColumn($col);

        return $grid;
    }

    public function initToolbar() {
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
    }

}