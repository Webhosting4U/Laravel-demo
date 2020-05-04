<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DummyRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class DummyCrudController.
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DummyCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Dummy');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/dummy');
        $this->crud->setEntityNameStrings('dummy', 'dummies');
    }

    protected function setupListOperation()
    {
        CRUD::addColumn('name');
        CRUD::addColumn('description');

        foreach ($this->groups() as $groupKey => $groupFields) {
            CRUD::addColumn([
                'name'     => $groupKey,
                'label'    => str_replace('_', ' ', Str::title($groupKey)),
                'type'     => 'array_count',
            ]);
        }
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(DummyRequest::class);
        $this->crud->setOperationSetting('contentClass', 'col-md-12');

        CRUD::addField('name');
        CRUD::addField('description');

        foreach ($this->groups() as $groupKey => $groupFields) {
            CRUD::addField([
                'name'     => $groupKey,
                'label'    => str_replace('_', ' ', Str::title($groupKey)),
                'type'     => 'repeatable',
                'fake'     => true,
                'store_in' => 'extras',
                'fields'   => $groupFields,
            ]);
        }
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
        $this->crud->setOperationSetting('contentClass', 'col-md-12');

        // for field types that have multiple name (ex: date_range)
        // split those into two separate text columns
        foreach ($this->groups() as $groupKey => $groupFields) {
            CRUD::removeColumn($groupKey);

            foreach ($groupFields as $key => $field) {
                if (is_array($field['name'])) {
                    foreach ($field['name'] as $name) {
                        $newField = $field;
                        $newField['name'] = $name;
                        $newField['type'] = 'text';
                        $groupFields[] = $newField;
                    }
                    unset($groupFields[$key]);
                }
            }

            // only consider fields that have both name and label (needed for table column)
            // reject custom_html fields (since they have no value)
            $validFields = collect($groupFields)->reject(function ($value, $key) {
                $is_custom_html_field = $value['type'] == 'custom_html';
                $does_not_have_label = !isset($value['label']);
                $does_not_have_name = !isset($value['name']);

                return $is_custom_html_field || $does_not_have_label || $does_not_have_name;
            })->pluck('label', 'name');

            CRUD::addColumn([
                'name'     => $groupKey,
                'label'    => str_replace('_', ' ', Str::title($groupKey)),
                'type'     => 'table',
                'columns'  => $validFields,
            ]);
        }

        CRUD::addColumn([
            'name' => 'created_at',
            'type' => 'datetime',
        ]);
        CRUD::addColumn([
            'name' => 'updated_at',
            'type' => 'datetime',
        ]);
    }

    protected function groups()
    {
        // instead of manually defining all the field type here too
        // let's pull all field types defined in MonsterCrudController instead
        // since they're already nicely split by tab,
        // we can split them exactly the same here, but into groups instead of tabs
        // (one repeatable field for each tab in MonsterCrudController)
        $groups['simple'] = MonsterCrudController::getFieldsArrayForSimpleTab();
        $groups['time_and_space'] = MonsterCrudController::getFieldsArrayForTimeAndSpaceTab();
        $groups['relationships'] = MonsterCrudController::getFieldsArrayForRelationshipsTab();
        $groups['selects'] = MonsterCrudController::getFieldsArrayForSelectsTab();
        $groups['uploads'] = MonsterCrudController::getFieldsArrayForUploadsTab();
        $groups['big_texts'] = MonsterCrudController::getFieldsArrayForBigTextsTab();
        $groups['miscellaneous'] = MonsterCrudController::getFieldsArrayForMiscellaneousTab();

        // some fields do not make sense, or do not work inside repeatable, so let's exclude them
        $excludedFieldTypes = [
            'address', // TODO
            'address_google', // TODO
            'relationship', // TODO
            'select2_from_ajax', // TODO
            'select2_from_ajax_multiple', // TODO

            'checklist_dependency', // only available in PermissionManager package
            'custom_html', // this works (of course), it's only used for heading, but the page looks better without them
            'enum', // doesn't make sense inside repeatable
            'page_or_link', // only available in PageManager package
            'upload', // currently impossible to make it work inside repeatable;
            'upload_multiple',  // currently impossible to make it work inside repeatable;
        ];

        foreach ($groups as $groupKey => $fields) {
            $groups[$groupKey] = Arr::where($fields, function ($field) use ($excludedFieldTypes) {
                return !in_array($field['type'], $excludedFieldTypes);
            });
        }

        return $groups;
    }
}