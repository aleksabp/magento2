<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Core
 * @copyright  Copyright (c) 2012 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Create table 'core_theme'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('core_theme'))
    ->addColumn('theme_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'Theme identifier')
    ->addColumn('parent_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('nullable' => true), 'Parent Id')
    ->addColumn('theme_path', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array('nullable' => true), 'Theme Path')
    ->addColumn('theme_version', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array('nullable' => false), 'Theme Version')
    ->addColumn('theme_title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array('nullable' => false), 'Theme Title')
    ->addColumn('preview_image', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array('nullable' => true), 'Preview Image')
    ->addColumn('magento_version_from', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false
    ), 'Magento Version From')
    ->addColumn('magento_version_to', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false
    ), 'Magento Version To')
    ->addColumn('is_featured', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable' => false,
        'default'  => 0
    ), 'Is Theme Featured')
    ->setComment('Core theme');

$installer->getConnection()->createTable($table);

$installer->endSetup();