<?php
namespace Gurucomkz;

use SilverStripe\Forms\FormRequestHandler;

/**
 * Used to override default Form functionality to display the readonly preview form.
 */
class DummyRequestHandler extends FormRequestHandler
{
    private static $allowed_actions = [
        'httpSubmission',
    ];
    public function httpSubmission($request)
    {
    }
}
