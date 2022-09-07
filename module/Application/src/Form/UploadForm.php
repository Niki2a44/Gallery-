<?php 

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;

class UploadForm extends Form
{
    public function __construct()
    {
        parent::__construct('upload-form');
        $this->setAttribute('enctype','multipart/form-data');
        $this->setAttribute('method','post');
        $this->addElements();
        $this->inputFilter();
    }
     
    function addElements(){
        $this->add(['type'=>'file','name'=>'file','attributes' => ['id' => 'formFile','class'=> 'form-control'],'options' => ['label' => ' ',]]);
        $this->add(['type'  => 'submit','name' => 'submit','attributes' => ['value' => 'Upload','class'=> 'btn btn-primary','id' => 'submitbutton']]);
    }
    function inputFilter(){
         $filter =new InputFilter();
         $this->setInputFilter($filter);
         $filter->add([
            'type'     => 'Laminas\InputFilter\FileInput',
            'name'     => 'file',
            'required' => true,   
            'validators' => [
                ['name'    => 'FileUploadFile'],
                [
                    'name'    => 'FileMimeType',                        
                    'options' => [                            
                        'mimeType'  => ['image/jpeg','image/jpg', 'image/png']
                    ]
                ],
                ['name'    => 'FileIsImage'],
                [
                    'name'    => 'FileImageSize',
                    'options' => [
                        'maxWidth'  => 8096,
                        'maxHeight' => 8096
                    ]
                ],
            ],
            'filters'  => [                    
                [
                    'name' => 'FileRenameUpload',
                    'options' => [  
                        'target' => './data/images',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => false
                    ]
                ]
            ],   
        ]);
    }
}