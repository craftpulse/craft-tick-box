<?php
/**
 * Checkbox Search List plugin for Craft CMS 4.x
 *
 * Checkbox list with search functionality
 *
 * @link      https://percipio.london
 * @copyright Copyright (c) 2022 percipio.london
 */

namespace percipiolondon\checkboxsearchlist;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;

use craft\web\twig\variables\CraftVariable;
use nystudio107\pluginvite\services\VitePluginService;
use percipiolondon\checkboxsearchlist\assetbundles\checkboxsearchlistfield\CheckboxSearchListFieldAsset;
use percipiolondon\checkboxsearchlist\fields\FieldCheckboxSearchList;
use percipiolondon\checkboxsearchlist\variables\CheckboxSearchListVariable;
use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://docs.craftcms.com/v3/extend/
 *
 * @author    percipio.london
 * @package   CheckboxSearchList
 * @since     1.0.0
 *
 * @property VitePluginService  $vite
 *
 */
class CheckboxSearchList extends Plugin
{
    // Static Properties
    // =========================================================================
    public static ?CheckboxSearchListVariable $checkboxSearchListVariable = null;

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * CheckboxSearchList::$plugin
     *
     * @var CheckboxSearchList
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public string $schemaVersion = '1.0.0';

    /**
     * Set to `true` if the plugin should have a settings view in the control panel.
     *
     * @var bool
     */
    public bool $hasCpSettings = false;

    /**
     * Set to `true` if the plugin should have its own section (main nav item) in the control panel.
     *
     * @var bool
     */
    public bool $hasCpSection = false;

    // Public Methods
    // =========================================================================

    public function __construct($id, $parent = null, array $config = [])
    {
        $config['components'] = [
            'checkbox' => __CLASS__,
            'vite' => [
                'class' => VitePluginService::class,
                'assetClass' => CheckboxSearchListFieldAsset::class,
                'useDevServer' => true,
                'devServerPublic' => 'http://localhost:3752',
                'serverPublic' => 'http://localhost:3700',
                'errorEntry' => '/src/js/main.ts',
                'devServerInternal' => 'http://craft-checkbox-search-list-buildchain:3752',
                'checkDevServer' => true,
            ],
        ];

        parent::__construct($id, $parent, $config);
    }

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * CheckboxSearchList::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Register our fields
        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = FieldCheckboxSearchList::class;
            }
        );

        // Register variable
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function(Event $event): void {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('checkbox', [
                    'class' => CheckboxSearchListVariable::class,
                    'viteService' => $this->vite,
                ]);
            }
        );

        // Do something after we're installed
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    // We were just installed
                }
            }
        );

        Craft::info(
            Craft::t(
                'checkbox-search-list',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
