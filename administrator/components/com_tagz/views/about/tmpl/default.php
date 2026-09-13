<?php
/**
 * @version    5.0.1
 * @package    TAGZ
 * @author     roosterz.nl <roy@roosterz.nl>
 * @copyright  2024 roosterz.nl
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS & JS
TagzHelper::addBackendCSS();
TagzHelper::addBackendJS();
TagzHelper::addWrapperStart();

?>

<div class="tile tile-white">
    <div class="tile_image_tagz"></div>
    <div class="inner">
        <div class="tile_title"><h2><?php echo JTEXT::_("COM_TAGZ_TITLE_ABOUT") ?></h2>
            <span class="tile_title_version">(<?php echo JText::_("COM_TAGZ_VERSION") ?> 5.0.1 - FREE)</span>
        </div>
        <div class="tile_desc">
            <p><?php echo JText::_("COM_TAGZ_ABOUT_1") ?><br /><br /><?php echo JText::_("COM_TAGZ_ABOUT_2") ?>:<br /><br /><?php echo JText::_("COM_TAGZ_ABOUT_3") ?><br /><?php echo JText::_("COM_TAGZ_ABOUT_4") ?><br /><?php echo JText::_("COM_TAGZ_ABOUT_5") ?><br /><?php echo JText::_("COM_TAGZ_ABOUT_6") ?><br /><?php echo JText::_("COM_TAGZ_ABOUT_7") ?><br /><?php echo JText::_("COM_TAGZ_ABOUT_8") ?><br /><br /><?php echo JText::_("COM_TAGZ_ABOUT_9") ?><br /><br /><?php echo JText::_("COM_TAGZ_ABOUT_11") ?><br /><br /><?php echo JText::_("COM_TAGZ_ABOUT_12") ?><br /><br />
            </p>
        </div>
    </div>
</div>

<?php

TagzHelper::addWrapperEnd();

?>
