<?php

/**
 * @version    5.0.1
 * @package    TAGZ
 * @author     roosterz.nl <roy@roosterz.nl>
 * @copyright  2020 roosterz.nl
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

JHtml::_('bootstrap.popover');
JHtml::_('formbehavior.chosen');

$page = '&view=config';

TagzHelper::addBackendCSS();
TagzHelper::addBackendJS();
TagzHelper::addWrapperStart($page);

?>

<div style="display: block;" class="rh_tab active">
	<div class="rh_tag-title"><h2><?php echo JText::_("COM_TAGZ_CONFIGURATION"); ?><a target="_blank" href="https://docs.roosterz.nl/tagz/configuration"><i class="rh_icon-doc-text small"></i></a>
        </h2>
    </div>

	<div class="row" style="margin-left:0;">
		<div class="span6 col-lg-6" style="padding-left: 0;">
			<?php echo $this->form->renderFieldset("general") ?>
		</div>
		<div class="span6 col-lg-6" style="padding-left: 0;">
			<?php echo $this->form->renderFieldset("updates") ?>
		</div>
	</div>

	<div class="row" style="margin-left:0;">
		<div class="span6 col-lg-6" style="padding-left: 0;">
			<?php echo $this->form->renderFieldset("og") ?>
		</div>
		<div class="span6 col-lg-6" style="padding-left: 0;">
			<?php echo $this->form->renderFieldset("twitter") ?>
		</div>
	</div>

	<div class="row" style="margin-left:0;">
		<div class="span6 col-lg-6" style="padding-left: 0;">
			<?php echo $this->form->renderFieldset("content") ?>
		</div>

	<?php
    $enabledExtensions = TagzHelper::getEnabledExtensions();
    if (in_array("com_zoo", $enabledExtensions)) {?>
			<div class="span6 col-lg-6" style="padding-left: 0;">
				<?php echo $this->form->renderFieldset("zoo") ?>
			</div>
		</div>
	<?php } ?>

	<?php
		if (in_array("com_hikashop", $enabledExtensions)) {?>
			<div class="span6 col-lg-6" style="padding-left: 0;">
				<?php echo $this->form->renderFieldset("hikashop") ?>
			</div>
		</div>
	<?php } ?>

</div>

<?php

TagzHelper::addWrapperEnd("config");
