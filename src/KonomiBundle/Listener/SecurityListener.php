<?php
namespace KonomiBundle\Listener;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use KonomiBundle\Entity\User;

/**
 * konomi security listener class
 *
 * @package konomi
 * @license <property of MWa/MiS>
 */
class SecurityListener {

    /**
     * SecurityListener constructor.
     */
    public function __construct( ContainerInterface $oContainer ) {

        // Set container object
        $this->oContainer = $oContainer;
    }





    /**
     * Authentication success
     * Updates the timestamp on every authentication success.
     *
     * @return void
     */
    public function onSecurityAuthentication() {

        // Get doctrine manager
        $oManager = $this->oContainer->get('doctrine.orm.entity_manager');

        // Get current date time for timestamp
        $oDateTime  = new \DateTime( "now" );
        $oDateTime->setTimezone( new \DateTimeZone($this->oContainer->getParameter("date_time_zone")) );

        // Get token object
        /** @var TokenInterface $oToken */
        $oToken = $this->oContainer->get('security.token_storage')->getToken();

        // Check if a user is present / a token exists
        if ( $oToken ) {

            // Get current user
            /** @var User $oUser */
            $oUser = $oToken->getUser();

            // Update timestamp with current datetime object
            $oUser->setTimestamp( $oDateTime );

            // Flush all changes
            $oManager->flush();
        }
    }
}
