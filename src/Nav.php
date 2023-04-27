<?php

namespace portalium\bootstrap5;

use yii\helpers\ArrayHelper;
use portalium\bootstrap5\Html;
use portalium\bootstrap5\Widget;
use yii\base\InvalidConfigException;

class Nav extends \yii\bootstrap5\Nav
{
    public function renderItem($item): string
    {
        if (is_string($item)) {
            return $item;
        }
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }
        $encodeLabel = $item['encode'] ?? $this->encodeLabels;
        $label = Html::decode($item['label']);
        $options = ArrayHelper::getValue($item, 'options', []);
        $items = ArrayHelper::getValue($item, 'items');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
        $disabled = ArrayHelper::getValue($item, 'disabled', false);
        $active = $this->isItemActive($item);

        if (empty($items)) {
            $items = '';
            Html::addCssClass($options, ['widget' => 'nav-item  me-3 me-lg-0']);
            Html::addCssClass($linkOptions, ['widget' => 'nav-link']);
        } else {
            $linkOptions['data']['bs-toggle'] = 'dropdown';
            $linkOptions['role'] = 'button';
            $linkOptions['aria']['expanded'] = 'false';
            Html::addCssClass($options, ['widget' => 'dropdown nav-item']);
            Html::addCssClass($linkOptions, ['widget' => 'dropdown-toggle nav-link']);
            if (is_array($items)) {
                $items = $this->isChildActive($items, $active);
                $items = $this->renderDropdown($items, $item);
            }
        }

        if ($disabled) {
            ArrayHelper::setValue($linkOptions, 'tabindex', '-1');
            ArrayHelper::setValue($linkOptions, 'aria.disabled', 'true');
            Html::addCssClass($linkOptions, ['disable' => 'disabled']);
        } elseif ($this->activateItems && $active) {
            Html::addCssClass($linkOptions, ['activate' => 'active']);
        }
        if (isset($item['displayType']))
            $options['data-bs-type'] = $item['displayType'];

        if (!isset($item['icon']))
            return Html::tag('li', Html::a($label, $url, $linkOptions) . $items, $options);
        else
            return Html::tag('li', $this->generateLabel($label, $url, $linkOptions, $item['icon']) . $items, $options);
    }

    private function generateLabel($label, $url, $linkOptions, $icon){
        //$aLabel = Html::tag('span', $label, ['class' => 'align-items-center d-flex']);
        $label =  Html::a($icon.$label, $url, $linkOptions);


        return $label;
    }
}
