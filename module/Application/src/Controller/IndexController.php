<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Form\UploadForm;


class IndexController extends AbstractActionController
{
    private $dir = './data/images/';
    // Returns a list of all files in the image directory.
    public function getFiles()
    {
        $saveDir = $this->dir;
        if (!is_dir($saveDir)) {
            if (!mkdir($saveDir)) {
                throw new \Exception('Error while creating image directory: ' . error_get_last());
            }
        }
        $files = [];
        $openDir = opendir($saveDir);
        while (($file = readdir($openDir)) !== false) {

            if ($file == '.' || $file == '..') {
                continue;
            }
            $files[] = $file;
        }
        return $files;
    }
    //corrects name to proper state
    public function GetPathByName($name)
    {
        $correction = ["/", "\\"];
        $name = str_replace($correction, "", $name);
        $name = $this->dir . $name;
        return $name;
    }
    public function getImage($filePath)
    {
        return file_get_contents($filePath);
    }
    public function indexAction()
    {
        
        $form = new UploadForm();
        //getting all saved files in images directory
        $files = $this->getFiles();
        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $content =  $request->getPost()->toArray();
            //Different action if delete action
            if ($content['submit'] == 'Delete') {
                $path = $this->GetPathByName($content['file']);
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
        $name = $this->GetPathByName($name);
        $response = $this->getResponse();
        //getting image 
        $file = $this->getImage($name);
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
