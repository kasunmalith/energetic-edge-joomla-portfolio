<?php
    /**
     * @version    5.0.1
     * @package    Com_Tagz
     * @author     roosterz.nl <roy@roosterz.nl>
     * @copyright  2016 roosterz.nl
     * @license    GNU General Public License version 2 or later; see LICENSE.txt
     */

    // No direct access
    defined('_JEXEC') or die;

    use Joomla\CMS\HTML\HTMLHelper;

    JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
    JHtml::_('bootstrap.tooltip');
    JHtml::_('behavior.multiselect');
    JHtml::_('formbehavior.chosen');

    $joomla_version = substr(JVERSION, 0, 1);

    if ($joomla_version < 4) {
        // joomla 3.x
        JHTML::_('behavior.modal');
    } else {
        JHTML::_('bootstrap.renderModal');
    }

    $view          = JFactory::getApplication()->input->get('view');
    $componentType = JFactory::getApplication()->input->get('component_type');
    $page          = 'view=' . $view . '&component_type=' . $componentType;

    // Import CSS & JS
    TagzHelper::addBackendCSS();
    TagzHelper::addBackendJS();
    TagzHelper::addWrapperStart($page);
?>

<div style="display: block;" class="rh_tab active">
	<div class="rh_tab-title"><h2><?php echo $componentType; ?> TAGZ<a target="_blank" href="https://docs.roosterz.nl/tagz/creating-tags"><i class="rh_icon-doc-text small"></i></a></h2></div>

	<div class="bs-callout subHeader bs-callout-trans">

	<?php if ($componentType !== 'menu') : ?>
		<div class="note hasText"><span><?php echo JText::_('COM_TAGZ_GET_SELECTED_INFO_NOTE'); ?></span></div>
		
		
		<button disabled class="btn btn-no-border btn-success input-356"><span class="icon-download"></span> <?php echo JText::_('COM_TAGZ_GET_SELECTED_INFO'); ?></button><a href="https://www.roosterz.nl/joomla-extensions/tagz" target="_blank"><div class="btn label-warning" style="margin-left: 5px;">PRO ONLY <span class="icon-out-2"></span></div></a>
		
	<?php else: ?>
		<div class="note hasText"><span><?php echo JText::_('COM_TAGZ_MENU_ITEMS_MANUALLY'); ?></span></div>
	<?php endif;?>
	</div>

	<div class="bs-callout bs-callout-trans subHeader">
		<?php
            echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
        ?>

		<table class="adminlist table">
		   <thead>
			   <tr>
				   <th class="center" width="2%"><?php echo JHtml::_('grid.checkall'); ?></th>
				   <?php
                       if ($componentType == 'zoo') {
                           echo '<th width="8%">';
                           echo 'Zoo type';
                           echo '</th>';
                       }
                       if ($componentType == 'menu') {
                           echo '<th width="8%">';
                           echo 'Menu type';
                           echo '</th>';
                       }
                   ?>
				   <th><?php echo ucfirst($componentType) . ' ' . JText::_('COM_TAGZ_TITLE'); ?></th>
				   <th width="8%" class="rh_hide_non_desktop"><?php echo JText::_('COM_TAGZ_EDIT_ITEM'); ?></th>
				   <th class="center" width="8%"><?php echo JText::_('COM_TAGZ_LOCKED'); ?></th>
				   <th class="center" width="15%"><?php echo JText::_('COM_TAGZ_PREVIEW'); ?> Open Graph</th>
				   <th class="center" width="15%"><?php echo JText::_('COM_TAGZ_PREVIEW'); ?> Twitter</th>
			   </tr>
		   </thead>
		   <tbody>
			   <?php if (count($this->items)) {
                       $editItemLink    = TagzHelper::getEditItemLink($componentType);
                       $itemsParams     = TagzHelper::getItemsParams($componentType);    // Get the contents of all items
                       $itemsLockStates = TagzHelper::getItemsLockState($componentType); // Get the lock state of all items

                       foreach ($this->items as $i => $item):

                       $title      = urlencode($item->title);
                       $title_text = strlen($item->title) > 50 ? substr($item->title, 0, 50) . "..." : $item->title;

                       $preview_fb      = false;
                       $preview_twitter = false;
                       $locked          = false;

                       if (array_key_exists($item->component_id, $itemsParams)) {
                           if (json_decode($itemsParams[$item->component_id])->fb_title) {
                               $preview_fb = true;
                           }

                           if (json_decode($itemsParams[$item->component_id])->twitter_title) {
                               $preview_twitter = true;
                           }
                       }
                       if (array_key_exists($item->component_id, $itemsLockStates)) {
                           if ($itemsLockStates[$item->component_id] == 1) {
                               $locked = true;
                           }
                       } ?>

						   	<tr class="row<?php echo $i % 2; ?>">
								<td class="center">
									<?php echo JHtml::_('grid.id', $i, $item->component_id); ?>
									<div data-id="<?php if (isset($item->id)) {
                           echo $item->id;
                       } else {
                           echo '-1';
                       } ?>" data-locked="<?php if ($locked) {
                           echo 'true';
                       } else {
                           echo 'false';
                       } ?>"></div>
								</td>
								<?php
                                    if ($componentType == 'zoo') {
                                        echo '<td>';
                                        echo ucfirst($item->zoo_type);
                                        echo '</td>';
                                    }
                       if ($componentType == 'menu') {
                           echo '<td>';
                           echo $item->menutype;
                           echo '</td>';
                       } ?>
								<td>
									<a href="<?php echo JRoute::_('index.php?option=com_tagz&view=tag&layout=edit&id=' . $item->id . '&component_type=' . $componentType . '&component_id=' . $item->component_id . '&title=' . $title); ?>" title="<?php echo JText::_('COM_TAGZ_EDIT'); ?>"><?php echo $title_text; ?>
								   	</a>
								   	<span class="subtitle">(id:								   	                            <?php echo $item->component_id; ?>)</span>
							   	</td>
							   	<td  class="rh_hide_non_desktop" style="text-align: center"><span class=""><a href="<?php echo JRoute::_($editItemLink . $item->component_id); ?>" target="_blank" title="<?php echo JText::_('COM_TAGZ_EDIT_ITEM'); ?>"><span class="icon-out-2"></span></a></span></td>
							   	<td style="text-align: center">
									<?php if ($locked) {?>
										<a href="<?php echo JRoute::_('index.php?option=com_tagz&task=tagz.unlock&cid=' . $item->component_id . '&component_type=' . $componentType); ?>" title="<?php echo JText::_('COM_TAGZ_UNLOCK_TAG'); ?>"><i class="icon-checkedout" style="color: green"></i></a>
									<?php } ?>
								</td>
								<td style="text-align: center">

									<?php if ($preview_fb) {
                           $params = json_decode($itemsParams[$item->component_id]);

                           if ($joomla_version < 4) {
                               echo TagzHelper::getPreviewDiv($item->component_id, $params, "fb"); ?>

											<a href="#preview_fb_<?php echo $item->component_id; ?>" style="cursor:pointer" class="modal rh_outline_button facebook" rel="{size: {x: 527, y: 390}}"><i class="rh_icon-facebook small"></i><span class="rh_hide_non_desktop"><?php echo JText::_('COM_TAGZ_PREVIEW'); ?></span></a>

										<?php
                           } else {
                               echo HTMLHelper::_(
                                   'bootstrap.renderModal',
                                   'FacebookPreview' . $item->component_id. 'Modal',
                                   array(
                                                                'title'       => "Preview Open Graph",
                                                                'closeButton' => true,
                                                                'height'      => '390px',
                                                                'width'       => '527px',
                                                            ),
                                   TagzHelper::getPreviewDiv($item->component_id, $params, "fb")
                               ); ?>
											<button type="button" style="background-color: #fff;" class="rh_outline_button facebook" data-bs-toggle="modal"
											data-bs-target="#FacebookPreview<?= $item->component_id ?>Modal">
											<i class="rh_icon-facebook small"></i><span class="rh_hide_non_desktop">
											<?php echo JText::_('COM_TAGZ_PREVIEW'); ?></span></button>
										<?php
                           } ?>
									<?php
                       } ?>
								</td>
								<td style="text-align: center">
									<?php if ($preview_twitter) {
                           $params = json_decode($itemsParams[$item->component_id]);

                           if ($joomla_version < 4) {
                               echo TagzHelper::getPreviewDiv($item->component_id, $params, "twitter"); ?>
												<a href="#preview_twitter_<?php echo $item->component_id; ?>" style="cursor:pointer" class="modal rh_outline_button twitter" rel="{size: {x: 527, y: 390}}"><i class="rh_icon-twitter small"></i><span class="rh_hide_non_desktop"><?php echo JText::_('COM_TAGZ_PREVIEW'); ?></span></a>

										   <?php
                           } else {
                               echo HTMLHelper::_(
                                   'bootstrap.renderModal',
                                   'TwitterPreview' . $item->component_id. 'Modal',
                                   array(
                                                            'title'       => "Preview Twitter Card",
                                                            'closeButton' => true,
                                                            'height'      => '390px',
                                                            'width'       => '527px'
                                                        ),
                                   TagzHelper::getPreviewDiv($item->component_id, $params, "twitter")
                               ); ?>
												<button type="button" style="background-color: #fff;" class="rh_outline_button twitter" data-bs-toggle="modal"
												data-bs-target="#TwitterPreview<?= $item->component_id ?>Modal">
												<i class="rh_icon-twitter small"></i><span class="rh_hide_non_desktop">
												<?php echo JText::_('COM_TAGZ_PREVIEW'); ?></span></button>
									<?php
                           } ?>
									<?php
                       } ?>
								</td>
						   	</tr>
					   <?php endforeach; ?>
