<?php

/**
 * @version    5.0.1
 * @package    TAGZ
 * @author     roosterz.nl <roy@roosterz.nl>
 * @copyright  2020 roosterz.nl
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Tagz helper.
 *
 * @since  1.6
 */
class TagzHelper
{
    /**
     *  Plugin Params
     *
     *  @var  JRegistry
     */
    public static $params;

    public static function checkAMPZNotInstalled()
    {
        if (JComponentHelper::isEnabled('com_ampz', true)) {
            return false;
        } else {
            return true;
        }
    }

    public static function getTagInfo($component_id, $component_type)
    {
        $structure = TagzHelper::getContentTypeStructure($component_type);
        $config_params = TagzHelper::getParams();
        $media_params = JComponentHelper::getParams('com_media');
        $images_path = $media_params['image_path'];

        $db = JFactory::getDBO();

        $results['title'] = "";
        $results['description'] = "";
        $results['image'] = "";

        $select_array = array();
        $select_custom_array = array();

        if ($component_type == "content") {
            $title = $config_params->get('content_custom_title', 'title');
            $description = $config_params->get('content_custom_description', 'introtext');
            $image = $config_params->get('content_custom_image', 'image_intro');

            // when an needs to be extracted from the content, make sure that the description is fetched
            if ($config_params->get('get_image_from_content')) {
                $select_array[] = $db->quoteName('introtext', 'original_description');
                $select_array[] = $db->quoteName('fulltext', 'original_description_full');
            }

            // First the non custom fields:
            if (!is_numeric($title)) {
                $select_array[] = $db->quoteName($title, 'title');
            } elseif (is_numeric($title)) {
                $select_custom_array['title'] = $title;
            }
            if (!is_numeric($description)) {
                $select_array[] = $db->quoteName($description, 'description');
            } elseif (is_numeric($description)) {
                $select_custom_array['description'] = $description;
            }
            if (!is_numeric($image)) {
                $select_array[] = $db->quoteName('images', 'image');
            } elseif (is_numeric($image)) {
                $select_custom_array['image'] = $image;
            }

            if (!empty($select_array)) { // At least one field needs to come from the content table
                $query = $db->getQuery(true);
                $query
                    ->select($select_array)
                    ->from($db->quoteName('#__content'))
                    ->where($db->quoteName($structure['id']) . ' = ' . $db->quote($component_id));

                $db->setQuery($query);
                $results = $db->loadAssoc();

                if (isset($results['image'])) {
                    $images = json_decode($results['image']);
                    $image = $images->$image;
                }

                if (isset($image)) {
                    $results['image'] = $image;
                } else {
                    $results['image'] = '';
                }
            }

            // Now the custom fields:
            if (!empty($select_custom_array)) { // At least one field needs to come from the content table
                foreach ($select_custom_array as $key => $value) {
                    $query = $db->getQuery(true);
                    $query
                        ->select($db->quoteName('value', $key))
                        ->from($db->quoteName('#__fields_values'))
                        ->where($db->quoteName('field_id') . ' = ' . $db->quote($value))
                        ->where($db->quoteName('item_id') . ' = ' . $db->quote($component_id));
                    $db->setQuery($query);
                    if (is_null($db->loadResult())) {
                        $results[$key] = '';
                    } else {
                        $results[$key] = $db->loadResult();
                    }
                }
            }
        } else {
            $select_array = array($db->quoteName($structure['title'], 'title'), $db->quoteName($structure['description'], 'description'));

            if ($component_type != "k2" && $component_type != "virtuemart" && $component_type != "hikashop" && $component_type != "zoo" && $component_type != "jticketing" && $component_type != "eshop" && $component_type != "opencart" && $component_type != "jcart" && $component_type != "igallery" && $component_type != "djclassifieds") { // No direct access to images in database
                array_push($select_array, $db->quoteName($structure['image'], 'image'));
            }
            if ($component_type == "zoo") {
                array_push($select_array, $db->quoteName('application_id', 'application_id'));
                array_push($select_array, $db->quoteName('type', 'type'));
            }
            if ($component_type == "igallery") {
                array_push($select_array, $db->quoteName('folder', 'folder'));
            }
            if ($component_type != "opencart" && $component_type != "jcart") {
                $structure['table'] = '#__' . $structure['table'];
            }

            if ($component_type != "j2store") { // J2Store uses a combination of three tables so no use for the select_array
                $query = $db->getQuery(true);
                $query
                    ->select($select_array)
                    ->from($db->quoteName($structure['table']))
                    ->where($db->quoteName($structure['id']) . ' = ' . $db->quote($component_id));

                $db->setQuery($query);
                $results = $db->loadAssoc();
            }

            if ($component_type == "zoo") {
                $zoo_elements = json_decode($results['description'], true);
                $zoo_application_type = $results['type'];

                $zooCustomTypesEnabled = $config_params->get("enable_zoo_custom_types");
                if ($zooCustomTypesEnabled) {
                    for ($i = 1; $i <= 10 ; $i++) {
                        $var = "custom_" . $i;
                        $zooCustomType = $config_params->get($var);

                        if ($zooCustomType == '') { // Stop since the next custom type isn't filled out
                            break;
                        }

                        if ($zoo_application_type == $zooCustomType) {
                            $zoo_application_type = "custom_" . $i;
                            break;
                        }
                    }
                }

                $zoo_application_type_description = 'zoo_' . $zoo_application_type . '_description_string';
                $zoo_application_type_image = 'zoo_' . $zoo_application_type . '_image_string';

                $zooDescriptionString = $config_params->get($zoo_application_type_description);
                $zooImageString = $config_params->get($zoo_application_type_image);

                $results['description'] = $zoo_elements[$zooDescriptionString][0]["value"];
                if (is_null($results['description'])) {
                    $results['description'] = '';
                }
                $results['image'] = $zoo_elements[$zooImageString]["file"];
                if (!isset($results['image'])) {
                    $results['image'] =  $zoo_elements[$zooImageString][0]["file"];
                }
            } elseif ($component_type == "jomclassifieds") {
                // jomclassifieds can store multiple images in the same Table field
                $images = $results['image'];
                $results['image'] = substr($images, 0, strpos($images, ','));
            } elseif ($component_type == "rseventspro") {
                if (isset($results['image'])) {
                    $image = $results['image'];
                    $results['image'] = 'components/com_rseventspro/assets/images/events/' . $image;
                }
            } elseif ($component_type == "easyblog") {
                // Easyblog uses different ways to store images in several folders
                $full_image = $results['image'];

                if (strpos($full_image, 'post:', 0) !== false) {
                    $image = substr($full_image, 5);
                    $results['image'] = $images_path . '/easyblog_articles/' . $image;
                } elseif (strpos($full_image, 'user:', 0) !== false) {
                    $image = substr($full_image, 5);
                    $results['image'] = $images_path . '/easyblog_images/' . $image;
                } elseif (strpos($full_image, 'shared/', 0) !== false) {
                    $image = substr($full_image, 7);
                    $results['image'] = $images_path . '/easyblog_shared/' . $image;
                }
            } elseif ($component_type == "k2") {
                if (JFile::exists(JPATH_SITE.'/media/k2/items/cache/'.md5("Image".$component_id).'_XL.jpg')) {
                    $results['image'] = 'media/k2/items/cache/'.md5("Image".$component_id).'_XL.jpg';
                } else {
                    $results['image'] = '';
                }
            } elseif ($component_type == "rsblog") {
                $image = $results['image'];
                if (strpos($image, '[images]blog/', 0) === 0) {
                    $image = substr($image, 13);
                    $results['image'] = $images_path . '/blog/' . $image;
                } else {
                    $results['image'] = 'components/com_rsblog/assets/images/blog/' . $image;
                }
            } elseif ($component_type == "virtuemart") {
                $query = $db->getQuery(true);
                $query
                    ->select($db->quoteName('file_url', 'image'))
                    ->from($db->quoteName('#__virtuemart_medias', 'a'))
                    ->join('INNER', $db->quoteName('#__virtuemart_product_medias', 'b') . ' ON (' . $db->quoteName('a.virtuemart_media_id') . ' = ' . $db->quoteName('b.virtuemart_media_id') . ')')
                    ->where($db->quoteName($structure['id']) . ' = ' . $db->quote($component_id))
                    ->where($db->quoteName('a.published') . ' = 1')
                    ->order($db->quoteName('b.ordering') . ' ASC')
                    ->setLimit('1');

                $db->setQuery($query);
                $result = $db->loadResult();
                if (isset($result)) {
                    $results['image'] = $result;
                }
            } elseif ($component_type == "igallery") {
                $query = $db->getQuery(true);
                $query
                    ->select($db->quoteName('filename', 'image'))
                    ->from($db->quoteName('#__igallery_img', 'a'))
                    ->where($db->quoteName('gallery_id') . ' = ' . $db->quote($component_id))
                    ->setLimit('1');

                $db->setQuery($query);
                $result = $db->loadResult();

                $image_path = '/images/igallery/original/' . $results['folder'] . '/' . $result;
                if (JFile::exists(JPATH_SITE.$image_path)) {
                    $results['image'] = $image_path;
                } else {
                    $results['image'] = '';
                }
            } elseif ($component_type == "jshopping") {
                $full_image = $results['image'];

                if (JFile::exists(JPATH_SITE.'/components/com_jshopping/files/img_products/full_'. $full_image)) {
                    $results['image'] = 'components/com_jshopping/files/img_products/full_'. $full_image;
                } else {
                    $results['image'] = '';
                }
            } elseif ($component_type == "j2store") {
                $query = $db->getQuery(true);
                $query
                    ->select($db->quoteName('main_image', 'image'))
                    ->from($db->quoteName('#__j2store_productimages', 'a'))
                    ->where($db->quoteName('product_id') . ' = ' . $db->quote($component_id))
                    ->setLimit('1');

                $db->setQuery($query); // Get the image first
                $result = $db->loadResult();
                if (isset($result)) {
                    $results['image'] = $result;
                }

                $query = $db->getQuery(true);
                $query
                    ->select($db->quoteName('a.title', 'title'))
                    ->select($db->quoteName('a.introtext', 'description'))
                    ->from($db->quoteName('#__content', 'a'))
                    ->join('INNER', $db->quoteName('#__j2store_products', 'b') . ' ON (' . $db->quoteName('b.product_source_id') . ' = ' . $db->quoteName('a.id') . ')')
                    ->where($db->quoteName('b.j2store_product_id') . ' = ' . $db->quote($component_id));

                $db->setQuery($query); // Get the title and description from the original Article
                $title_description = $db->loadAssoc();
                if (isset($title_description['title'])) {
                    $results['title'] = $title_description['title'];
                }
                if (isset($title_description['description'])) {
                    $results['description'] = $title_description['description'];
                }
            } elseif ($component_type == "djclassifieds") {
                $query = $db->getQuery(true);
                $query
                    ->select('*')
                    ->from($db->quoteName('#__djcf_images', 'a'))
                    ->where($db->quoteName('a.item_id') . ' = ' . $db->quote($component_id))
                    // ->where($db->quoteName('a.type') . ' = "item"')
                    ->order($db->quoteName('a.ordering') . ' ASC')
                    ->setLimit('1');

                $db->setQuery($query);
                $result = $db->loadAssoc();
                if (isset($result)) {
                    $results['image'] = substr($result['path'], 1) . $result['name'] . '.' . $result['ext'];
                }
            } elseif ($component_type == "opencart" || $component_type == "jcart") {
                $query = $db->getQuery(true);
                $query
                    ->select($db->quoteName('image', 'image'))
                    ->from($db->quoteName('oc_product', 'a'))
                    ->join('INNER', $db->quoteName('oc_product_description', 'b') . ' ON (' . $db->quoteName('a.product_id') . ' = ' . $db->quoteName('b.product_id') . ')')
                    ->where($db->quoteName('a.product_id') . ' = ' . $db->quote($component_id))
                    ->setLimit('1');

                $db->setQuery($query);
                $result = $db->loadResult();
                if (isset($result)) {
                    $results['image'] = 'components/com_'. $component_type . '/image/' . $result;
                }
            } elseif ($component_type == "jticketing") {
                $query = $db->getQuery(true);
                $query
                    ->select($db->quoteName('path'))
                    ->select($db->quoteName('source'))
                    ->from($db->quoteName('#__tj_media_files', 'b'))
                    ->join('INNER', $db->quoteName('#__tj_media_files_xref', 'c') . ' ON (' . $db->quoteName('b.id') . ' = ' . $db->quoteName('c.media_id') . ')')
                    ->join('INNER', $db->quoteName('#__jticketing_events', 'a') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('c.client_id') . ')')
                    ->where('a.id = ' . $db->quote($component_id))
                    ->where($db->quoteName('c.is_gallery') . ' = 0');

                $db->setQuery($query);
                $result = $db->loadAssoc();
                if (isset($result['source']) && isset($result['path'])) {
                    $results['image'] = $result['path'] . '/' . $result['source'];
                }
            } elseif ($component_type == "hikashop") {
                $query = $db->getQuery(true);
                $query
                    ->select($db->quoteName('file_path', 'image'))
                    ->from($db->quoteName('#__hikashop_file', 'a'))
                    ->where($db->quoteName('a.file_ref_id') . ' = ' . $db->quote($component_id))
                    ->where($db->quoteName('a.file_type') . ' = "product"')
                    ->order($db->quoteName('a.file_id') . ' ASC')
                    ->setLimit('1');

                $db->setQuery($query);
                $result = $db->loadResult();
                if (isset($result)) {
                    $hikashop_images_folder = $config_params->get('hikashop_image_folder');
                    $results['image'] = $hikashop_images_folder . '/' . $result;
                }
            } elseif ($component_type == "phocacart") {
                $query = $db->getQuery(true);
                $query
                    ->select($db->quoteName('image', 'image'))
                    ->from($db->quoteName('#__phocacart_products', 'a'))
                    ->where($db->quoteName('a.id') . ' = ' . $db->quote($component_id))
                    ->setLimit('1');

                $db->setQuery($query);
                $result = $db->loadResult();
                if (isset($result)) {
                    $results['image'] = $images_path . '/phocacartproducts/' . $result;
                }
            } elseif ($component_type == "eshop") {
                $query = $db->getQuery(true);
                $query
                    ->select($db->quoteName('product_image', 'image'))
                    ->from($db->quoteName('#__eshop_products', 'a'))
                    ->where($db->quoteName('a.id') . ' = ' . $db->quote($component_id))
                    ->setLimit('1');

                $db->setQuery($query);
                $result = $db->loadResult();
                if (isset($result)) {
                    $results['image'] = 'media/com_eshop/products/' . $result;
                }
            }

            // when an needs to be extracted from the content, make sure that the description is fetched
            if ($config_params->get('get_image_from_content')) {
                if (isset($results['description'])) {
                    $results['original_description'] = $results['description'];
                }
            }
        }

        // Search the content for an image if this is not defined and needs to be done
        if (isset($results['image'])) {
            if (($results['image'] == '') && ($config_params->get('get_image_from_content'))) {
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $results['original_description'], $match);
                if (isset($match[1]) && $match[1] != '') {
                    $results['image'] = $match[1];
                }
                else { // if not found in the intro text, found in the full text?
                    preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $results['original_description_full'], $match);
                    if (isset($match[1]) && $match[1] != '') {
                        $results['image'] = $match[1];
                    }
                }
            }
        }

        $removePluginShortcodes = $config_params->get('remove_plugin_shortcodes');
        if ($removePluginShortcodes && isset($results['description'])) {
            $results['description'] = preg_replace('/{(.*?)}/', '', $results['description']);
        }

        if (isset($results['title'])) {
            $results['title'] = trim(strip_tags($results['title'])); 
        }

        if (isset($results['description'])) {
            $results['description'] = trim(strip_tags($results['description'])); // Also do a html entity decode?
            // $results['description'] = strip_tags($results['description']); // Strip HTML tags from the description
        }

        if (isset($results['image'])) {
          if (strpos($results['image'], "#joomlaImage") !== false) { // cut off the #JoomlaImage part in J4
            $results['image'] = substr($results['image'], 0, strpos($results['image'], "#joomlaImage"));
          }
        }

        return $results;
    }

    public static function getParamsfromResults($results)
    {
        if (!isset($results["title"])) {
            return false;
        }

        $config_params = TagzHelper::getParams();
        $maxCharsTitleFacebook = $config_params->get('fb_max_title_length');
        $maxCharsDescriptionFacebook = $config_params->get('fb_max_description_length');
        $maxCharsTitleTwitter = $config_params->get('twitter_max_title_length');
        $maxCharsDescriptionTwitter = $config_params->get('twitter_max_description_length');

        if (function_exists('mb_substr')) { // mb_substr can be disabled
            $fb_title = mb_substr($results["title"], 0, $maxCharsTitleFacebook);
            $fb_description = mb_substr($results["description"], 0, $maxCharsDescriptionFacebook);
            $twitter_title = mb_substr($results["title"], 0, $maxCharsTitleTwitter);
            $twitter_description = mb_substr($results["description"], 0, $maxCharsDescriptionTwitter);
        } else { // then use the default substr function...if there are issues enable mb functions
            $fb_title = substr($results["title"], 0, $maxCharsTitleFacebook);
            $fb_description = substr($results["description"], 0, $maxCharsDescriptionFacebook);
            $twitter_title = substr($results["title"], 0, $maxCharsTitleTwitter);
            $twitter_description = substr($results["description"], 0, $maxCharsDescriptionTwitter);
        }

        $fb_image = $results["image"];
        $twitter_image = $results["image"];

        $params = array("fb_title" => $fb_title, "fb_description" => $fb_description, "fb_image" => $fb_image, "twitter_title" => $twitter_title, "twitter_description" => $twitter_description, "twitter_image" => $twitter_image);

        return $params;
    }

    public static function storeTagInfo($component_id, $component_type, $params, $id)
    {
        $registry = new JRegistry();
        $registry->loadArray($params);
        $params = (string)$registry;

        // Only insert if data is passed
        if ($component_type != '' && $component_id != 0) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            if ($id === -1) { // New Entry so Insert
                $id = '';
                $values = array($db->quote('0'), $db->quote($component_type), $db->quote($component_id), $db->quote('0'), $db->quote($params));

                // Prepare the insert query.
                $query
                     ->insert($db->quoteName('#__tagz'))
                     ->values(implode(',', $values));
            } else { // Existing entry so Update

                $query
                    ->update($db->quoteName('#__tagz'))
                    ->set($db->quoteName('params') . ' = ' . $db->quote($params))
                    ->where($db->quoteName('id') . ' = ' . $db->quote($id));
            }

            try {
                $db->setQuery($query);
                $result = $db->execute();
            } catch (Exception $e) {
                $app->enqueueMessage(JText::_($e->getMessage()), 'error');
            }
        }
    }

    public static function getContentTypeStructure($type)
    {
        $structure = array();
        $params = TagzHelper::getParams();

        $id = 'id';
        $title = 'title';
        $image = 'images';
        $description = 'introtext';
        $defaultSort = 'a.modified';

        switch ($type) {
            case 'content':
                $table = 'content';
                break;
            case 'k2':
                $table = 'k2_items';
                break;
            case 'jticketing':
                $table = 'jticketing_events';
                $description = 'long_description';
                break;
            case 'rsblog':
                $table = 'rsblog_posts';
                $image = 'image';
                $defaultSort = 'a.modified_date';
                break;
            case 'zoo':
                $table = 'zoo_item';
                $title = 'name';
                $description = 'elements';
                break;
            case 'icagenda':
                $table = 'icagenda_events';
                $description = 'shortdesc';
                $image = 'image';
                break;
            case 'rseventspro':
                $table = 'rseventspro_events';
                $title = 'name';
                $description = 'description';
                $image = 'icon';
                $defaultSort = 'a.created';
                break;
            case 'mijovideos':
                $table = 'mijovideos_videos';
                $image = 'thumb';
                break;
            case 'virtuemart':
                $activeLanguageTag = str_replace('-', '_', strtolower(JFactory::getLanguage()->getTag()));
                $table = 'virtuemart_products_' . $activeLanguageTag;
                $id = 'virtuemart_product_id';
                $title = 'product_name';
                $description = 'product_desc';
                $defaultSort = 'a.virtuemart_product_id';
                break;
            case 'jshopping':
                $activeLanguageTag = JFactory::getLanguage()->getTag();
                $table = 'jshopping_products';
                $image = 'image';
                $id = 'product_id';
                $title = 'name_' . $activeLanguageTag;
                $description = 'description_'  . $activeLanguageTag;
                $defaultSort = 'a.product_id';
                break;
            case 'igallery':
                $table = 'igallery';
                $title = 'name';
                $description = 'metadesc';
                $defaultSort = 'a.id';
                break;
            case 'j2store':
                $table = 'content';
                $id = 'b.j2store_product_id';
                break;
            case 'djclassifieds':
                $table = 'djcf_items';
                $title = 'name';
                $description = 'description';
                $defaultSort = 'a.id';
                break;
            case 'opencart':
            case 'jcart':
                $table = 'oc_product_description';
                $id = 'product_id';
                $title = 'name';
                $description = 'description';
                $defaultSort = 'a.product_id';
                break;
            case 'phocacart':
                $table = 'phocacart_products';
                $description = 'description_long';
                $image = 'image';
                break;
            case 'eshop':
                $table = 'eshop_productdetails';
                $id = 'product_id';
                $title = 'product_name';
                $description = 'product_desc';
                $defaultSort = 'a.product_id';
                break;
            case 'eventbooking':
                $table = 'eb_events';
                $description = 'description';
                $image = 'image';
                $defaultSort = 'a.event_date';
                break;
            case 'jomclassifieds':
                $table = 'jomcl_adverts';
                $description = 'description';
                $defaultSort = 'a.updateddate';
                break;
            case 'easyblog':
                $table = 'easyblog_post';
                $description = 'intro';
                $image = 'image';
                break;
            case 'hikashop':
                $table = 'hikashop_product';
                $id = 'product_id';
                $description = 'product_description';
                $title = 'product_name';
                $defaultSort = 'product_modified';
                break;
            case 'menu':
                $table = 'menu';
                $title = 'alias';
                $description = '';
                $image = '';
                $defaultSort = 'a.menutype';
                break;
            default:
                $table = 'none';
                break;
        }

        $structure['id'] = $id;
        $structure['table'] = $table;
        $structure['title'] = $title;
        $structure['description'] = $description;
        $structure['image'] = $image;
        $structure['defaultSort'] = $defaultSort;

        return $structure;
    }

    public static function getPreviewDiv($component_id, $params, $network)
    {
        $baseUrl = JURI::root();
        $titleVar = $network . "_title";
        $title = $params->$titleVar;
        $descriptionVar = $network . "_description";
        $description = $params->$descriptionVar;
        $imageVar = $network . "_image";
        $imageUrl = $params->$imageVar;
        if (strpos($imageUrl, 'http') === false) { // only add the base url if no http has been added already (e.g. external website)
            $imageUrl = $baseUrl . $params->$imageVar;
        }

        $joomla_version = substr(JVERSION, 0, 1);
        $display_style =  ($joomla_version < 4) ? ' style="display:none" ': '';

        $html = '<div '. $display_style .'>
			 <div id="preview_'. $network . "_" . $component_id .'">
			 	<div class="tagz_preview_container_'. $network .'">';

        $html .= '
	 				<div class="tagz_preview_image_'. $network .'">
	 					<img src="' . $imageUrl . '">
	 				</div>
	         		<div class="tagz_preview_content_'. $network .'">
	             		<div class="tagz_preview_content_title_'. $network .'">'. $title .'</div>
	             		<div class="tagz_preview_content_text_'. $network .'">'. $description .'</div>
	         		</div>
	     		</div>

	 		 </div>
		 </div>';

        return $html;
    }

    public static function getItemsLockState($type)
    {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true)
            ->select($db->quoteName(array('component_id', 'lock_tag')))
            ->from($db->quoteName('#__tagz'))
            ->where($db->quoteName('component_type') . ' = ' . $db->quote($type));
        $db->setQuery($query);

        $results = $db->loadAssocList('component_id', 'lock_tag');

        return $results;
    }

    public static function getItemsParams($type)
    {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true)
            ->select($db->quoteName(array('component_id', 'params')))
            ->from($db->quoteName('#__tagz'))
            ->where($db->quoteName('component_type') . ' = ' . $db->quote($type));
        $db->setQuery($query);

        $results = $db->loadAssocList('component_id', 'params');

        return $results;
    }

    public static function getEditItemLink($type)
    {
        switch ($type) {
            case 'content':
                $link = 'index.php?option=com_content&view=article&task=article.edit&layout=edit&id=';
                break;
            case 'k2':
                $link = 'index.php?option=com_k2&view=item&cid=';
                break;
            case 'zoo':
                $link = 'index.php?option=com_zoo&controller=item&changeapp=1&task=edit&cid%5B%5D=';
                break;
            case 'icagenda':
                $link = 'index.php?option=com_icagenda&task=event.edit&id=';
                break;
            case 'rsblog':
                $link = 'index.php?option=com_rsblog&view=post&layout=edit&id=';
                break;
            case 'mijovideos':
                $link = 'index.php?option=com_mijovideos&view=videos&task=edit&cid[]=';
                break;
            case 'virtuemart':
                $link = 'index.php?option=com_virtuemart&view=product&task=edit&virtuemart_product_id=';
                break;
            case 'igallery':
                $link = 'index.php?option=com_igallery&view=icategory&id=';
                break;
            case 'jshopping':
                $link = 'index.php?option=com_jshopping&controller=products&task=edit&product_id=';
                break;
            case 'j2store':
                $link = 'index.php?option=com_content&view=article&layout=edit&id=';
                break;
            case 'djclassifieds':
                $link = 'index.php?option=com_djclassifieds&view=product&task=item.edit&id=';
                break;
            case 'opencart':
                $link = 'index.php?option=com_opencart&route=catalog/product/edit&product_id=';
                break;
            case 'jcart':
                $link = 'index.php?option=com_jcart&route=catalog/product/edit&product_id=';
                break;
            case 'phocacart':
                $link = 'index.php?option=com_phocacart&task=phocacartitem.edit&id=';
                break;
            case 'eshop':
                $link = 'index.php?option=com_eshop&task=product.edit&cid[]=';
                break;
            case 'eventbooking':
                $link = 'index.php?option=com_eventbooking&view=event&id=';
                break;
            case 'rseventspro':
                $link = 'index.php?option=com_rseventspro&task=event.edit&id=';
                break;
            case 'easyblog':
                $link = 'index.php?option=com_easyblog&view=composer&tmpl=component&uid=';
                break;
            case 'hikashop':
                $link = 'index.php?option=com_hikashop&ctrl=product&task=edit&cid[]=';
                break;
            case 'jomclassifieds':
                $link = 'index.php?option=com_jomclassifieds&view=adverts&task=edit&'. JSession::getFormToken() .'=1&cid[]=';
                break;
            case 'menu':
                $link = 'index.php?option=com_menus&task=item.edit&id=';
                break;
        }

        return $link;
    }

    public static function addBackendCSS()
    {
        $document = JFactory::getDocument();

        $document->addStyleSheet(JUri::root() . 'administrator/components/com_tagz/assets/css/rh_admin.css');
        $document->addStyleSheet(JUri::root() . 'administrator/components/com_tagz/assets/css/sweetalert.css');
        $document->addStyleSheet(JURI::root() . 'administrator/components/com_tagz/assets/vendor/labelauty/jquery-labelauty.css');
        $document->addStyleSheet(JURI::root() . '/media/jui/css/icomoon.css');
    }

    public static function addBackendFastEditCSS()
    {
        $document = JFactory::getDocument();

        $document->addStyleSheet(JUri::root() . 'administrator/components/com_tagz/assets/css/tagz.css');
    }

    /**
     *  Get Plugin Parameters
     *
     *  @return  JRegistry
     */
    public static function getParams()
    {
        if (self::$params) {
            return self::$params;
        }

        JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tagz/tables');

        $table = JTable::getInstance('Config', 'TagzTable');
        $table->load('config');

        return (self::$params = new Registry($table->params));
    }

    /**
     *  Get Enabled Extensions
     *
     *  @return  array
     */
    public static function getEnabledExtensions()
    {
        $supportedExtensions = array('com_k2', 'com_virtuemart', 'com_easyblog', 'com_jomclassifieds', 'com_hikashop', 'com_rseventspro', 'com_zoo', 'com_jticketing', 'com_icagenda', 'com_eventbooking', 'com_eshop', 'com_rsblog', 'com_mijovideos', 'com_opencart', 'com_jcart', 'com_djclassifieds', 'com_j2store', 'com_phocacart', 'com_jshopping', 'com_igallery');
        $enabledExtensions = array();

        $db = JFactory::getDBO();

        foreach ($supportedExtensions as &$extension) {
            $query = $db->getQuery(true)
                ->select('e.enabled')
                ->from('#__extensions AS e')
                ->where('e.element = "'. $extension .'"')
                ->where('e.type = "component"');
            $db->setQuery($query);
            $extensionEnabled = $db->loadResult();

            if ($extensionEnabled == 1) { // Extension is installed and enabled
                array_push($enabledExtensions, $extension);
            }
        }

        return $enabledExtensions;
    }

    public static function addBackendJS()
    {
        $document = JFactory::getDocument();

        $document->addScript(JURI::root() . 'administrator/components/com_tagz/assets/js/admin.js');
        $document->addScript(JURI::root() . 'administrator/components/com_tagz/assets/js/sweetalert.min.js');
        $document->addScript(JURI::root() . 'administrator/components/com_tagz/assets/js/jquery.loading-indicator.js');
        $document->addScript(JURI::root() . 'administrator/components/com_tagz/assets/vendor/labelauty/jquery-labelauty.js');
        $document->addScript(JURI::root() . 'administrator/components/com_tagz/assets/vendor/nestable/jquery.nestable.js');

        $document->addScriptDeclaration('
        	window.twttr = (function (d, s, id) {
        	  var t, js, fjs = d.getElementsByTagName(s)[0];
        	  if (d.getElementById(id)) return;
        	  js = d.createElement(s); js.id = id;
        	  js.src= "https://platform.twitter.com/widgets.js";
        	  fjs.parentNode.insertBefore(js, fjs);
        	  return window.twttr || (t = { _e: [], ready: function (f) { t._e.push(f) } });
        	}(document, "script", "twitter-wjs"));
        ');
    }

    public static function addWrapperStart($page = '', $class='')
    {
        $formUrl = 'index.php?option=com_tagz';

        if ($page != '') {
            $formUrl = 'index.php?option=com_tagz&' . $page;
        }

        $enabledExtensions = TagzHelper::getEnabledExtensions();

        // Set the wrapper height based on the number of enabled extensions
        $min_height_wrapper = 650 + (count($enabledExtensions) * 50);

        $joomla_version = substr(JVERSION, 0, 1);

        if ($joomla_version == 4 || $joomla_version == 5) {
            TagzHelper::fixFieldTooltips();
        }

        $html = '
        <form action="'. JRoute::_($formUrl) .'" method="post" name="adminForm" id="adminForm" class="'. $class .'">

        <div id="rh_wrapper_outer" class="joomla' . $joomla_version . '">
        	<div id="rh_wrapper" style="min-height:' . $min_height_wrapper . 'px">
        	    <header class="rh_header">
        	        <nav class="rh_navbar rh_minimal_navbar">
        	            <div class="rh_navbar-header bg-darker">
        	                <a class="rh_navbar-icon rh_text-brand">
        	                    <span class="rh_icon_text"></span>
        	                </a>
        	            </div>
        	            <a href="https://www.roosterz.nl" target="_blank" id="rh_logo_full" class="rh_logo fadeInTopBig animated_slow"></a>
        	        </nav>
        	    </header>

        		<div id="rh_wrapper_sides" class="rh_tabs">
        			<aside class="rh_left-side">
        				<section class="rh_sidebar">
        					<ul class="rh_sidebar-menu rh_sidebar-menu-minimal rh_tab-links">
        						<li id="type_content" class=""><a href="index.php?option=com_tagz&view=tagz&component_type=content"><i class="rh_icon-joomla"></i><span>'. JText::_('COM_TAGZ_ARTICLES') .'</span></a></li>';

        $shoppingIcons = array('virtuemart', 'hikashop', 'eshop', 'opencart', 'jcart', 'djclassifieds', 'j2store', 'phocacart', 'jshopping');
        $eventIcons = array('rseventspro', 'jticketing', 'icagenda', 'eventbooking');
        $videoIcons = array('mijovideos', 'igallery');
        $blogIcons = array('rsblog');

        foreach ($enabledExtensions as &$extension) {
            $extensionName = substr($extension, 4); // Name is after com_
            $extensionIcon = $extensionName;
            if (in_array($extensionName, $shoppingIcons)) {
                $extensionIcon = 'shopping-basket';
            }
            if (in_array($extensionName, $eventIcons)) {
                $extensionIcon = 'calendar';
            }
            if (in_array($extensionName, $blogIcons)) {
                $extensionIcon = 'doc-text';
            }
            if (in_array($extensionName, $videoIcons)) {
                $extensionIcon = 'video';
            }

            
            $link = 'https://www.roosterz.nl/joomla-extensions/tagz';
            $target = "_blank";
            
            
            $html .= '			<li id="type_'. $extensionName .'" class=""><a href='.$link.' target='. $target .'><i class="rh_icon-'. $extensionIcon .'"></i><span>'. $extensionName .'</span>';
            
            $html .= '<div class="label label-warning" style="margin: 3px 0 0 3px; float:right; font-size: 8px;">PRO</div>';
            
            $html .= '</a></li>';
        }

        $html .= '
        						<li id="type_menu" class=""><a href="index.php?option=com_tagz&view=tagz&component_type=menu"><i class="rh_icon-menu"></i><span>'. JText::_('COM_TAGZ_MENU_ITEMS') .'</span></a></li>
        						<li id="type_config" class=""><a href="index.php?option=com_tagz&view=config"><i class="rh_icon-configure"></i><span>config</span></a></li>
        						<li><a target="_blank" href="https://docs.roosterz.nl/tagz"><i class="rh_icon-doc"></i><span>manual</span></a></li>
        						<li><a target="_blank" href="https://www.roosterz.nl/support/forum"><i class="rh_icon-forum"></i><span>forum</span></a></li>
        						<li><a target="_blank" href="https://extensions.joomla.org/extensions/extension/site-management/seo-a-metadata/tagz-open-graph"><i class="rh_icon-love" style="color:#FF69B4!important;"></i><span>review</span></a></li>
        						<li id="type_about" class=""><a href="index.php?option=com_tagz&view=about"><i class="rh_icon-about"></i><span>'. JText::_("COM_TAGZ_ABOUT") .'</span></a></li>';

        if (TagzHelper::checkAMPZNotInstalled()) {
            $html .= '<a target="_blank" rel="noopener" href="https://www.roosterz.nl/joomla-extensions/ampz" id="rh_ampz_ad"></a>';
        }
        $html .= '
        					</ul>
        				</section>
        			</aside>
        			<aside class="rh_right-side">
        				<div class="rh_tab-content fadeInLeft animated">
                            <div style="display: block; margin-top: 20px;" class="rh_tab active">
            ';

        echo $html;
    }

    public static function addWrapperEnd($type = "")
    {
        $active = "type_about";
        if ($type != "") {
            $active = "type_" . $type;
        }

        echo '
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
        <div>
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            '. JHtml::_('form.token') .'
         </div>
        </form>';

        $params = TagzHelper::getParams();

        $maxCharsTitleFacebook = $params->get('fb_max_title_length');
        $maxCharsDescriptionFacebook = $params->get('fb_max_description_length');
        $maxCharsTitleTwitter = $params->get('twitter_max_title_length');
        $maxCharsDescriptionTwitter = $params->get('twitter_max_description_length');
        $componentType = $type;

        $js = "
		var tagz = {};
		tagz.max_chars_title_facebook = $maxCharsTitleFacebook;
		tagz.max_chars_description_facebook = $maxCharsDescriptionFacebook;
		tagz.max_chars_title_twitter = $maxCharsTitleTwitter;
		tagz.max_chars_description_twitter = $maxCharsDescriptionTwitter;
		tagz.baseUrl = location.href.split('&view')[0];
		tagz.component_type = '$componentType';

		jQuery(document).ready(function($) {
			$('#$active').addClass(\"active\");
		});

		";

        JFactory::getDocument()->addScriptDeclaration($js);
    }

    /**
     * Display field help text as tooltip in Joomla 4
     *
     * @return void
     */
    public static function fixFieldTooltips()
    {
        // Run once
        static $run;

        if ($run) {
            return;
        }

        $run = true;

        \JHtml::_('bootstrap.popover');

        $doc = \JFactory::getDocument();

        $doc->addStyleDeclaration('
 			.form-text, .form-control-feedback {
 				display:none;
 			}
 			.tooltip .arrow:before {
 				border-top-color:#444;
 				border-bottom-color:#444;
 			}
 			.tooltip-inner {
 				text-align: left;
 				background-color: #444;
 				padding: 7px 9px;
 				max-width:300px;
 			}

            label.tTooltip::before, label.hasPopover::before {
                background-color: #ffffff;
                border: 1px solid #c1c1c1;
                border-radius: 100%;
                color: #929292;
                content: "i";
                display: inline-block;
                float: left;
                font-family: georgia;
                font-size: 10px;
                font-weight: bold;
                height: 13px;
                left: 0;
                line-height: 12px;
                position: relative;
                margin-right: 5px;
                text-align: center;
                top: 2px;
                -webkit-transition: all 200ms ease 0s;
                transition: all 200ms ease 0s;
                width: 13px;
            }
 		');

        $doc->addScriptDeclaration('
 			document.addEventListener("DOMContentLoaded", function() {
 				initPopover();

 				document.addEventListener("joomla:updated", initPopover);

 				function initPopover(event) {
 					var target = event && event.target ? event.target : document;
 					var fields = target.querySelectorAll(".control-group");

 					fields.forEach(function(field) {
 						var desc = field.querySelector(".form-text");

 						if (desc) {
 							var label = field.querySelector("label");

 							if (label) {
 								label.classList.add("tTooltip");
 								label.setAttribute("title", desc.innerHTML);
 							}
 						}
 					});

 					jQuery(target).find(".tTooltip").tooltip({
 						placement: "top",
 						html: true,
 						delay: {
 							show: 200
 						}
 					});
 				}
 			});
 		');
    }

    public static function getTagzPluginUrl()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__extensions'))
            ->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
            ->where($db->quoteName('folder') . ' = ' . $db->quote('system'))
            ->where($db->quoteName('element') . ' = ' . $db->quote('tagz'));
        $db->setQuery($query);
        $plugin = $db->loadObject();

        $tagz_plugin_url = 'index.php?option=com_plugins&task=plugin.edit&extension_id=' . $plugin->extension_id;

        return $tagz_plugin_url;
    }
}
