<?php
namespace Agere\User\Form;

use Zend\Form\Form;

class UserForm extends Form
{
    protected $objectManager;

    public function init()
    {
        $this->setName('user');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');

        $this->add([
            'name' => 'user',
            'type' => 'Agere\User\Form\UserFieldset',
            'options' => [
                'use_as_base_fieldset' => true,
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type' => 'submit',
                'value' => 'Send',
                'class' => 'btn btn-primary',
            ],
        ]);
    }
}