<?php

return [
    'product_specification' => 'Product Specification',
    'specification_groups' => [
        'title' => 'Specification Groups',
        'menu_name' => 'Groups',

        'create' => [
            'title' => 'Create Specification Group',
        ],

        'edit' => [
            'title' => 'Edit Specification Group ":name"',
        ],
    ],

    'specification_attributes' => [
        'title' => 'Specification Attributes',
        'menu_name' => 'Attributes',

        'group' => 'Associated Group',
        'group_placeholder' => 'Choose any Group',
        'name_placeholder' => 'Enter attribute name',
        'type' => 'Field Type',
        'type_placeholder' => 'Select field type',
        'default_value' => 'Default Value',
        'default_value_placeholder' => 'Enter default value (optional)',
        'options' => [
            'heading' => 'Options',

            'add' => [
                'label' => 'Add new option',
            ],
        ],

        'create' => [
            'title' => 'Create Specification Attribute',
        ],

        'edit' => [
            'title' => 'Edit Specification Attribute ":name"',
        ],
    ],

    'specification_tables' => [
        'title' => 'Specification Tables',
        'menu_name' => 'Tables',

        'create' => [
            'title' => 'Create Specification Table',
        ],

        'edit' => [
            'title' => 'Edit Specification Table ":name"',
        ],

        'fields' => [
            'groups' => 'Select the groups to display in this table',
            'name' => 'Group name',
            'assigned_groups' => 'Assigned Groups',
            'sorting' => 'Sorting',
        ],
    ],

    'product' => [
        'specification_table' => [
            'options' => 'Options',
            'title' => 'Specification Table',
            'select_none' => 'None',
            'description' => 'Select the specification table to display in this product',
            'group' => 'Group',
            'attribute' => 'Attribute',
            'value' => 'Attribute value',
            'hide' => 'Hide',
            'sorting' => 'Sorting',
            'enter_value' => 'Enter value',
            'enter_translation' => 'Enter translation',
            'not_set' => 'Not set',
        ],
    ],

    'enums' => [
        'field_types' => [
            'text' => 'Text',
            'textarea' => 'Textarea',
            'select' => 'Select',
            'checkbox' => 'Checkbox',
            'radio' => 'Radio',
        ],
    ],
];
