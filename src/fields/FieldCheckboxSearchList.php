<?php
/**
 * Checkbox Search List plugin for Craft CMS 4.x
 *
 * Checkbox list with search functionality
 *
 * @link      https://percipio.london
 * @copyright Copyright (c) 2022 percipio.london
 */

namespace percipiolondon\checkboxsearchlist\fields;

use Craft;
use craft\base\ElementInterface;
use craft\fields\BaseOptionsField;
use craft\fields\Checkboxes;
use craft\fields\data\MultiOptionsFieldData;
use craft\helpers\ArrayHelper;

/**
 * Class CheckboxList
 *
 * @package modules\sitemodule\fields
 */
class FieldCheckboxSearchList extends Checkboxes
{
    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('tick-box', 'Tick Box List');
    }

    /**
     * @inheritdoc
     */
    public static function valueType(): string
    {
        return MultiOptionsFieldData::class;
    }

    /**
     * @inheritdoc
     */
    protected bool $multi = true;

    /**
     * @inheritdoc
     */
    public function useFieldset(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    protected function inputHtml(mixed $value, ?ElementInterface $element = null): string
    {
        /** @var MultiOptionsFieldData $value */
        if (ArrayHelper::contains($value, 'valid', false, true)) {
            Craft::$app->getView()->setInitialDeltaValue($this->handle, null);
        }

        return Craft::$app->getView()->renderTemplate('tick-box/_components/fields/CheckboxSearchList_input', [
            'describedBy' => $this->describedBy,
            'name' => $this->handle,
            'values' => $this->encodeValue($value),
            'options' => $this->translatedOptions(true),
        ]);
    }

    public function serializeValue(mixed $value, ?craft\base\ElementInterface $element = null): mixed
    {
        return parent::serializeValue($value, $element);
    }

    /**
     * @inheritdoc
     */
    protected function optionsSettingLabel(): string
    {
        return Craft::t('tick-box', 'Tick Box Options');
    }
}
//
//    public function getInputHtml($value, ElementInterface $element = null): string
//    {
//        // Register our asset bundle
//        Craft::$app->getView()->registerAssetBundle(CheckboxSearchListFieldAsset::class);
//
//        // Get our id and namespace
//        $id = Craft::$app->getView()->formatInputId($this->handle);
//        $namespacedId = Craft::$app->getView()->namespaceInputId($id);
//
//        // Variables to pass down to our field JavaScript to let it namespace properly
//        $jsonVars = [
//            'id' => $id,
//            'name' => $this->handle,
//            'namespace' => $namespacedId,
//            'prefix' => Craft::$app->getView()->namespaceInputId(''),
//            ];
//        $jsonVars = Json::encode($jsonVars);
//        Craft::$app->getView()->registerJs("$('#{$namespacedId}-field').CheckboxSearchListCheckboxSearchList(" . $jsonVars . ");");
//
//        // Render the input template
//        return Craft::$app->getView()->renderTemplate(
//            'checkbox-search-list/_components/fields/CheckboxSearchList_input',
//            [
//                'name' => $this->handle,
//                'value' => $value,
//                'field' => $this,
//                'id' => $id,
//                'namespacedId' => $namespacedId,
//            ]
//        );
//    }
