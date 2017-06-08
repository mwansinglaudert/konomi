<?php
namespace KonomiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use KonomiBundle\Entity\Log;
use KonomiBundle\Entity\Template;
use KonomiBundle\Utility\CookieUtility;

/**
 * konomi editor controller class
 *
 * @package konomi
 * @license <property of MWa/MiS>
 */
class EditController extends Controller {

    /**
     * Edit Action
     *
     * @Route("/edit", name="edit")
     * @param Request $oRequest
     * @return object
     */
    public function editAction( Request $oRequest ) {

        // Get AJAX status
        $bAjax = $this->_isAJAX();

        // Initialize cookie utility and get cookie webapp value
        $oCookieUtility = new CookieUtility();
        $sCookieWebApp = $oCookieUtility->get( 'webapp' );

        // Set number formatting
        $iSum = floatval( $oRequest->query->get("sum") );
        $aNumberFormat = $this->getParameter("number_format");
        $iSum = number_format( $iSum, intval($aNumberFormat["decimal"]), $aNumberFormat["decimal_point"], $aNumberFormat["thousand_sep"] );

        // Create createstamp
        $oCreationDateTime  = new \DateTime( $oRequest->query->get("createstamp") );
        $oCreationDateTime->setTimezone( new \DateTimeZone($this->getParameter("date_time_zone")) );

        // Create timestamp
        $oDateTime  = new \DateTime( $oRequest->query->get("timestamp") );
        $oDateTime->setTimezone( new \DateTimeZone($this->getParameter("date_time_zone")) );

        // Get parameters
        $aParams = array(
            'iID'           => $oRequest->query->get( "id" ),
            'sCode'         => $oRequest->query->get( "code" ),
            'sDescription'  => $oRequest->query->get( "description" ),
            'sSum'          => $iSum,
            'iType'         => intval( $oRequest->query->get( "type" ) ),
            'sUser'         => $oRequest->query->get( "user" ),
            'sCreatestamp'  => $oCreationDateTime->format( 'Y-m-d\TH:i:s' ),
            'sTimestamp'    => $oDateTime->format( 'Y-m-d\TH:i:s' ),
        );

        // Set template by AJAX status
        if ( $bAjax ) {
            $sTemplate = "ajax/edit.html.twig";
        }
        else {
            $sTemplate = "edit/edit.html.twig";
        }

        // Render template with variables
        $oResponse = $this->render( $sTemplate, [
            'sBaseDir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'sCookieWebApp' => $sCookieWebApp,
            'aParams' => $aParams,
        ]);

        // Return response
        return $oResponse;
    }


    /**
     * Save Action
     *
     * @Route("/save", name="save")
     * @param Request $oRequest
     * @return object
     */
    public function saveAction( Request $oRequest ) {

        // Get current user
        $oUser = $this->get('security.token_storage')->getToken()->getUser();

        // Get doctrine manager
        $oManager = $this->getDoctrine()->getManager();

        // Get save / delete parameters
        $bNew = (bool)$oRequest->request->get("saveNew");
        $bDelete = (bool)$oRequest->request->get("delete");

        // Get log parameters
        $sUserName = $oUser->getUsername();
        $iID = $oRequest->request->get( "iID" );
        $sCode = $oRequest->request->get( "sCode" );
        $sDescription = $oRequest->request->get( "sDescription" );
        $iSum = floatval( str_replace(",", ".", $oRequest->request->get("sSum")) );
        $iType = $oRequest->request->get( "iType" );
        $sCreatestamp = $oRequest->request->get( "sCreatestamp" );

        // Create 'create timestamp'
        $oCreationDateTime  = new \DateTime( $sCreatestamp );
        $oCreationDateTime->setTimezone( new \DateTimeZone($this->getParameter("date_time_zone")) );

        // Create current timestamp
        $oDateTime  = new \DateTime( "now" );
        $oDateTime->setTimezone( new \DateTimeZone($this->getParameter("date_time_zone")) );

        // Set number formatting
        $aNumberFormat = $this->getParameter("number_format");
        $iSum = number_format( $iSum, intval($aNumberFormat["decimal"]), ".", "" );

        // Check if a new log should be saved or an existing one updated
        if ( $bNew ) {

            // Create new log object and set properties
            $oLog = new Log();
            $oLog->setCode( $sCode );
            $oLog->setDescription( $sDescription );
            $oLog->setSum( $iSum );
            $oLog->setType( $iType );
            $oLog->setUser( $sUserName );
            $oLog->setCreatestamp( $oCreationDateTime );
            $oLog->setTimestamp( $oDateTime );
            $oLog->setDeleted( 0 );

            // Persist object
            $oManager->persist( $oLog );
        }
        else {

            // Get current log
            /** @var Log $oLog */
            $oLog = $oManager->getRepository('KonomiBundle:Log')->find( $iID );

            // Delete or update log
            if ( $bDelete ) {

                // Delete log (don't delete, just set flag!)
//                $oManager->remove( $oLog );
                $oLog->setDeleted( 1 );
            }
            else {

                // Update properties
                $oLog->setDescription( $sDescription );
                $oLog->setSum( $iSum );
                $oLog->setCreatestamp( $oCreationDateTime );
                $oLog->setTimestamp( $oDateTime );
            }
        }

        // Set initial response message for JS (success)
        $sResult = "success";

        // Save changes (new, update or delete) and catch possible exception
        try {
            $oManager->flush();
        }
        catch ( \Exception $oEx ) {
            $sResult = $oEx->getMessage();
        }

        // Return response
        return new Response( $sResult );
    }


