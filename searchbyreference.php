<?php
/**
* 2007-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2022 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/


if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(_PS_MODULE_DIR_. 'searchbyreference/vendor/autoload.php')) {
    require_once _PS_MODULE_DIR_.  'searchbyreference/vendor/autoload.php';
}

class Searchbyreference extends Module
{
    /**
     * @param Cleandev\Searchbyreference\Repository $repository
     */
    protected $repository;

    public $tabs = [];

    public function __construct()
    {
        $this->name = 'searchbyreference';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Cleandev';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();


        $this->repository = new Cleandev\Searchbyreference\Repository($this); 
        
        $this->displayName = $this->l('Effectue une recherche par reference de déclinaison');
        $this->description = $this->l('Effectue une recherche par reference de déclinaison');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        return parent::install() && $this->repository->install();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->repository->uninstall();
    }

    public function hookActionAdminProductsListingFieldsModifier($list) {
        $ref = Tools::getValue('filter_column_ref_dec', false);
        if($ref && !empty($ref) && isset($list['sql_where'])) {
            $list['sql_where'][] = "
                (EXISTS (SELECT 1 FROM `"._DB_PREFIX_."product_attribute` pa WHERE pa.id_product = p.id_product AND pa.reference like '%$ref%')
                OR p.reference like '%$ref%')
            ";
            $_POST['filter_column_ref_dec'] = '';
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        if(Tools::getValue('controller') == 'product') {
            $id_product = Tools::getValue('id_product');
            $id_product_attribute = Tools::getValue('id_product_attribute');
            $reference = Cleandev\Searchbyreference\Classes\Product::getProductReference($id_product, $id_product_attribute);
            Media::addJsDef([
                'product_reference'=>$reference,
                'product_id'=>$id_product,
                'ref_link'=>$this->context->link->getModuleLink($this->name, 'ref'),
            ]);
            $this->context->controller->addJS($this->_path.'/views/js/front.js');
        }
    }
    
}
