<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Form\UploadForm;


class IndexController extends AbstractActionController
{
    private $imageModel;

    public function __construct($imageModel)
    {
        $this->imageModel = $imageModel;
    }


    public function indexAction()
    {
        
        $form = new UploadForm();
        //getting all saved files in images directory
        $files = $this->imageModel->getFiles();
        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $content =  $request->getPost()->toArray();
            //Different action if delete action
            if ($content['submit'] == 'Delete') {
                $path = $this->imageModel->GetPathByName($content['file']);
                //checking if file exists 
                if (file_exists($path)) {
                    unlink($path);//deleting file
                }
                return $this->redirect()->toRoute('home');
            } else {
                
                $data = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
                //sending data to form 
                $form->setData($data);
                if ($form->isValid()) {
                    //move upload images to images directory
                    $data = $form->getData();
                    return $this->redirect()->toRoute('home');
                }
            }
        }



        return new ViewModel(['form' => $form, 'files' => $files]);
    }
    //Returs image 
    public function fileAction()
    {   
       //Getting image name from query
        $name = $this->params()->fromQuery('name', '');
        //Correcting name and adding path 
        $name = $this->imageModel->GetPathByName($name);
        $response = $this->getResponse();
        //getting image 
        $file = $this->imageModel->getImage($name);
        if ($file !== false) {
            //putting image in response
            $response->setContent($file);
        } else {
            $this->getResponse()->setStatusCode(500);
            return;
        }
        //sending response
        return $this->getResponse();
    }
    
}
