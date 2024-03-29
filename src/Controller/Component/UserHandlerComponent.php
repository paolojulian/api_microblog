<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use App\Lib\Utils\ImageResizerHelper;
use Cake\Routing\Router;

/**
 * UserHandler component
 */
class UserHandlerComponent extends Component
{
    // public $components = ['MailHandler', 'UploadImgHandler', 'NotificationHandler'];
    public $components = ['MailHandler', 'UploadImgHandler'];
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * Handles the sending of mail for account activation
     * Assumes everything in data is already validated
     * 
     * @param array $data - User Object
     * @param string $serverName - The server name
     * 
     * @return bool
     */
    public function sendActivationMail(array $data)
    {
        $serverName = Router::url('/api/auth/activate', true);
        $this->MailHandler->sendActivationMail(
            $data['email'],
            [
                'fullName' => $data['first_name'] . ' ' . $data['last_name'],
                'activationUrl' => $serverName . '/' . $data['activation_key']
            ]
        );
        return true;
    }

    public function uploadimage($file, $id)
    {
        try {
            $path = "profiles/$id/";
            $uploadedFile = $this->UploadImgHandler->uploadImage(
                $file,
                $path
            );
            $imageName = $uploadedFile['imageName'];
            $imageResizer = new ImageResizerHelper("$path$imageName.png");
            $imageResizer->multipleResizeMaxHeight(
                $path.$imageName,
                [256, 128, 64, 32, 24]
            );
        } catch (\Exception $e) {
            throw $e;
        }

        return $uploadedFile['basePath'];
    }

    // public function notifyAfterFollow(Follower $follower)
    // {
    //     $notificationDTO = new NotificationDTO();
    //     $notificationDTO->setFollowed(
    //         $follower->user_id,
    //         $follower->following_id
    //     );
    //     $this->NotificationHandler->notifyUser($notificationDTO);
    // }
}
