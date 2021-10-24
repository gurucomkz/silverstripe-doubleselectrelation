# Silverstripe two-column relation picker component 

Old-fashioned component with left column with available options, right column with selected ones and some buttons to move elements between those.
Has filter fields to look up for the elements in the columns.

![](example.png)

## Installation 

```bash
composer require gurucomkz/doubleselectrelation
composer vendor-expose
```

## Usage

```php
$offersList = Offers::get();
$offersSelector = DoubleSelectRelation::create('Offers', null, $offersList);
$fields->addFieldToTab('Root.Main', $offersSelector);
```
### Select custom Title

Declare a field (i.e. `LongTitle`) or a getter (i.e. `getLongTitle()`) in your model and do like this:
```php
DoubleSelectRelation::create('Offers', null, $offersList)
    ->setTitleField('LongTitle');
```

### Enable preview 

You may want to let users check out the options before working with them. It can be done like this:
```php
DoubleSelectRelation::create('Offers', null, $offersList)
    ->setAllowPreview(true);
```
It will create a button in every line, which by clicking will display a readonly CMS form in a modal box.

Additionally, you can declare method `getPreviewCMSFields()` in your related model to make a totally custom preview form.
## TODO

* Checks for changes and reporting to the form to trigger "Save" button activation
* Some API maybe for programmatic control over options?
