<?php

/*
 * CMSUtils - a module for Prestashop v1.6+
 * cms_selflink is port of the same CMS Made Simple tag (www.cmsmadesimple.org)
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

class cmsutils_smarty extends Module {

    private static $_cache;

    //get cms content with cache
    private static function get_cms_content($id_cms, $block) {
        if (!isset($_cache[__FUNCTION__][$id_cms]))
            $_cache[__FUNCTION__][$id_cms] = Db::getInstance()->getRow("SELECT * FROM " . _DB_PREFIX_ . "cms_lang WHERE id_lang='" . (int) (Context::getContext()->language->id) . "' AND id_cms = '" . (int) $id_cms . "'");

        return $_cache[__FUNCTION__][$id_cms][$block];
    }

    public static function cms_content($params) {
        $smarty = Context::getContext()->smarty;
        if (!isset($params['page']))
            return;
        $result = self::get_cms_content($params['page'], 'content');
        if (isset($params['assign'])) {
            $smarty->assign(trim($params['assign']), $result);
            return;
        }
        return $result;
    }

    public static function cms_title($params) {
        $smarty = Context::getContext()->smarty;
        if (!isset($params['page']))
            return;
        $result = self::get_cms_content($params['page'], 'meta_title');
        if (isset($params['assign'])) {
            $smarty->assign(trim($params['assign']), $result);
            return;
        }
        return $result;
    }

    public static function cms_get_data($params) {
        $smarty = Context::getContext()->smarty;
        if (!isset($params['block']))
            return;
        $result = self::get_cms_content($params['page'], $params['block']);
        if (isset($params['assign'])) {
            $smarty->assign(trim($params['assign']), $result);
            return;
        }
        return $result;
    }

    public static function cms_selflink($params) {
        $smarty = Context::getContext()->smarty;
        $link = Context::getContext()->link;

        $url = '';
        $urlparam = '';
        $label_side = 'left';
        $label = '';
        $urlonly = 0;
        $node = null;
        $dir = null;
        $pageid = null;

        if (isset($params['urlparam']) && ( strlen($params['urlparam']) > 0 )) {
            $urlparam = trim($params['urlparam']);
        }

        if (isset($params['page']) or isset($params['href'])) {
            $page = null;
            if (isset($params['href'])) {
                $page = $params['href'];
                $urlonly = 1;
            } else {
                $page = $params['page'];
            }
            if ($page) {
                if ((int) $page > 0) {
                    $pageid = (int) $page;
                } else {
                    // if  page id doesn't exist
                    return;
                }
            }
        }
        if (isset($params['dir'])) {
            $startpage = null;
            if ($pageid)
                $startpage = $pageid;
            if (!$startpage)
                $startpage = Tools::getValue('id_cms');
            $dir = strtolower(trim($params['dir']));

            switch ($dir) {
                case 'next':
                case 'prev':
                case 'first':
                case 'last':
                    // next visible page.
                    $cms = new CMS($startpage, Context::getContext()->language->id);
                    $flatcontent = CMS::getCMSPages(Context::getContext()->language->id, $cms->id_cms_category);
                    if ($dir == 'first') {
                        $pageid = $flatcontent[0]['id_cms'];
                        break;
                    }
                    if ($dir == 'last') {
                        if (count($flatcontent) > 1)
                            $pageid = $flatcontent[count($flatcontent) - 1]['id_cms'];
                        else
                            $pageid = '';
                        break;
                    }

                    $history = array();
                    $next = false;
                    foreach ($flatcontent as $content) {

                        // next page id
                        if ($dir == 'next' && $next == true) {
                            $pageid = $content['id_cms'];
                            break;
                        }
                        //prev page id
                        if ($dir == 'prev' && $next == true) {
                            if (count($history) > 1)
                                $pageid = $history[count($history) - 2];
                            else
                                $pageid = '';
                            break;
                        }

                        if ($content['id_cms'] == $startpage)
                            $next = true;

                        $history[] = $content['id_cms'];
                    }
                    break;
                default:
                    // unknown direction... prolly should do something here.
                    return;
            }
        }
        if ($pageid == '')
            return;

        // one final check to see if this page exists.
        $node = new CMS($pageid, Context::getContext()->language->id);
        if (!$node)
            return;

        // get the content object.
        $content = $node;

        // get our raw display data
        $name = $content->meta_title;
        $url = $link->getCMSLink($pageid, $content->link_rewrite);

        if (isset($params['anchorlink']))
            $url .= '#' . ltrim($params['anchorlink'], '#');
        if ($urlparam != '')
            $url .= $urlparam;

        if (empty($url))
            return; // no url to link to, therefore nothing to do.

        if (isset($params['urlonly']))
            $urlonly = Tools::boolVal($params['urlonly']);

        if ($urlonly) {
            if (isset($params['assign'])) {
                $smarty->assign(trim($params['assign']), $url);
                return;
            }
            return $url;
        }

        // Now we build the output.
        $result = "";
        if (isset($params['label'])) {
            $label = $params['label'];
            $label = Tools::htmlentitiesUTF8($label);
        }

        $title = (isset($name)) ? $name : '';
        if (isset($params['title'])) {
            $title = $params['title'];
        }

        $title = Tools::htmlentitiesUTF8($title);

        if (isset($params['label_side']))
            $label_side = strtolower(trim($params['label_side']));
        if ($label_side == 'left')
            $result .= $label . ' ';
        $result .= '<a href="' . $url . '"';
        $result .= ' title="' . $title . '" ';
        if (isset($params['target']))
            $result .= ' target="' . $params['target'] . '"';
        if (isset($params['id']))
            $result .= ' id="' . $params['id'] . '"';
        if (isset($params['class']))
            $result .= ' class="' . $params['class'] . '"';
        if (isset($params['tabindex']))
            $result .= ' tabindex="' . $params['tabindex'] . '"';
        if (isset($params['more']))
            $result .= ' ' . $params['more'];
        $result .= '>';

        $linktext = $name;
        if (isset($params['text'])) {
            $linktext = $params['text'];
        }

        if (isset($params['image']) && !empty($params['image'])) {
            $width = (isset($params['width']) && !empty($params['width'])) ? (int) $params['width'] : '';
            $height = (isset($params['height']) && !empty($params['height'])) ? (int) $params['height'] : '';
            $alt = (isset($params['alt']) && !empty($params['alt'])) ? $params['alt'] : '';
            $result .= "<img src=\"{$params['image']}\" alt=\"$alt\"";
            if ($width)
                $width = max(1, $width);
            if ($width)
                $result .= " width=\"$width\"";
            if ($height)
                $height = max(1, $height);
            if ($height)
                $result .= " height=\"$height\"";
            $result .= "/>";
            if (!(isset($params['imageonly']) && $params['imageonly']))
                $result .= " $linktext";
        } else {
            $result .= $linktext;
        }

        $result .= '</a>';
        if ($label_side == 'right')
            $result .= ' ' . $label;

        $result = trim($result);
        if (isset($params['assign'])) {
            $smarty->assign(trim($params['assign']), $result);
            return;
        }
        return $result;
    }

}

?>