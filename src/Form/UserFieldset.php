<?php
namespace Agere\User\Form;

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Form\Fieldset;

class UserFieldset extends Fieldset implements InputFilterProviderInterface, ObjectManagerAwareInterface
{
    use ProvidesObjectManager;

    public function init()
    {
        $this->setName('user');

        $this->add([
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ]);

        /*$this->add([
            'name' => 'supplierId',
            'options' => [
                'label' => 'supplierId',
            ],
            'attributes' => [
                'id' => 'supplierId',
                'class' => 'form-control',
                'placeholder' => 'Enter supplierId...',
                'required' => 'required'
            ],
        ]);*/

        $this->add([
            'name' => 'email',
            'options' => [
                'label' => 'Email',
            ],
            'attributes' => [
                'id' => 'email',
                'class' => 'form-control',
                'placeholder' => 'Enter Email...',
                'required' => 'required'
            ],
        ]);

        $this->add([
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
            'attributes' => [
                'id' => 'password',
                'class' => 'form-control',
                'placeholder' => 'Enter Password...',
                'required' => 'required'
            ],
        ]);

        $this->add([
            'name' => 'fio',
            'options' => [
                'label' => 'FIO',
            ],
            'attributes' => [
                'id' => 'fio',
                'class' => 'form-control',
                'placeholder' => 'Enter  FIO...',
                'required' => 'required'
            ],
        ]);

        /*$this->add([
            'name' => 'lastName',
            'options' => [
                'label' => 'Last Name',
            ],
            'attributes' => [
                'id' => 'lastName',
                'class' => 'form-control',
                'placeholder' => 'Enter Last Name...',
                'required' => 'required'
            ],
        ]);

        $this->add([
            'name' => 'patronymic',
            'options' => [
                'label' => 'Patronymic',
            ],
            'attributes' => [
                'id' => 'patronymic',
                'class' => 'form-control',
                'placeholder' => 'Enter Patronymic...',
            ],
        ]);*/

        $this->add([
            'name' => 'phone',
            'options' => [
                'label' => 'phone',
            ],
            'attributes' => [
                'id' => 'phone',
                'class' => 'form-control',
                'placeholder' => 'Enter phone..',
            ],
        ]);

        $this->add([
            'name' => 'phoneWork',
            'options' => [
                'label' => 'phoneWork',
            ],
            'attributes' => [
                'id' => 'phoneWork',
                'class' => 'form-control',
                'placeholder' => 'Enter phoneWork..',
            ],
        ]);

        $this->add([
            'name' => 'phoneInternal',
            'options' => [
                'label' => 'phoneInternal',
            ],
            'attributes' => [
                'id' => 'phoneInternal',
                'class' => 'form-control',
                'placeholder' => 'Enter phoneInternal..',
            ],
        ]);

        $this->add([
            'name' => 'post',
            'options' => [
                'label' => 'post',
            ],
            'attributes' => [
                'id' => 'post',
                'class' => 'form-control',
                'placeholder' => 'Enter Post..',
            ],
        ]);

        /*$this->add([
            'name' => 'photo',
            'type' => \Zend\Form\Element\File::class,
            'options' => [
                'label' => 'image',
            ],

            'attributes' => [
                'type' => 'file',
            ],
        ]);*/

        /*$this->add([
                'type' => 'Zend\Form\Element\File',
                'name' => 'photo',
                'options' => [
                    'label' => 'Your photo'
                ],
                'attributes' => [
                    'required' => 'required',
                    'id'  => 'photo'
                ],
            ]);*/

        $this->add(
            [
                'name' => 'dateBirth',
                'type' => 'Date',
                'attributes' => [
                    'type' => 'text',
                    'class' => 'datepicker',
                ],
                'options' => [
                    'label' => 'Дата  рождения',
                    'format' => 'm/d/Y',
                ],
            ]
        );

        $this->add(
            [
                'name' => 'dateEmployment',
                'type' => 'Date',
                'attributes' => [
                    'type' => 'text',
                    'class' => 'datepicker',
                ],
                'options' => [
                    'label' => 'Дата  рождения',
                    'format' => 'm/d/Y',
                ],
            ]
        );

        $this->add([
            'name' => 'photo',
            'attributes' => [
                'type' => 'file',
            ],
        ]);

        /*$this->add([
            'name' => 'dateBirth',
            'attributes' => [
                'readonly' => 'readonly',
                'required' => 'required',
            ],
        ]);

        $this->add([
            'name' => 'dateEmployment',
            'attributes' => [
                'readonly' => 'readonly',
            ],
        ]);*/

        /*$this->add([
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'roleId',
            'options' => [
                'object_manager' => $this->getObjectManager(),
                'target_class' => 'Agere\User\Model\UsersRoles',
                'property' => 'role',
            ],
        ]);*/

        /*$this->add([
            'name' => 'name',
            'options' => [
                'label' => 'Название',
            ],
            'attributes' => [
                'id' => 'name',
                'class' => 'form-control',
                'placeholder' => 'Enter contract number...',
            ],
        ]);

        $this->add([
                'name' => 'description',
                'type' => 'textarea',
                'options' => [
                    'label' => 'Описание',
                ],
                'attributes' => [
                    'id' => 'description',
                    'class' => 'form-control',
                    'placeholder' => 'Enter contract number...',
                    'rows' => 5,
                ],
        ]);

        $this->add([
                'name' => 'appointment',
                'type' => 'textarea',
                'options' => [
                    'label' => 'Предназначение',
                ],
                'attributes' => [
                    'id' => 'appointment',
                    'class' => 'form-control',
                    'placeholder' => 'Enter contract number...',
                    'rows' => 5,
                ],
            ]);

        $this->add([
                'name' => 'document',
                'options' => [
                    'label' => 'Документ',
                ],
                'attributes' => [
                    'id' => 'document',
                    'class' => 'form-control',
                    'placeholder' => 'Enter contract number...',
                ],
            ]);

        $this->add([
                'name' => 'price',
                'options' => [
                    'label' => 'Цена',
                ],
                'attributes' => [
                    'id' => 'price',
                    'class' => 'form-control',
                    'placeholder' => 'Enter price...',
                ],
            ]);

        $this->add([
                'name' => 'count',
                'options' => [
                    'label' => 'Количество',
                ],
                'attributes' => [
                    'id' => 'count',
                    'class' => 'form-control',
                    'placeholder' => 'Enter count...',
                ],
            ]);

        $this->add([
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'typeMaterial',
            'options' => [
                'object_manager' => $this->getObjectManager(),
              'target_class' => 'Agere\Material\Model\TypeMaterial',
                'property' => 'name',
            ],
        ]);

        $this->add([
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'materialCategory',
                'options' => [
                    'object_manager' => $this->getObjectManager(),
                    'target_class' => 'Agere\Material\Model\MaterialCategory',
                    'property' => 'name',
                ],
            ]);*/

        /*$this->add([
                'name' => 'photo',
                'options' => [
                    'label' => 'Фото',
                ],
                'attributes' => [
                    'id' => 'photo',
                    'class' => 'form-control',
                    'placeholder' => 'Enter contract number...',
                ],
            ]);*/
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'email' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],

            /*'photo' => [
                'required' => false,
                'validators' => array (
                    array (
                        'name' => 'filesize',
                        'options' => array (
                            'max' => 2097152, // 2 MB
                        ),
                    ),
                    array (
                        'name' => 'filemimetype',
                        'options' => array (
                            'mimeType' => 'image/png,image/x-png,image/jpg,image/jpeg,image/gif',
                        )
                    ),
                    array (
                        'name' => 'fileimagesize',
                        'options' => array (
                            'maxWidth' => 5,
                            'maxHeight' => 5
                        )
                    ),
                ),
                'filters' => array (
                    // the filter below will save the uploaded file under
                    // <app-path>/data/images/photos/<tmp_name>_<random-data>
                    array (
                        'name'    => 'filerenameupload',
                        'options' => array (
                            // Notice: Make sure that the folder below is existing on your system
                            //         otherwise this filter will not pass and you will get strange
                            //         error message reporting that the required field is empty
                            'target'    => 'data/image/photos/',
                            'randomize' => true,
                        ),
                    ),
                ),
            ],*/
        ];
    }
}
