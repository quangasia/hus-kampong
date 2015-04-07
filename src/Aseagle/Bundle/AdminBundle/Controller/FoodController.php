<?php

/**
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\AdminBundle\Controller;

use Aseagle\Bundle\CoreBundle\Helper\Html;

/**
 * FoodController
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class FoodController extends BaseController {

    /**
     * indexAction
     */
    public function indexAction() {
        return parent::indexAction();
    }

    /* (non-PHPdoc)
     * @see \Aseagle\Bundle\AdminBundle\Controller\BaseController::grid()
     */
    protected function grid($entities) {
        $grid = array ();
        foreach ($entities as $item) {
            $grid [] = array (
                '<input type="checkbox" name="ids[]" class="check" value="' . $item->getId() . '"/>',
                '<a href="'.$this->generateUrl('admin_food_new', array ('id' => $item->getId())).'">' . $item->getTitle() . '</a>',
                is_object($item->getAuthor()) ? $item->getAuthor()->getFullname() : '_',
                (NULL != $item->getPageView()) ? $item->getPageView() : 0,
                is_object($item->getCreated()) ? $item->getCreated()->format('d/m/Y') : '',
                Html::showStatusInTable($this->container, $item->getEnabled()),
                Html::showActionButtonsInTable($this->container, array (
                    'edit' => $this->generateUrl('admin_food_new', array (
                        'id' => $item->getId()
                    ))
                ))
            );
        }
        
        return $grid;
    }

     /**
     * uploadAction
     */
    public function uploadAction() {
            
        // 5 minutes execution time
        @set_time_limit(5 * 60);
        
        // Uncomment this one to fake upload time
        // usleep(5000);
        
        // Settings
        //$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
        $targetDir = 'uploads/tmp';
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds
                                
        // Create target dir
        if (! file_exists($targetDir)) {
            @mkdir($targetDir);
        }
        
        // Get a file name
        if (isset($_REQUEST ["name"])) {
            $fileName = $_REQUEST ["name"];
        } elseif (! empty($_FILES)) {
            $fileName = $_FILES ["file"] ["name"];
        } else {
            $fileName = uniqid("file_");
        }
        
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        
        // Chunking might be enabled
        $chunk = isset($_REQUEST ["chunk"]) ? intval($_REQUEST ["chunk"]) : 0;
        $chunks = isset($_REQUEST ["chunks"]) ? intval($_REQUEST ["chunks"]) : 0;
        
        // Remove old temp files
        if ($cleanupTargetDir) {
            if (! is_dir($targetDir) || ! $dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }
            
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
                
                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}.part") {
                    continue;
                }
                
                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }
        
        // Open temp file
        if (! $out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }
        
        if (! empty($_FILES)) {
            if ($_FILES ["file"] ["error"] || ! is_uploaded_file($_FILES ["file"] ["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }
            
            // Read binary input stream and append it to temp file
            if (! $in = @fopen($_FILES ["file"] ["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (! $in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }
        
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        
        @fclose($out);
        @fclose($in);
        
        // Check if file has been uploaded
        if (! $chunks || $chunk == $chunks - 1) {
            // Strip the temp .part suffix off
            rename("{$filePath}.part", $filePath);
            
            $image = $this->get('backend')->getMediaManager()->createObject();

            $imageDir = $this->get('kernel')->getRootDir() . '/../web/uploads/products/';
            $imgName = time() . '_' . $fileName;
            
            if (!is_dir($imageDir)) mkdir($imageDir);
            copy($filePath, $imageDir . $imgName);
            $image->setType(1);
            $image->setPath($imgName);
            
            @unlink($filePath);
            
            $response = new \stdClass();
            $response->jsonrpc = "2.0";
            $response->result = "OK";
            $response->id = time();
            $response->src = '/uploads/products/' . $image->getPath();
            $response->name = $image->getName();
            $response->path = $imgName;
            
            // Return Success JSON-RPC response
            die(json_encode($response));
        }
        
        // Return Success JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : "OK", "id" : "id"}');
    }

    /**
     * deleteImageAction
     */
    public function deleteMediaAction() {
        $request = $this->getRequest();
        
        if($request->getMethod() == 'POST' && $request->isXmlHttpRequest()) {
            $id = $request->get('id');
            $image = $this->get('backend')->getMediaManager()->getObject($id);
            $this->get('backend')->getMediaManager()->deleteObject($image);
            return new Response(json_encode(array('status' => 'OK')));
        }
        
        return new Response(json_encode(array('status' => 'KO')));
    }

}
