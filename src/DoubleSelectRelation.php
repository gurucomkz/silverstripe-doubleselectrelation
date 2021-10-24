<?php


namespace Gurucomkz;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Convert;
use SilverStripe\Forms\CompositeField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldButtonRow;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldPageCount;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Forms\MultiSelectField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;

/**
 * @method DataList getSource()
 */
class DoubleSelectRelation extends MultiSelectField
{
    private static $allowed_actions = [
        'preview',
    ];

    private static $url_handlers = [
        '$Action!/$ID' => '$Action'
    ];

    private $titleField = 'Title';
    private $allowPreview = false;

    private static $default_classes = [
        'stacked',
    ];

    public function preview(HTTPRequest $request)
    {
        /** @var DataObject */
        $object = $this->getSource()->byID($request->latestParam('ID'));
        $form = $this->ItemEditForm($object);
        return $form;
    }

    private function ItemEditForm(DataObject $record)
    {
        if (!$record->canView()) {
            return  _t(
                __CLASS__ . '.ViewPermissionsFailure',
                'It seems you don\'t have the necessary permissions to view "{ObjectTitle}"',
                ['ObjectTitle' => $record->singular_name()]
            );
        }

        $fields = $record->hasMethod('getPreviewCMSFields') ? $record->getPreviewCMSFields() : $record->getCMSFields();
        $fields->makeReadonly();
        $this->fixGridFields($fields);

        $form = new Form(
            $this,
            'ItemEditForm',
            $fields,
            FieldList::create()
        );

        $form->loadDataFrom($record);
        $form->makeReadonly();
        $form->setFormAction('nada');
        $form->setRequestHandler(new DummyRequestHandler($form));
        $form->setStrictFormMethodCheck(false);
        $form->disableSecurityToken();


        // Always show with base template (full width, no other panels),
        // regardless of overloaded CMS controller templates.
        // TODO Allow customization, e.g. to display an edit form alongside a search form from the CMS controller
        $form->setTemplate([
            'type' => 'Includes',
            'SilverStripe\\Admin\\LeftAndMain_EditForm',
        ]);
        $form->addExtraClass('cms-content cms-edit-form center fill-height flexbox-area-grow');
        $form->setAttribute('data-pjax-fragment', 'CurrentForm Content');
        if ($form->Fields()->hasTabSet()) {
            $form->Fields()->findOrMakeTab('Root')->setTemplate('SilverStripe\\Forms\\CMSTabSet');
            $form->addExtraClass('cms-tabset');
        }

        return $form->forTemplate();
    }

    private function fixGridFields(FieldList $fields)
    {
        foreach ($fields as $field) {
            if ($field instanceof CompositeField) {
                $this->fixGridFields($field->getChildren());
            }
            if ($field instanceof GridField) {
                $field->getConfig()
                    ->removeComponentsByType(GridFieldPageCount::class)
                    ->removeComponentsByType(GridFieldButtonRow::class)
                    ->removeComponentsByType(GridFieldToolbarHeader::class)
                    ->removeComponentsByType(GridFieldFilterHeader::class);
            }
        }
    }

    public function getTitleField()
    {
        return $this->titleField;
    }

    public function setTitleField($value)
    {
        $this->titleField = $value;
        return $this;
    }

    public function getAllowPreview()
    {
        return $this->allowPreview;
    }

    public function setAllowPreview($value)
    {
        $this->allowPreview = !!$value;
        return $this;
    }

    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

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
        foreach ($this->getSource() as $relatedObject) {
            /** @var DataObject $relatedObject */
            $itemValue = $relatedObject->ID;
            $title = $relatedObject->{$this->titleField};
            
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

    public function getDataClass()
    {
        return $this->getSource()->dataClass();
    }

    public function getDataTitle()
    {
        return singleton($this->getDataClass())->singular_name();
    }
}
