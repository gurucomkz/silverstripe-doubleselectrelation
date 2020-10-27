<?php


namespace Gurucomkz;

use SilverStripe\Core\Convert;
use SilverStripe\Forms\FormField;
use SilverStripe\Forms\MultiSelectField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;

class DoubleSelectRelation extends MultiSelectField {

    /**
     * @todo Explain different source data that can be used with this field,
     * e.g. SQLMap, ArrayList or an array.
     *
     * @param array $properties
     * @return DBHTMLText
     */
    public function Field($properties = [])
    {
        $properties = array_merge($properties, [
            'Options' => $this->getOptions()
        ]);

        return FormField::Field($properties);
    }

    /**
     * Gets the list of options to render in this formfield
     *
     * @return ArrayList
     */
    public function getOptions()
    {
        $selectedValues = $this->getValueArray();
        $defaultItems = $this->getDefaultItems();
        $disabledItems = $this->getDisabledItems();

        // Generate list of options to display
        $odd = false;
        $formID = $this->ID();
        $options = new ArrayList();
        foreach ($this->getSource() as $itemValue => $title) {
            $itemID = Convert::raw2htmlid("{$formID}_{$itemValue}");
            $odd = !$odd;
            $extraClass = $odd ? 'odd' : 'even';
            $extraClass .= ' val' . preg_replace('/[^a-zA-Z0-9\-\_]/', '_', $itemValue);

            $itemChecked = in_array($itemValue, $selectedValues) || in_array($itemValue, $defaultItems);
            $itemDisabled = $this->isDisabled() || in_array($itemValue, $disabledItems);

            $options->push(new ArrayData([
                'ID' => $itemID,
                'Class' => $extraClass,
                'Role' => 'option',
                'Name' => "{$this->name}[{$itemValue}]",
                'Value' => $itemValue,
                'Title' => $title,
                'isChecked' => $itemChecked,
                'isDisabled' => $itemDisabled,
            ]));
        }
        $this->extend('updateGetOptions', $options);
        return $options;
    }

    public function Type()
    {
        return 'optionset doubleselect';
    }

    public function getAttributes()
    {
        $attributes = array_merge(
            parent::getAttributes(),
            ['role' => 'listbox']
        );

        // Remove invalid attributes from wrapper.
        unset($attributes['name']);
        unset($attributes['required']);
        unset($attributes['aria-required']);
        return $attributes;
    }
}
