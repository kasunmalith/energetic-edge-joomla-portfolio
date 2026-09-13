<?php

/**
 * @version    5.0.1
 * @package    TAGZ
 * @author     roosterz.nl <roy@roosterz.nl>
 * @copyright  2024 roosterz.nl
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


class JFormFieldRH_Preview extends JFormField
{
    public $type = 'Preview';
    private $params = null;
    public $previewContent = array();
    
    protected function getLabel()
    {
        return '';
    }

    protected function getInput()
    {
        $this->params = $this->element->attributes();
        $network = $this->get('network');
        $formData = $this->form->getData();
        $baseUrl = JURI::root();
        $imageTooSmall = false;
        $html = '';

        $title = $formData[$network . '_title'];
        $description = $formData[$network . '_description'];
        $config_params = TagzHelper::getParams();

        if ($formData[$network . '_image'] != "") {
            $imageUrl = $formData[$network . '_image'];
            if (strpos($imageUrl, 'http') === false) { // only add the base url if no http has been added already (e.g. external website)
                $imageUrl = $baseUrl . $formData[$network . '_image'];
            }

            if (!$config_params->get('skip_getimagesize')) {
                if (ini_get('allow_url_fopen')) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $imageUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
                    $image = curl_exec($ch);
                    curl_close($ch);

                    if ($image !== false)
                    {
                    try {
                        list($imageWidth, $imageHeight) = getimagesizefromstring($image);

                        if ($imageWidth && $imageHeight) {
                            if ($network == "fb") {
                                $imageWidthReq = 600;
                                $imageHeightReq = 315;

                                if (intval($imageWidth) < $imageWidthReq || intval($imageHeight) < $imageHeightReq) {
                                    $imageTooSmall = true;
                                }
                            } elseif ($network == "twitter") {
                                $imageWidthReq = 300;
                                $imageHeightReq = 157;

                                if (intval($imageWidth) < $imageWidthReq || intval($imageHeight) < $imageHeightReq) {
                                    $imageTooSmall = true;
                                }
                            }
                        }
                    } catch (Exception $e) {
                        JLog::add('Unable to get image size (backend).', JLog::DEBUG, $e);
                    }
                    }
                }
            }
        } else {
            $imageUrl = $baseUrl . "administrator/components/com_tagz/assets/images/". $network ."_placeholder.png";
        }

        $html .= '
			<div id="tagz_changed_notice_'.$network.'">
				<div class="alert alert-info" style="margin-top: 10px;">
					'. JText::sprintf('TAGZ_TAG_CHANGED') .'
				</div>
			</div>
		';

        if ($imageTooSmall) {
            $html .= '
				<div class="alert alert-warning" style="margin-top: 10px;">
					<strong>'. JText::_('WARNING') .'!</strong> '. JText::sprintf('TAGZ_IMAGE_TOO_SMALL', $imageWidth, $imageHeight, $imageWidthReq, $imageHeightReq) .'
				</div>
			';
        }

        $html .= '
			<div class="tagz_preview_container_'. $network .'">';

        $html .= '
				<div class="tagz_preview_image_'. $network .'">
					<img src="' . $imageUrl . '" style="max-width:100%;">
				</div>
        		<div class="tagz_preview_content_'. $network .'">
            		<div class="tagz_preview_content_title_'. $network .'">'. $title .'</div>
            		<div class="tagz_preview_content_text_'. $network .'">'. $description .'</div>
        		</div>
    		</div>
			';

        return $html;
    }

    private function get($val, $default = '')
    {
        return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
    }
}
