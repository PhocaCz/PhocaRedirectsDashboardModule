<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\Module\PhocaRedirectsDashboard\Administrator\Helper\PhocaRedirectsDashboardHelper;

// Only super user can view this data
if (!$app->getIdentity()->authorise('core.admin')) {
    return;
}
$wa = $app->getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('mod_phoca_redirects_dashboard', 'media/mod_phoca_redirects_dashboard/css/main.css');

$data = [];
$count = (int)PhocaRedirectsDashboardHelper::getDataCount();

$data['force_stop_collect']  = 0;
$url_count_stop = (int)$params->get('url_count_stop', 0);
if ($url_count_stop > 0 && $count >= $url_count_stop) {
    $data['force_stop_collect'] = PhocaRedirectsDashboardHelper::stopCollectData();
}

$dataPlugin = PhocaRedirectsDashboardHelper::getData();
$data['enabled'] = isset($dataPlugin['enabled']) ? $dataPlugin['enabled']: 0;
$data['url_count'] = isset($count) ? $count: 0;
$data['collect_urls'] = isset($dataPlugin['collect_urls']) ? $dataPlugin['collect_urls']: 0;

$data['class'] = '';
if ($data['enabled']) {
    $data['class'] .= ' enabled';
}
if ($data['collect_urls']) {
    $data['class'] .= ' collect';
}

$url_count_warning = (int)$params->get('url_count_warning', 0);
if ($url_count_warning > 0 && $data['url_count'] >= $url_count_warning) {
    $data['class'] .= " alarm";
}


if (!empty($data)) {
    require ModuleHelper::getLayoutPath('mod_phoca_redirects_dashboard', $params->get('layout', 'default'));
} else {
    echo LayoutHelper::render('joomla.content.emptystate_module', [
            'textPrefix' => 'MOD_PHOCA_REDIRECT_DASHBOARD',
            'icon'       => 'icon-lock',
        ]);
}
