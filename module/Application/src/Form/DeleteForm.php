<?php 

namespace Application\Form;

use Laminas\Form\Form;


class DeleteForm extends Form
{
    public function __construct($filename)
    {
        parent::__construct('delete-form');
        $this->setAttribute('method', 'post');
        $this->addElements($filename);
       
    }

    public function addElements($filename)
    {
        $this->add(['type'=>'text','name'=>'file','attributes' => ['class'=> 'hidden' ,'value'=> $filename],'options' => ['label' => ' ',]]);
        $this->add(['type'  => 'submit','name' => 'submit','attributes' => ['value' => 'Delete','class'=> 'btn btn-danger','style'=>'width:100%;border-radius:0;','id' => 'submitbutton']]);
    }
}