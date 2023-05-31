<?php
namespace common\components;

use yii\bootstrap5\Nav;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\Html;
use Yii;

class NewNav extends Nav {

    public $dropdownClass = NewDropdown::class;
    
        /**
     * Renders the given items as a dropdown.
     * This method is called to create sub-menus.
     * @param array $items the given items. Please refer to [[Dropdown::items]] for the array structure.
     * @param array $parentItem the parent item information. Please refer to [[items]] for the structure of this array.
     * @return string the rendering result.
     * @throws Throwable
     */
    protected function renderDropdown(array $items, array $parentItem): string
    {
        /** @var Widget $dropdownClass */
        $dropdownClass = $this->dropdownClass;

        return $dropdownClass::widget([
            'options' => ArrayHelper::getValue($parentItem, 'dropdownOptions', []),
            'items' => $items,
            'encodeLabels' => $this->encodeLabels,
            'clientOptions' => [],
            'view' => $this->getView(),
        ]);
    }

    /**
     * Renders a widget's item.
     * @param string|array $item the item to render.
     * @return string the rendering result.
     * @throws InvalidConfigException
     */
	public function renderItem($item) : string{
		if (is_string($item)) {
			return $item;
		}
		$linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
		$url = ArrayHelper::getValue($item, 'url','#');
		$label = ArrayHelper::getValue($item, 'label','#');
                $notabs = ArrayHelper::getValue($item, 'notabs','#');
                $nolink = ArrayHelper::getValue($item, 'nolink','#');
                if ( (isset($nolink) && $nolink != '' && $nolink != '#')) {
                    $url = '#';
                }
                if ( $notabs != 'true') {
                    if ( isset($url) && $url != '' && $url != '#') {
                        $linkOptions['onclick'] = "Tabs.addTab(this, '" . $label . "','Pagina " . $label . "','/index.php?r=" . $url[0] . "'); return false;";
                    }
                }
		ArrayHelper::setValue($item,'linkOptions',$linkOptions);
		return $this->renderItemStd($item);
	}

    /**
     * Renders a widget's item.
     * @param string|array $item the item to render.
     * @return string the rendering result.
     * @throws InvalidConfigException
     * @throws Throwable
     */
    public function renderItemStd($item): string
    {
        if (is_string($item)) {
            return $item;
        }
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }
        $encodeLabel = $item['encode'] ?? $this->encodeLabels;
        $label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
        $options = ArrayHelper::getValue($item, 'options', []);
        $items = ArrayHelper::getValue($item, 'items');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
        $disabled = ArrayHelper::getValue($item, 'disabled', false);
        $active = $this->isItemActive($item);

        if (empty($items)) {
            $items = '';
            Html::addCssClass($options, ['widget' => 'nav-item']);
            Html::addCssClass($linkOptions, ['widget' => 'nav-link']);
        } else {
            // Paride, sono nel padre di un navbar che ha un sottomenu: annullo il tag url
            $url = '#';
            unset($linkOptions['onclick']);
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

        return Html::tag('li', Html::a($label, $url, $linkOptions) . $items, $options);
    }
        
}