<?php
/**
 * @version    5.0.1
 * @package    TAGZ
 * @author     roosterz.nl <roy@roosterz.nl>
 * @copyright  2016 roosterz.nl
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Version;

HTMLHelper::_('behavior.keepalive');

HTMLHelper::_('behavior.formvalidator');

// JHtml::_('behavior.formvalidation');
// JHtml::_('formbehavior.chosen');

$app = JFactory::getApplication();
$jinput = $app->input;
$formData = $this->form->getData();
$componentType = $jinput->getString('component_type');
$componentId = $jinput->getInt('component_id');
$page = 'layout=edit&id='.(int) $this->item->id . '&component_type=' . $componentType;

TagzHelper::addBackendCSS();
TagzHelper::addBackendJS();
TagzHelper::addWrapperStart($page, 'tagz_edit');

isset($formData['title']) ? $title = $formData['title'] : $title = $jinput->get('title', '', 'STRING');
isset($formData['component_type']) ? $component_type = $formData['component_type'] : $component_type = $jinput->getString('component_type');
isset($formData['component_id']) ? $component_id = $formData['component_id'] : $component_id = $jinput->getInt('component_id');

$version = new Version;
$jver    = explode('.', $version->getShortVersion());

?>

<script type="text/javascript">
    // cbs j3.x
    // Joomla.submitbutton = function(task)
    // {
    //     if (task == 'tag.cancel' || document.formvalidator.isValid(document.id('adminForm')))
    //     {
	// 		Joomla.submitform(task, document.getElementById('adminForm'));
    //     }
    // }
</script>

<div style="display: block;" class="rh_tab active">
	<div class="rh_tag-title"><h2><?php echo JText::_("JACTION_EDIT"); ?> TAG:
			<?php echo $title; ?><a target="_blank" href="https://docs.roosterz.nl/tagz/edit-tags"><i class="rh_icon-doc-text small"></i></a>
			<span class="subtitle"><?php echo ucfirst($component_type); ?> (id: <?php echo $component_id; ?>)</span>
        </h2>
    </div>

    <div class="form-horizontal">
    <?php if ($componentType !== 'menu') : ?>
        <?php echo $this->form->renderFieldset("general") ?>
    <?php else : ?>
        <?php echo $this->form->renderFieldset("general_menu") ?>
    <?php endif; ?>
    </div>

    <div class="row" style="margin-left:0;">
		<div id="fb" class="span6 col-lg-6" style="padding-left: 0;">
			<?php echo $this->form->renderFieldset("facebook") ?>
			<?php echo $this->form->renderFieldset("facebookpreview") ?>
		</div>
		<div id="twitter" class="span6 col-lg-6" style="padding-left: 0;">
			<?php echo $this->form->renderFieldset("twitter") ?>
			<?php echo $this->form->renderFieldset("twitterpreview") ?>
		</div>
	</div>

    <?php echo $this->form->renderFieldset("advanced") ?>

</div>

<?php

TagzHelper::addWrapperEnd($componentType);

// Instantiate global document object
$doc = JFactory::getDocument();

// Language constants needed in the Ajax Call
JText::script('COM_TAGZ_GET_TAG_INFO_SUCCESS');
JText::script('COM_TAGZ_TITLE');
JText::script('COM_TAGZ_DESCRIPTION');
JText::script('COM_TAGZ_IMAGE');
JText::script('COM_TAGZ_GET_TAG_INFO_SAVE');
JText::script('COM_TAGZ_GET_TAG_INFO_PART');
JText::script('COM_TAGZ_GET_TAG_INFO_PART_NOTE');

$js = "
(function ($) {
    $(document).on('click', '#get_tag_info', function (e) {

        e.preventDefault();

        id = $('#jform_component_id').val();
        type = $('#jform_component_type').val();

        var ajaxLoader = $('body').data('loadingIndicator');
        ajaxLoader.show();

        $.ajax({
            url: tagz.baseUrl + '&task=getTagInfo&format=json&component_id=' + id + '&component_type=' + type
          }).always(function(data) {
              ajaxLoader.hide();
          }).done(function(response) {
              var no_info = new Array();
			  if (response['title'] == '') {
                  no_info.push(Joomla.JText._('COM_TAGZ_TITLE'));
              }
              if (response['description'] == '') {
                  no_info.push(Joomla.JText._('COM_TAGZ_DESCRIPTION'));
              }
              if (response['image'] == '') {
                  no_info.push(Joomla.JText._('COM_TAGZ_IMAGE'));
              }
			  
              if (no_info.length > 0) { // some information is not fetched
                  swal({
                      customClass: 'no_input',
                      title: Joomla.JText._('COM_TAGZ_GET_TAG_INFO_PART'),
                      text: Joomla.JText._('COM_TAGZ_GET_TAG_INFO_PART_NOTE') + '<br /><br /><b>' + no_info.toString() + '</b>',
                      type: 'warning',
                      html: true,
                      //   timer: 2000,
                      showConfirmButton: true
                  });
              }
              else {
                  swal({
                      customClass: 'no_input',
                      title: Joomla.JText._('COM_TAGZ_GET_TAG_INFO_SUCCESS'),
                      text: Joomla.JText._('COM_TAGZ_GET_TAG_INFO_SAVE'),
                      type: 'success',
                      //   timer: 2000,
                      showConfirmButton: true
                  });
              }

              title_facebook = response['title'].substring(0, tagz.max_chars_title_facebook);
              description_facebook = response['description'].substring(0, tagz.max_chars_description_facebook);

              title_twitter = response['title'].substring(0, tagz.max_chars_title_twitter);
              description_twitter = response['description'].substring(0, tagz.max_chars_description_twitter);

              image = response['image'];

              // Set the title and description
              $('[id$=\'fb_title\']').val(title_facebook);
              $('[id$=\'fb_description\']').val(description_facebook);
              $('[id$=\'twitter_title\']').val(title_twitter);
              $('[id$=\'twitter_description\']').val(description_twitter);
              $('[id$=\'_image\']').val(image).change();

          })
          .fail(function(data) {
			  console.log(data);
            swal({
                customClass: 'no_input',
                title: 'Oops',
                text: 'Something went wrong. Please get in touch with roy@roosterz.nl.',
                type: 'error'
            });
          });
    });
})(jQuery);";

$doc->addScriptDeclaration($js);

?>
