<?php
/**
 * MCategoriesModel
 *
 * @author Kensuke Sakakibara
 * @since 2017.03.16
 * @copyright Copyright (c) 2017, Kensuke Sakakibara.
 * 
 * Modelと同名でないTableへのアクセスは禁止しています。
 * 別のTableへアクセスする際は、必ず該当のModelを経由してアクセスしてください。
 */
namespace SuisuiChat\App\Model;

// TusersTable以外のTableは使用してはいけません
use \SuisuiChat\App\Table\MCategoriesTable;
use \SuisuiChat\App\Model\MCategoriesSubModel;

class MCategoriesModel extends AbstractModel
{
    /**
     * コントローラ共通コンストラクタ
     *
     * @param object $container
     */
    public function __construct($container)
    {
        $table = new MCategoriesTable($container);
        parent::__construct($container, $table);
    }
    
    /**
     * カテゴリとサブカテゴリをorder_numで並べて取得
     */
    public function getAllCategories()
    {
        // 一旦全カテゴリを取得して整形する
        $categoriesOrg = $this->_table->getAllOrderNum();
        $categories = array();
        if (!empty($categoriesOrg)) {
            foreach ($categoriesOrg as $row) {
                $row['categories_sub'] = array();
                $categories[$row['id']] = $row;
            }
        }
        
        // 一旦全サブカテゴリを取得する
        $mCategoriesSubModel = new MCategoriesSubModel($this->_container);
        $categoriesSub = $mCategoriesSubModel->getAllOrderNum();
        
        // カテゴリにサブカテゴリを紐づける
        if (!empty($categories) && !empty($categoriesSub)) {
            foreach ($categoriesSub as $row) {
                $categories[$row['category_id']]['categories_sub'][] = $row;
            }
        }
        
        return $categories;
    }
}
