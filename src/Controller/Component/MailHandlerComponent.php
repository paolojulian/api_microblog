<?php
namespace App\Controller\Component;

use App\Exception\EmailNotSentException;
use Cake\Controller\Component;
use Cake\Mailer\Email;
use Cake\Core\Configure;
/**
 * MailHandler component
 */
class MailHandlerComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * Sends an Activation Mail to an address
     * wont send if in debug mode
     * 
     * @param string $to - email address to send the mail
     * @param array $data - contains activation codes and name of the recipient
     * 
     * @return void
     * 
     * @throws EmailNotSentException - Something went wrong with sending the mail
     */
    public function sendActivationMail(string $to, array $data)
    {
        if (Configure::read('debug')) {
            return false;
        }

        try {
            $email = new Email('default');
            $email
                ->transport('gmail')
                ->setViewVars($data)
                ->template('activation')
                ->emailFormat('html')
                ->subject('Account Activation')
                ->from(['paolovincent.yns@gmail.com' => 'LaCosina.com'])
                ->to($to)
                ->send();
        } catch (\Exception $e) {
            throw new EmailNotSentException();
        }
    }
}
