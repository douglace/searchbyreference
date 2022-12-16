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

namespace Cleandev\Searchbyreference\Classes;

use Db;

class Product
{
    public static function getProductReference($id_product, $id_product_attribute = null)
    {
        if($id_product_attribute) {
            return Db::getInstance()->getValue("SELECT reference FROM `"._DB_PREFIX_."product_attribute` where id_product = $id_product AND id_product_attribute = $id_product_attribute");
        }
        return Db::getInstance()->getValue("SELECT reference FROM `"._DB_PREFIX_."product` where id_product = $id_product"); 
    }
}