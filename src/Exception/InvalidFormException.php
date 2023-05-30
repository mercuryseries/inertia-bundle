<?php

namespace MercurySeries\Bundle\InertiaBundle\Exception;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * This exception is thrown when a submitted form is invalid.
 */
class InvalidFormException extends HttpException
{
    private $form;

    public function __construct(
        Form $form,
        string $message = '',
        \Throwable $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        parent::__construct(
            Response::HTTP_UNPROCESSABLE_ENTITY, $message, $previous, $headers, $code
        );
        $this->form = $form;
    }

    public function getForm(): Form
    {
        return $this->form;
    }

    /**
     * Return all of the errors from all of the fields of the form.
     */
    public function getFormErrors(): array
    {
        $errors = [];

        foreach ($this->getForm()->getErrors(true) as $error) {
            $parentFormName = $error->getOrigin()->getParent() ? $error->getOrigin()->getParent()->getName() : null;

            if (null !== $parentFormName) {
                if (!isset($errors[$parentFormName])) {
                    $errors[$parentFormName] = [];
                }

                $errors[$parentFormName][$error->getOrigin()->getName()] = $error->getMessage();
            } else {
                $errorKey = $error->getOrigin()->getName() ?: '__non_field__';
                $errors[$errorKey] = $error->getMessage();
            }
        }

        return $errors;
    }
}
