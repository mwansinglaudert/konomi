<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Utility\CookieUtility;

/**
 * konomi security controller class
 *
 * @package konomi
 * @license <property of MWa/MiS>
 */
class SecurityController extends Controller {

    /**
     * Password Action that encrypts a static password
     *
     * @Route("/password", name="password")
     * @param Request $oRequest
     * @return object
     */
    public function passwordAction( Request $oRequest ) {

        // Initialize cookie utility and get cookie webapp value
        $oCookieUtility = new CookieUtility();
        $sCookieWebApp = $oCookieUtility->get( 'webapp' );

        // Get password from POST / GET
        $sPlainPass = $oRequest->query->get( "password" );
        if ( empty($sPlainPass) ) { $sPlainPass = $oRequest->query->get( "passcode" ); }
        if ( empty($sPlainPass) ) { $sPlainPass = $oRequest->query->get( "pass" ); }
        if ( empty($sPlainPass) ) { $sPlainPass = $oRequest->request->get( "password" ); }
        if ( empty($sPlainPass) ) { $sPlainPass = $oRequest->request->get( "passcode" ); }
        if ( empty($sPlainPass) ) { $sPlainPass = $oRequest->request->get( "pass" ); }
        if ( empty($sPlainPass) ) { $sPlainPass = 'password'; }

        // Encode password
        $oUser = new \AppBundle\Entity\User();
        $oEncoder = $this->container->get('security.password_encoder');
        $sEncodedPass = $oEncoder->encodePassword($oUser, $sPlainPass);

        // replace this example code with whatever you need
        return $this->render('security/password.html.twig', [
            'sCookieWebApp' => $sCookieWebApp,
            'sPlainPass' => $sPlainPass,
            'sEncodedPass' => $sEncodedPass,
        ]);
    }





    /**
     * PRIVATE function that returns a boolean state if the current request is an AJAX (XML HTTP) request
     *
     * @return bool
     */
    private function _isAJAX() {

        // Check if current request is an AJAX (XML HTTP) request
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) { return TRUE; }
        return FALSE;
    }
}
