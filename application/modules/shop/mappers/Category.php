<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Modules\Shop\Models\Category as CategoryModel;

class Category extends \Ilch\Mapper
{
    /**
     * Gets categories.
     *
     * @param array $where
     * @return CategoryModel[]|[]
     */
    public function getCategories($where = [])
    {
        $categoryArray = $this->db()->select('*')
            ->from('shop_cats')
            ->where($where)
            ->order(['pos' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($categoryArray)) {
            return [];
        }

        $categories = [];
        foreach ($categoryArray as $categoryRow) {
            $categoryModel = new CategoryModel();
            $categoryModel->setId($categoryRow['id']);
            $categoryModel->setPos($categoryRow['pos']);
            $categoryModel->setTitle($categoryRow['title']);
            $categoryModel->setReadAccess($categoryRow['read_access']);

            $categories[] = $categoryModel;
        }

        return $categories;
    }

    /**
     * Returns category by the id.
     *
     * @param int $id
     * @return null|CategoryModel
     */
    public function getCategoryById($id)
    {
        $category = $this->getCategories(['id' => $id]);

        if (!$category) {
            return null;
        }

        return reset($category);
    }
    
    /**
     * Updates the position of cats in the database.
     *
     * @param int $id
     * @param int $position
     */
    public function updatePositionById($id, $position) {
        $this->db()->update('shop_cats')
            ->values(['pos' => $position])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Inserts or updates category model.
     *
     * @param CategoryModel $category
     */
    public function save(CategoryModel $category)
    {
        if ($category->getId()) {
            $this->db()->update('shop_cats')
                ->values([
                    'title' => $category->getTitle(),
                    'read_access' => $category->getReadAccess()
                ])
                ->where(['id' => $category->getId()])
                ->execute();
        } else {
            $maxPos = $this->db()->select('MAX(pos)')
                      ->from('shop_cats')
                      ->execute()
                      ->fetchCell();
                      
            $this->db()->insert('shop_cats')
                ->values([
                    'title' => $category->getTitle(),
                    'pos' => $maxPos+1,
                    'read_access' => $category->getReadAccess()
                ])
                ->execute();
        }
    }

    /**
     * Deletes category with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('shop_cats')
            ->where(['id' => $id])
            ->execute();
    }
}
