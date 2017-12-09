<?php
namespace AppBundle\Service;
use Symfony\Component\Form\FormInterface;

/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 06/12/2017
 * Time: 21:31
 */
class FormErrorsRenderer
{
    public function renderErrors($errors)
    {
        $data = [
            'type' => 'validation_error',
            'title' => 'There was a validation error',
            'errors' => $errors
        ];

        return $data;
    }
}