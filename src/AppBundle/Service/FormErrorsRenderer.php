<?php
namespace AppBundle\Service;
use Symfony\Component\Form\FormInterface;

/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 06/12/2017
 * Time: 21:31
 */
class FormErrorsRecuperator
{
    public function getFormErrors(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getFormErrors($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}