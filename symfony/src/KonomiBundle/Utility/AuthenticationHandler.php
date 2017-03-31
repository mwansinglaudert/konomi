<?php
// AuthenticationHandler.php

namespace KonomiBundle\Utility;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface {
    private $router;
    private $session;

    /**
     * Constructor
     *
     * @author 	Joe Sexton <joe@webtipblog.com>
     * @param 	RouterInterface $router
     * @param 	Session $session
     */
    public function __construct( RouterInterface $router, Session $session )
    {
        $this->router  = $router;
        $this->session = $session;
    }

    /**
     * onAuthenticationSuccess
     *
     * @author 	Joe Sexton <joe@webtipblog.com>
     * @param 	Request $request
     * @param 	TokenInterface $token
     * @return 	Response
     */
    public function onAuthenticationSuccess( Request $request, TokenInterface $token )
    {
        // if AJAX login
        if ( $request->isXmlHttpRequest() ) {

            $array = array( 'success' => true ); // data to return via JSON
            $response = new Response( json_encode( $array ) );
            $response->headers->set( 'Content-Type', 'application/json' );

            return $response;

            // if form login
        } else {

            if ( $this->session->get('_security.main.target_path' ) ) {

                $url = $this->session->get( '_security.main.target_path' );

            } else {

                $url = $this->router->generate( 'overview' );

            } // end if

            return new RedirectResponse( $url );

        }
    }

    /**
     * onAuthenticationFailure
     *
     * @author 	Joe Sexton <joe@webtipblog.com>
     * @param 	Request $request
     * @param 	AuthenticationException $exception
     * @return 	Response
     */
    public function onAuthenticationFailure( Request $request, AuthenticationException $exception )
    {
        // if AJAX login
        if ( $request->isXmlHttpRequest() ) {

            $array = array( 'success' => false, 'message' => $exception->getMessage() ); // data to return via JSON
            $response = new Response( json_encode( $array ) );
            $response->headers->set( 'Content-Type', 'application/json' );

            return $response;

            // if form login
        } else {

            // set authentication exception to session
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

            return new RedirectResponse( $this->router->generate( '/' ) );
        }
    }
}