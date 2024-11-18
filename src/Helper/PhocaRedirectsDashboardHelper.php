<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

namespace Joomla\Module\PhocaRedirectsDashboard\Administrator\Helper;

use Joomla\CMS\Factory;
use Joomla\Database\Exception\ExecutionFailureException;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

class PhocaRedirectsDashboardHelper
{

    public static function getData()
    {
        $db = Factory::getDbo();

        $data = [];

        try {
            // Check if the Redirect plugin is enabled
            $query = $db->getQuery(true)
                ->select($db->quoteName('enabled'))
                ->select($db->quoteName('params'))
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('element') . ' = ' . $db->quote('redirect'))
                ->where($db->quoteName('folder') . ' = ' . $db->quote('system'));
            $db->setQuery($query);
            $row = $db->loadObject();

            if (isset($row->enabled) && $row->enabled == 1) {
                $data['enabled'] = $row->enabled;
                if (isset($row->params)) {
                    $registry = new Registry;
                    $registry->loadString($row->params);
                    $data['collect_urls'] = $registry->get('collect_urls', 0);
                }
            }
            return $data;
        } catch (ExecutionFailureException $e) {
            return [];
        }
    }

    public static function getDataCount()
    {
        $db = Factory::getDbo();
        try {
            $query = $db->getQuery(true)
                ->select('COUNT(*)')
                ->from($db->quoteName('#__redirect_links'));
            $db->setQuery($query);
            return $db->loadResult();

        } catch (ExecutionFailureException $e) {
            return 0;
        }
    }

    public static function stopCollectData()
    {
        $db = Factory::getDbo();

        try {
            $query = $db->getQuery(true)
                ->select($db->quoteName('extension_id'))
                ->select($db->quoteName('params'))
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('element') . ' = ' . $db->quote('redirect'))
                ->where($db->quoteName('folder') . ' = ' . $db->quote('system'));
            $db->setQuery($query);
            $row = $db->loadObject();

            if (isset($row->extension_id) && (int)$row->extension_id > 0 && isset($row->params)) {
                $id = (int)$row->extension_id;
                $registry = new Registry;
                $registry->loadString($row->params);
                $collect_url = $registry->get('collect_urls', 0);
                if ($collect_url == 1) {
                    $registry->set('collect_urls', 0);
                    $paramsString = $registry->toString();
                    $query        = $db->getQuery(true)
                        ->update($db->quoteName('#__extensions'))
                        ->set($db->quoteName('params') . ' = :params')
                        //->where($db->quoteName('type') . ' = ' . $db->quote('library'))
                        //->where($db->quoteName('element') . ' = :element')
                        ->where($db->quoteName('extension_id') . ' = :extension_id')
                        ->bind(':params', $paramsString)
                        //->bind(':element', $element);
                        ->bind(':extension_id', $id);
                    $db->setQuery($query);
                    $result = $db->execute();
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 2;
            }
        } catch (ExecutionFailureException $e) {
            return 3;
        }
    }
}
