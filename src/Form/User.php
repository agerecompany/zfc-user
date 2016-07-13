<?php
namespace Agere\User\Form;

use Zend\Form\Form,
	Zend\InputFilter\Factory as InputFactory,
	Zend\InputFilter\InputFilter;

class User extends Form {

	public function __construct($id, $fields, $dbAdapter)
	{
		parent::__construct('user');

		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype', 'multipart/form-data');

		$this->add([
			'name' => 'id',
			'type' => 'hidden',
		]);


		//$this->add(['name' => 'departmentId']);
		$this->add(['name' => 'supplierId']);
		$this->add([
			'name' => 'email',
			'attributes' => [
				'required'		=> 'required',
				'autocomplete'	=> 'off',
			],
		]);
		$this->add([
			'name' => 'password',
			'attributes' => [
				'required' => 'required'
			],
		]);
		$this->add([
			'name' => 'firstName',
			'attributes' => [
				'required' => 'required',
				'onkeyup' => 'preventDigits(this);',
			],
		]);
		$this->add([
			'name' => 'lastName',
			'attributes' => [
				'required' => 'required',
				'onkeyup' => 'preventDigits(this);',
			],
		]);
		$this->add([
			'name' => 'patronymic',
			'attributes' => [
				'onkeyup' => 'preventDigits(this);',
			],
		]);
		$this->add(['name' => 'phone']);
		$this->add(['name' => 'phoneWork']);
		$this->add(['name' => 'phoneInternal']);
		$this->add(['name' => 'post']);
		$this->add([
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
		]);
		$this->add([
			'name' => 'photo',
			'attributes' => [
				'type' => 'file',
			],
		]);
		$this->add([
			'name' => 'cityId[]',
			'attributes' => [
				'required' 	=> 'required',
				'multiple'	=> 'multiple',
				'size'		=> '5'
			],
		]);
		$this->add(['name' => 'cityId']);
		$this->add([
			'name' => 'roleId[]',
			'attributes' => [
				'required' => 'required',
				'multiple'	=> 'multiple',
				'size'		=> '5'
			],
		]);
		$this->add(['name' => 'roleId']);
		$this->add([
			'name' => 'showIndex',
			'type' => 'Zend\Form\Element\Radio',
			'options' => array(
				'value_options' => array(
					'supplier'	=> 'Поставщик',
					'city'		=> 'Город',
				),
			)
		]);
		$this->add([
			'name' => 'notation',
			'type' => 'textarea',
		]);
        $this->add([
            'name' => 'sendEmails',
            'type' => 'checkbox',
        ]);

		$this->add([
			'name' => 'save',
			'options' => [
				'ignore' => true,
				'label' => 'Save'
			]
		]);


		$inputFilter = new InputFilter();
		$factory = new InputFactory();

		/*$inputFilter->add($factory->createInput(array(
			'name'	=> 'departmentId',
			'required' => false,
		)));*/

		$inputFilter->remove('email');

		$validator = new \Zend\Validator\NotEmpty();
		$validator->setMessage(
		    'email адрес должен быть уникальный'
		);

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'supplierId',
			'required' => false,
		)));

		$inputFilter->add([
            'name' => 'email',
            'required' => true,
            'validators' => [
                $validator,
				// array(
				// 	'name' => 'StringLength',
				// 	'options' => array(
				// 		'encoding' => 'UTF-8',
				// 		'max' => 100
				// 	)
				// ),
				// ['name' => 'EmailAddress'],
				// array(
				// 	'name' => 'Db\NoRecordExists',
				// 	'options' => array(
				// 		'table' => 'users',
				// 		'field' => 'email',
				// 		'adapter' => $dbAdapter,
				// 		'exclude' => array(
				// 			'field' => 'id',
				// 			'value' => (int) $id,
				// 		),
				// 	)
				// ),
            ],
        ]);

		// if (isset($fields['email']))
		// {
		//
		// 	var_dump(1); die;
		// 	$inputFilter->add($factory->createInput(array(
		// 		'name'	=> 'email',
		// 		'required' => true,
		// 		'validators' => array(
		// 			new Zend\Validator\NotEmpty(),
		// 			array(
		// 				'name' => 'StringLength',
		// 				'options' => array(
		// 					'encoding' => 'UTF-8',
		// 					'max' => 100
		// 				)
		// 			),
		// 			['name' => 'EmailAddress'],
		// 			array(
		// 				'name' => 'Db\NoRecordExists',
		// 				'options' => array(
		// 					'table' => 'users',
		// 					'field' => 'email',
		// 					'adapter' => $dbAdapter,
		// 					// 'exclude' => array(
		// 					// 	'field' => 'id',
		// 					// 	'value' => (int) $id,
		// 					// ),
		// 				)
		// 			),
		// 		)
		// 	)));
		// }

		if (isset($fields['password']))
		{
			$inputFilter->add($factory->createInput(array(
				'name'	=> 'password',
				'required' => true,
			)));
		}

		if (isset($fields['firstName']))
		{
			$inputFilter->add($factory->createInput(array(
				'name'	=> 'firstName',
				'required' => true,
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'max' => 30
						)
					),
				)
			)));
		}

		if (isset($fields['lastName']))
		{
			$inputFilter->add($factory->createInput(array(
				'name'	=> 'lastName',
				'required' => true,
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'max' => 30
						)
					),
				)
			)));
		}

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'patronymic',
			'required' => false,
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'max' => 50
					)
				),
			)
		)));

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'phone',
			'required' => false,
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'max' => 20
					)
				),
			)
		)));

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'phoneWork',
			'required' => false,
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'max' => 20
					)
				),
			)
		)));

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'phoneInternal',
			'required' => false,
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'max' => 20
					)
				),
				['name' => 'Digits']
			)
		)));

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'post',
			'required' => false,
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'max' => 100
					)
				),
			)
		)));

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'dateBirth',
			'required' => true,
			'filters' => array(
				array('name' => 'StringTrim'),
			),
		)));

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'dateEmployment',
			'required' => false,
			'filters' => array(
				array('name' => 'StringTrim'),
			),
		)));

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'photo',
			'required' => false,
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'max' => 255
					)
				),
			)
		)));

		if (isset($fields['cityId']))
		{
			$inputFilter->add($factory->createInput(array(
				'name'	=> 'cityId',
				'required' => true,
			)));
		}

		if (isset($fields['roleId']))
		{
			$inputFilter->add($factory->createInput(array(
				'name'	=> 'roleId',
				'required' => true,
			)));
		}

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'showIndex',
			'required' => false,
		)));

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'notation',
			'required' => false,
		)));

        $inputFilter->add($factory->createInput(array(
            'name'	=> 'sendEmails',
            'required' => false,
        )));


		$this->setInputFilter($inputFilter);
	}

}
