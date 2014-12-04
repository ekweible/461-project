<?php
return array(
	'controllers'=>array(
		'invokables'=> array(
			'Medical\Controller\Medical'=>'Medical\Controller\MedicalController',
			'Medical\Controller\User'=>'Medical\Controller\UserController',
		),
	),
	
	'router'=>array(
		'routes'=>array(
			'medical'=>array(
				'type'=>'segment',
				'options'=>array(
					'route'=>'/VidFinder[/:action][/:id]',
					'constraints'=>array(
						'action'=>'[a-zA-Z][a-zA-Z0-9_-]*',
						'id'=>'[0-9]+',
					),
					'defaults'=>array(
						'controller'=>'Medical\Controller\Medical',
						'action'=>'index',
					),
				),
			),
			'user'=>array(
				'type'=>'segment',
				'options'=>array(
					'route'=>'/user[/:action][/:id]',
					'constraints'=>array(
						'action'=>'[a-zA-Z][a-zA-Z0-9_-]*',
						'id'=>'[0-9]+',
					),
					'defaults'=>array(
						'controller'=>'Medical\Controller\User',
						'action'=>'index',
					),
				),
			)
		),
	),
	'view_manager'=> array(
		'template_path_stack'=>array(
			'medical'=>__DIR__ . '/../view',
		),
	),
);