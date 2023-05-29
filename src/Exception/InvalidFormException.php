<?php

namespace MercurySeries\Bundle\InertiaMakerBundle\Exception;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * This exception is thrown when a submitted form is invalid.
 */
class InvalidFormException extends HttpException
{
    public function __construct(
        private Form $form,
        string $message = '',
        \Throwable $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        parent::__construct(
            Response::HTTP_UNPROCESSABLE_ENTITY, $message, $previous, $headers, $code
        );
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
            $errorKey = $error->getOrigin()->getName() ?: '__non_field__';
            $errors[$errorKey] = $error->getMessage();
        }

        return $errors;
    }
}
