<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers\Admin;

use Ilch\Controller\Admin;
use Modules\Shop\Mappers\Category as CategoryMapper;
use Modules\Shop\Models\Category as CategoryModel;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\Shop\Mappers\Items as ItemsMapper;

class Cats extends Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuOverwiev',
                'active' => false,
                'icon' => 'fas fa-store-alt',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuItems',
                'active' => false,
                'icon' => 'fas fa-tshirt',
                'url' => $this->getLayout()->getUrl(['controller' => 'items', 'action' => 'index'])
            ],
            [
                'name' => 'menuOrders',
                'active' => false,
                'icon' => 'fas fa-cart-arrow-down',
                'url' => $this->getLayout()->getUrl(['controller' => 'orders', 'action' => 'index'])
            ],
            [
                'name' => 'menuCats',
                'active' => false,
                'icon' => 'fas fa-list-alt',
                'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuCurrencies',
                'active' => false,
                'icon' => 'fas fa-money-bill-alt',
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ],
            [
                'name' => 'menuNote',
                'active' => false,
                'icon' => 'fas fa-info-circle',
                'url' => $this->getLayout()->getUrl(['controller' => 'note', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[3][0]['active'] = true;
        } else {
            $items[3]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuShops',
            $items
        );
    }

    public function indexAction()
    {
        $categoryMapper = new CategoryMapper();
        $itemsMapper = new ItemsMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete') {
            $errorDelCat = 0;
            
            foreach ($this->getRequest()->getPost('check_cats') as $catId) {
                $countItems = count($itemsMapper->getShopItems(['cat_id' => $catId]));
                if ($countItems == 0) {
                    $categoryMapper->delete($catId);
                } else {
                    $errorDelCat = 1;
                }
            }
            
            if ($errorDelCat == 0) {
                $this->addMessage('deleteSuccess');
            } else {
                $this->addMessage('deleteCatsFailed', 'danger');
            }

            $this->redirect(['action' => 'index']);
            
        } elseif ($this->getRequest()->getPost('save') && $this->getRequest()->getPost('positions')) {
            $postData = $this->getRequest()->getPost('positions');
            $positions = explode(',', $postData);
            foreach ($positions as $x => $xValue) {
                $categoryMapper->updatePositionById($xValue, $x);
            }
            $this->addMessage('saveSuccess');
            $this->redirect(['action' => 'index']);
        } 

        $this->getView()->set('itemsMapper', $itemsMapper);
        $this->getView()->set('cats', $categoryMapper->getCategories());
    }

    public function treatAction()
    {
        $categoryMapper = new CategoryMapper();
        $groupMapper = new GroupMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('cat', $categoryMapper->getCategoryById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $model = new CategoryModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $title = trim($this->getRequest()->getPost('title'));

            $groups = '';
            if (!empty($this->getRequest()->getPost('groups'))) {
                $groups = implode(',', $this->getRequest()->getPost('groups'));
            }

            if (empty($title)) {
                $this->addMessage('missingTitle', 'danger');
            } else {
                $model->setTitle($title);
                $model->setReadAccess($groups);
                $categoryMapper->save($model);

                $this->addMessage('saveSuccess');

                $this->redirect(['action' => 'index']);
            }
        }

        if ($this->getRequest()->getParam('id')) {
            $groups = explode(',', $categoryMapper->getCategoryById($this->getRequest()->getParam('id'))->getReadAccess());
        } else {
            $groups = [1,2,3];
        }

        $this->getView()->set('groups', $groups);
        $this->getView()->set('userGroupList', $groupMapper->getGroupList());
    }

    public function delCatAction()
    {
        $itemsMapper = new ItemsMapper();
        $countItems = count($itemsMapper->getShopItems(['cat_id' => $this->getRequest()->getParam('id')]));

        if ($countItems == 0) {
            if ($this->getRequest()->isSecure()) {
                $categoryMapper = new CategoryMapper();
                $categoryMapper->delete($this->getRequest()->getParam('id'));
                $this->addMessage('deleteSuccess');
            }
        } else {
            $this->addMessage('deleteCatFailed', 'danger');
        }
        
        $this->redirect(['action' => 'index']);
    }

}
