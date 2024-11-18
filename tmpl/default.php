<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;


?>
<div class="phocaRedirectsDashboard<?php echo $data['class'] ?>" id="<?php echo str_replace(' ', '', $module->title) . $module->id; ?>">
    <!--<caption class="visually-hidden"><?php echo $module->title; ?></caption>-->

    <nav class="px-3 pb-3 ph-redirect-box"><?php

if ($data['enabled']) {
    echo '<div class="ph-redirect-info-box"><span class="ph-light ph-orange"></span>'.Text::_('MOD_PHOCA_REDIRECTS_DASHBOARD_REDIRECT_PLUGIN_ENABLED').'</div>';
} else {
    echo '<div class="ph-redirect-info-box"><span class="ph-light ph-green"></span>'.Text::_('MOD_PHOCA_REDIRECTS_DASHBOARD_REDIRECT_PLUGIN_DISABLED').'</div>';
}
if ($data['collect_urls']) {
    echo '<div class="ph-redirect-info-box"><span class="ph-light ph-red"></span>'.Text::_('MOD_PHOCA_REDIRECTS_DASHBOARD_REDIRECT_PLUGIN_COLLECTS_URLS').'</div>';
} else {
    echo '<div class="ph-redirect-info-box"><span class="ph-light ph-green"></span>'.Text::_('MOD_PHOCA_REDIRECTS_DASHBOARD_REDIRECT_PLUGIN_NOT_COLLECTS_URLS').'</div>';
}

if ($data['force_stop_collect'] == 1) {
    echo '<div class="ph-redirect-info-box"><span class="ph-light ph-green"></span>'.Text::_('MOD_PHOCA_REDIRECTS_DASHBOARD_REDIRECT_PLUGIN_PARAMETER_COLLECT_URLS_DISABLED').'</div>';
}

if ($data['url_count'] > 0) {
    echo '<br>';
    echo '<div class="ph-redirect-info-box"><span class="ph-light ph-neutral"></span>'.Text::_('MOD_PHOCA_REDIRECTS_DASHBOARD_NUMBER_SAVED_REDIRECTS').': <b>'. number_format($data['url_count'], 0, '.', ' ') . '</b></div>';
}
?>
    </nav>
</div>
