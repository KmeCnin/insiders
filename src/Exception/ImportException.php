<?php

namespace App\Exception;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class ImportException extends \Exception
{
    public function __construct(FormInterface $form, array $data) {
        $errors = $this->collectErrors($form);
        $message = 'Errors while importing data:';
        $message .= "\n- ".implode("\n- ", $errors);
        $message.= "\nData: ".json_encode(
            $data,
            JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE
        );

        parent::__construct($message);
    }

    private function collectErrors(FormInterface $form): array
    {
        $errors = array_map(function (FormError $error) {
            return $error->getOrigin()->getName().': '.$error->getMessage();
        }, iterator_to_array($form->getErrors(true)));

        if (count($form->getExtraData())) {
            $errors[] = "Extra fields: ".implode(', ', array_keys($form->getExtraData()));
        }

        return $errors;
    }
}
