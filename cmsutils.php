<?php

/*
 * CMSUtils - a module for Prestashop v1.6+
 * Copyright (C) 2014 kuzmany.biz
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('_PS_VERSION_'))
    exit;

require_once(_PS_MODULE_DIR_ . 'cmsutils/models/cmsutils_smarty.php');

class CMSUtils extends Module {

    public function __construct() {
        $this->name = 'cmsutils';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'kuzmany.biz';
        $this->need_instance = 0;
        // $this->dependencies = array('blockcart');

        parent::__construct();

        $this->displayName = $this->l('CMS Utils');
        $this->description = $this->l('A lot of CMS stuff. ');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    /**
     * install
     */
    public function install() {
        if (!parent::install() ||
                !$this->registerHook('displayAdminProductsExtra') ||
                !$this->registerHook('productTab') ||
                !$this->registerHook('productTabContent') ||
                !$this->registerHook('displayHeader'))
            return false;
        return true;
    }

    /**
     * uninstall
     */
    public function uninstall() {
        if (!parent::uninstall())
            return false;
        return true;
    }

    // header
    // fetch smarty tags
    public function hookHeader($params) {
        if (!isset($this->context->smarty->registered_plugins['function']['cms_selflink']))
            $this->context->smarty->registerPlugin('function', 'cms_selflink', array('cmsutils_smarty', 'cms_selflink'));
        if (!isset($this->context->smarty->registered_plugins['function']['cms_content']))
            $this->context->smarty->registerPlugin('function', 'cms_content', array('cmsutils_smarty', 'cms_content'));
        if (!isset($this->context->smarty->registered_plugins['function']['cms_title']))
            $this->context->smarty->registerPlugin('function', 'cms_title', array('cmsutils_smarty', 'cms_title'));
        if (!isset($this->context->smarty->registered_plugins['function']['cms_get_data']))
            $this->context->smarty->registerPlugin('function', 'cms_get_data', array('cmsutils_smarty', 'cms_get_data'));
    }

}

?>