    /**
     * New Action
     *
     * @Route("/new", name="new")
     * @param Request $oRequest
     * @return object
     */
    public function newAction( Request $oRequest ) {

        // Get AJAX status
        $bAjax = $this->_isAJAX();

        // Initialize cookie utility and get cookie webapp value
        $oCookieUtility = new CookieUtility();
        $sCookieWebApp = $oCookieUtility->get( 'webapp' );

        // Get current user
        $oUser = $this->get('security.token_storage')->getToken()->getUser();

        // Get yearmonth parameter
        $sYearMonth = $oRequest->query->get( "yearmonth" );

        // Create date time stamp
        if ( empty($sYearMonth) ) {

            // Create date time object of now
            $oDateTime  = new \DateTime( "now" );
            $oDateTime->setTimezone( new \DateTimeZone($this->getParameter("date_time_zone")) );
        }
        else {

            // Create date time object of given year / month
            $oDateTime  = new \DateTime( "$sYearMonth-01 00:00:00" );
            $oDateTime->setTimezone( new \DateTimeZone($this->getParameter("date_time_zone")) );
        }

        // Get public templates
        $oManager = $this->getDoctrine()->getManager();
        $oPublicIncomeTemplates = $oManager->getRepository('KonomiBundle:Template')->findByUserAndType( 1, 'public' );
        $oPublicExpendTemplates = $oManager->getRepository('KonomiBundle:Template')->findByUserAndType( 0, 'public' );

        // Set template by AJAX status
        if ( $bAjax ) {
            $sTemplate = "ajax/new.html.twig";
        }
        else {
            $sTemplate = "edit/new.html.twig";
        }

        // Render template with variables
        $oResponse = $this->render( $sTemplate, [
            'sBaseDir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'sCookieWebApp' => $sCookieWebApp,
            'sDateTime' => $oDateTime->format( 'Y-m-d\TH:i:s' ),
            'oPublicIncomeTemplates' => $oPublicIncomeTemplates,
            'oPublicExpendTemplates' => $oPublicExpendTemplates,
        ]);

        // Return response
        return $oResponse;
    }


    /**
     * New fix Action
     *
     * @Route("/newfix", name="new fix template")
     * @param Request $oRequest
     * @return object
     */
    public function newfixAction( Request $oRequest ) {

        // Get AJAX status
        $bAjax = $this->_isAJAX();

        // Initialize cookie utility and get cookie webapp value
        $oCookieUtility = new CookieUtility();
        $sCookieWebApp = $oCookieUtility->get( 'webapp' );

        // Get current user
        $oUser = $this->get('security.token_storage')->getToken()->getUser();

        // Get user fix templates
        $oManager = $this->getDoctrine()->getManager();
        $oPublicIncomeTemplates = $oManager->getRepository('KonomiBundle:Template')->findByUserAndType( 1, 'public' );
        $oPublicExpendTemplates = $oManager->getRepository('KonomiBundle:Template')->findByUserAndType( 0, 'public' );
        $oUserFixExpendTemplates = $oManager->getRepository('KonomiBundle:Template')->findByUserAndType( -1, $oUser->getUsername() );
        $oUserFixIncomeTemplates = $oManager->getRepository('KonomiBundle:Template')->findByUserAndType( -2, $oUser->getUsername() );

        // Update templates
        $oUserFixExpendTemplates = $this->_updateTemplates( $oUserFixExpendTemplates );
        $oUserFixIncomeTemplates = $this->_updateTemplates( $oUserFixIncomeTemplates );

        // Set template by AJAX status
        if ( $bAjax ) {
            $sTemplate = "ajax/newfix.html.twig";
        }
        else {
            $sTemplate = "edit/newfix.html.twig";
        }

        // Render template with variables
        $oResponse = $this->render( $sTemplate, [
            'sBaseDir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'sCookieWebApp' => $sCookieWebApp,
            'oPublicIncomeTemplates' => $oPublicIncomeTemplates,
            'oPublicExpendTemplates' => $oPublicExpendTemplates,
            'oUserFixExpendTemplates' => $oUserFixExpendTemplates,
            'oUserFixIncomeTemplates' => $oUserFixIncomeTemplates,
        ]);

        // Return response
        return $oResponse;
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


    /**
     * PRIVATE function that updates the link of each template of the given array
     *
     * @param array|object $oTemplates
     * @return array|object
     */
    private function _updateTemplates( $oTemplates ) {

        // Iterate over each template and build link
        /** @var Template $oTemplate */
        foreach ( $oTemplates as $oTemplate ) {

            // Build and set link of current template
            $aParameters = array();
            foreach ( $oTemplate as $sKey => $sValue ) {
                if ( $sValue instanceof \DateTime ) {
                    $sValue = $sValue->format( "Y-m-d H:i:s" );
                }
                if ( $sKey != "link" && $sKey != "deleted" ) {
                    $aParameters[] = $sKey."=".(string)$sValue;
                }
            }
            $sLink = './edit?' . str_replace( " ", "%20", implode("&", $aParameters) );
            $oTemplate->setLink( $sLink );
        }

        // Return updated templates
        return $oTemplates;
    }
}