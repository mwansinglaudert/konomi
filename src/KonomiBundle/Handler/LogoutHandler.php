<?php
namespace KonomiBundle\Handler;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\HttpUtils;
use KonomiBundle\Entity\User;

/**
 * konomi logout handler class
 *
 * @package konomi
 * @license <property of MWa/MiS>
 */
class LogoutHandler implements LogoutSuccessHandlerInterface {

    /**
     * Container interface object
     * @var ContainerInterface
     */
    protected $oContainer = NULL;

    /**
     * @var TokenStorage
     */
    protected $oTokenStorage;

    /**
     * @var HttpUtils
     */
    protected $oHttpUtils;

    /**
     * @var string
     */
    protected $sTargetUrl;





    /**
     * SecurityListener constructor.
     *
     * @param ContainerInterface $oContainer
     * @param TokenStorage $oTokenStorage
     * @param HttpUtils $httpUtils
     * @param string $targetUrl
     */
    public function __construct( ContainerInterface $oContainer, TokenStorage $oTokenStorage, HttpUtils $httpUtils, $targetUrl = '/' ) {

        // Set objects
        $this->oContainer = $oContainer;
        $this->oTokenStorage = $oTokenStorage;
        $this->oHttpUtils = $httpUtils;

        // Set target URL
        $this->sTargetUrl = $targetUrl;
    }





    /**
     * Logout success
     * Sets the user property 'login' to 0 and the current timestamp when logout was successful.
     *
     * @param Request $oRequest
     * @return Response
     */
    public function onLogoutSuccess( Request $oRequest ) {

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

            // Set login flag to 0 and update timestamp with current datetime object
            $oUser->setLogin( 0 );
            $oUser->setTimestamp( $oDateTime );

            // Flush all changes
            $oManager->flush();
        }

        // Return response object
        return $this->oHttpUtils->createRedirectResponse($oRequest, $this->sTargetUrl);
    }
}