<?php
                   } else {?>
				   <tr>
					   <td align="center" colspan="7">
						   <div align="center"><?php echo JText::_('COM_TAGZ_NO_CONTENT') ?></div>
					   </td>
				   </tr>
			   <?php }?>
		   </tbody>
		   <tfoot>
			   <tr><td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td></tr>
		   </tfoot>
	   </table>
	</div>
</div>

<?php

    TagzHelper::addWrapperEnd($componentType);

    // Instantiate global document object
    $doc = JFactory::getDocument();

    // Language constants needed in the Ajax Call
    JText::script('COM_TAGZ_GET_TAG_INFO_STORE_SUCCESS');
    JText::script('COM_TAGZ_SELECT_AT_LEAST_ONE');

    $js = "
(function ($) {
    $(document).on('click', '#get_tagz_info', function (e) {

        e.preventDefault();
		component_type = tagz.component_type;

		var checked_count = $(\"input[name='cid[]']:checked\").length;
		var ready_count = 0;

		if (checked_count == 0) {
			swal({
				customClass: 'no_input',
				title: Joomla.JText._('COM_TAGZ_SELECT_AT_LEAST_ONE'),
				type: 'warning',
				html: true,
				//   timer: 2000,
				showConfirmButton: true
			});
		}
		else
		{
			var ajaxLoader = $('body').data('loadingIndicator');
	        ajaxLoader.show();

			$.each($(\"input[name='cid[]']:checked\"), function()
			{
	            var component_id = $(this).val();
				var locked = $(this).next().data('locked');
				var id = $(this).next().data('id');

				if (!locked)
				{ // Only non-locked items are updated
					$.ajax({
			            url: tagz.baseUrl + '&task=getTagInfo&format=json&component_id=' + component_id + '&component_type=' + component_type
			          }).done(function(response) {

						  var title_facebook = response['title'].substring(0, tagz.max_chars_title_facebook);
						  var description_facebook = response['description'].substring(0, tagz.max_chars_description_facebook);
						  var title_twitter = response['title'].substring(0, tagz.max_chars_title_twitter);
						  var description_twitter = response['description'].substring(0, tagz.max_chars_description_twitter);
						  var image = response['image'];

						  var data = { component_id: component_id, component_type: component_type, id: id, fb_title: title_facebook, fb_description: description_facebook, fb_image: image, twitter_title: title_twitter, twitter_description: description_twitter, twitter_image: image };

						  $.ajax({
							  type: 'POST',
							  url: tagz.baseUrl + '&task=storeTagInfo',
							  data: data,
						  }).fail(function (jqXHR,status,err) {
							  console.log( 'error storing tag info: ' + err );
						  });
			          })
			          .fail(function(data) {

						  ajaxLoader.hide();

			          	swal({
			                customClass: 'no_input',
			                title: 'Oops',
			                text: 'Something went wrong. Please get in touch with roy@roosterz.nl.',
			                type: 'error'
			            });
			          });
				}

				ready_count++;

				$(document).ajaxStop(function() { // Fire when all ajax calls are finished
					if (ready_count == checked_count)
					{
					  ajaxLoader.hide();
					  swal({
						  customClass: 'no_input',
						  title: Joomla.JText._('COM_TAGZ_GET_TAG_INFO_STORE_SUCCESS'),
						  type: 'success',
						  //   timer: 2000,
						  showConfirmButton: true
					  },function() {
						  location.reload();
					  });
					}
				});
		    });
		}
    });

})(jQuery);";

    $doc->addScriptDeclaration($js);

?>
