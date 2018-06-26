<?php

namespace AppBundle\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
    /**
     * @SecurityAssert\UserPassword(
     *     message = "Wrong value for your current password"
     * )
     */
    public $oldPassword;

    /**
     * @Assert\Length(
     *     min = 6,
     *     minMessage = "Password should by at least 6 chars long"
     * )
     * @Assert\Regex(
     *     pattern="/^\S*(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/",
     *     message="Password should contains at least one lowercase character, one uppercase caracter and one number"
     * )
     */
    public $newPassword;
}