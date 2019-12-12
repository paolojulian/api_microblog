<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Utility\Security;
use App\Lib\Utils\FileUploadHelper;
use Cake\Http\Exception\InternalErrorException;

/**
 * UploadImgHandler component
 */
class UploadImgHandlerComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function uploadImage($file, $path)
    {
        try {
            if ($file['error']) {
                throw new InternalErrorException();
            }
            $imageName = Security::hash(Security::randomBytes(5) . $path . time());
            $imgPath = "img/$path";
            $fullPath = WWW_ROOT . $imgPath;
            FileUploadHelper::uploadImg(
                $fullPath,
                $file,
                $imageName.'.png'
            );
        } catch (\Exception $e) {
            throw $e;
        }
        return [
            'basePath' => "/$imgPath$imageName",
            'imageName' => $imageName
        ];
    }
}
