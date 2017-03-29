<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Log;
use AppBundle\Entity\Template;
use AppBundle\Utility\CookieUtility;

/**
 * konomi default controller class
 *
 * @package konomi
 * @license <property of MWa/MiS>
 */
class DefaultController extends Controller {

    /**
     * Login Action
     *
     * @Route("/login", name="login")
     * @param Request $oRequest
     * @return object
     */
    public function loginAction( Request $oRequest ) {

        // Get AJAX status
        $bAjax = $this->_isAJAX();
//        $oRequest->isXmlHttpRequest();

        // Initialize cookie utility and get cookie webapp value
        $oCookieUtility = new CookieUtility();
        $sCookieWebApp = $oCookieUtility->get( 'webapp' );

        // Get authentication utility
        $oAuthUtils = $this->get('security.authentication_utils');

        // Get login error if there is one
        $sAuthError = $oAuthUtils->getLastAuthenticationError();

        // Get last username entered by the user
        $sLastUsername = $oAuthUtils->getLastUsername();

        // Set template by AJAX status
        if ( $bAjax ) {
            $sTemplate = "ajax/login.html.twig";
        }
        else {
            $sTemplate = "security/login.html.twig";
        }

        // Render template with variables
        $oResponse = $this->render( $sTemplate, [
            'sBaseDir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'sCookieWebApp' => $sCookieWebApp,
            'last_username' => $sLastUsername,
            'error'         => $sAuthError,
        ]);

        // Return response
        return $oResponse;
    }


    /**
     * Start Action
     *
     * @Route("/")
     * @Route("/start")
     * @param Request $oRequest
     * @return object
     */
    public function startAction( Request $oRequest ) {

        // Get AJAX status
        $bAjax = $this->_isAJAX();

        // Initialize cookie utility and get cookie webapp value
        $oCookieUtility = new CookieUtility();
        $sCookieWebApp = $oCookieUtility->get( 'webapp' );

        // Get current user
        $oUser = $this->get('security.token_storage')->getToken()->getUser();

        // Show login form if no user exists, otherwise the start
        if ( !empty($oUser) && $oUser != "anon." ) {

            // Initialize doctrine manager
            $oManager = $this->getDoctrine()->getManager();

            // Get possible year and month parameter
            $iYear = $oRequest->query->get( "year" );
            $iMonth = $oRequest->query->get( "month" );

            // Create date stamps
            if ( !empty($iYear) && !empty($iMonth) ) {

                // Create date time object of given year / month
                $oDateTime  = new \DateTime( "$iYear-$iMonth-01 00:00:00" );
                $oDateTime->setTimezone( new \DateTimeZone($this->getParameter("date_time_zone")) );
                $sYearMonth = $oDateTime->format( "Y" ) ."-". $oDateTime->format( "m" );
            }
            else {

                // Create date time object of now
                $oDateTime  = new \DateTime( "now" );
                $oDateTime->setTimezone( new \DateTimeZone($this->getParameter("date_time_zone")) );
                $iYear  = $oDateTime->format( "Y" );
                $iMonth = $oDateTime->format( "m" );
                $sYearMonth = NULL;
            }

            // Build date stamps
            $sFromDateStamp = $oDateTime->format( "Y-m" ) . '-01 00:00:00';
            $sToDateStamp   = $oDateTime->format( "Y-m-t" ) . ' 23:59:59';

            // Check fix expends, but only if we don't show data of the past
            if ( empty($sYearMonth) ) {
                $this->_checkFixExpends( $oManager, $oUser, $sFromDateStamp, $sToDateStamp );
            }

            // Get all logs
            $oLogs = $oManager->getRepository('AppBundle:Log')->findByUserAndDate( $sFromDateStamp, $sToDateStamp, $oUser );

            // Initialize balance
            $iBalance = 0;

            // Iterate over each log and get balance
            /** @var Log $oLog */
            foreach ( $oLogs as $oLog ) {

                // Calculate balance
                $iType = intval($oLog->getType());
                if ( $iType == 0 || $iType == -1 ) {
                    $iBalance -= floatval( $oLog->getSum() );
                }
                else {
                    $iBalance += floatval( $oLog->getSum() );
                }

                // Set link for current log entry
                $aParameters = array();
                foreach ( $oLog as $sKey => $sValue ) {
                    if ( $sValue instanceof \DateTime ) {
                        $sValue = $sValue->format( "Y-m-d H:m:s" );
                    }
                    $aParameters[] = $sKey."=".(string)$sValue;
                }
                $sLink = './edit?' . str_replace( " ", "%20", implode("&", $aParameters) );
                $oLog->setLink( $sLink );
            }

            // Get number formatting
            $aNumberFormat = $this->getParameter("number_format");

            // Set template
            if ( $bAjax ) {
                $sTemplate = "ajax/start.html.twig";
            }
            else {
                $sTemplate = "default/start.html.twig";
            }

            // Render template with variables
            $oResponse = $this->render( $sTemplate,
                array(
                    'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                    'sCookieWebApp' => $sCookieWebApp,
                    'aNumberFormat' => $aNumberFormat,
                    'iYear' => $iYear,
                    'iMonth' => $iMonth,
                    'sYearMonth' => $sYearMonth,
                    'oLogs' => $oLogs,
                    'iBalance' => $iBalance,
                )
            );
        }
        else {

            // Execute login action of security controller
            $oResponse = $this->loginAction( $oRequest );
        }

        // Return response
        return $oResponse;
    }


    /**
     * Calendar Action
     *
     * @Route("/calendar")
     * @param Request $oRequest
     * @return object
     */
    public function calendarAction( Request $oRequest ) {

        // Get AJAX status
        $bAjax = $this->_isAJAX();

        // Initialize cookie utility and get cookie webapp value
        $oCookieUtility = new CookieUtility();
        $sCookieWebApp = $oCookieUtility->get( 'webapp' );

        // Get current user
        $oUser = $this->get('security.token_storage')->getToken()->getUser();

        // Get all logs
        $oManager = $this->getDoctrine()->getManager();
        $oLogs = $oManager->getRepository('AppBundle:Log')->findAllByUser( $oUser, 'createstamp', 'DESC' );

        // Create years array
        $aYears = array();

        // Iterate over each log and get balance
        /** @var Log $oLog */
        foreach ( $oLogs as $oLog ) {

            // Get creation time of current log and set year and month
            $oCreationDate = $oLog->getCreatestamp();
            $aDateTime = explode("-", $oCreationDate->format("Y-m-d"));
            $iYear = $aDateTime[0];
            $iMonth = $aDateTime[1];

            // Build year array
            if ( !array_key_exists($iYear, $aYears) ) {
                $aYears[$iYear] = array();
            }

            if ( !in_array($iMonth, $aYears[$iYear]) ) {
                array_push($aYears[$iYear], $iMonth);
            }
        }

        // Set template
        if ( $bAjax ) {
            $sTemplate = "ajax/calendar.html.twig";
        }
        else {
            $sTemplate = "default/calendar.html.twig";
        }

        // Render template with variables
        $oResponse = $this->render( $sTemplate,
            array(
                'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                'sCookieWebApp' => $sCookieWebApp,
                'aYears' => $aYears,
            )
        );

        // Return response
        return $oResponse;
    }


    /**
     * Statistic Action
     *
     * @Route("/statistic")
     * @param Request $oRequest
     * @return object
     */
    public function statisticAction( Request $oRequest ) {

        // Get AJAX status
        $bAjax = $this->_isAJAX();

        // Get number formatting
        $aNumberFormat = $this->getParameter("number_format");

        // Initialize cookie utility and get cookie webapp value
        $oCookieUtility = new CookieUtility();
        $sCookieWebApp = $oCookieUtility->get( 'webapp' );

        // Get current user
        $oUser = $this->get('security.token_storage')->getToken()->getUser();

        // Get all logs
        $oManager = $this->getDoctrine()->getManager();
        $oLogs = $oManager->getRepository('AppBundle:Log')->findAllByUser( $oUser, 'createstamp', 'ASC' );

        // Create statistics array
        $aStatistics = array();

        // Iterate over each log and get balance
        /** @var Log $oLog */
        foreach ( $oLogs as $oLog ) {

            // Get creation time of current log and set year and month
            $oCreationDate = $oLog->getCreatestamp();
            $aDateTime = explode("-", $oCreationDate->format("Y-m-d"));
            $iYear = $aDateTime[0];
            $iMonth = $aDateTime[1];

            // Build year array
            if ( !array_key_exists($iYear, $aStatistics) ) {
                $aStatistics[$iYear] = array();
            }

            // Build month array
            if ( !array_key_exists($iMonth, $aStatistics[$iYear]) ) {
                $aStatistics[$iYear][$iMonth] = array();
            }

            // Get current log code
            $sCode = $oLog->getCode();

            // Get fix expends or dynamic expends and set correct category
            if ( $oLog->getType() === -1 ) {

                $sCategory = 'fix';
            }
            elseif ( $oLog->getType() === 0 ) {

                if ( $sCode === "fuel" ) {
                    $sCategory = $sCode;
                }
                elseif ( $sCode === "foo" ) {
                    $sCategory = $sCode;
                }
                else {
                    $sCategory = 'normal';
                }
            }
            else {
                $sCategory = NULL;
            }

            // Add sums to categories
            if ( !empty($sCategory) ) {

                // Check if current category already exists
                if ( isset($aStatistics[$iYear][$iMonth][$sCategory]) || !empty($aStatistics[$iYear][$iMonth][$sCategory]) ) {
                    $iSum = floatval( $aStatistics[$iYear][$iMonth][$sCategory] );
                }
                else {
                    $iSum = 0;
                }

                // Add sum of current log / category
                $aStatistics[$iYear][$iMonth][$sCategory] = $iSum + floatval( $oLog->getSum() );
            }
        }

        // Get average month expend of each year
        foreach ( $aStatistics as $iYear => $aYear ) {

            $iCount                     = 0;
            $iAverageTotal              = 0;
            $iAverageTotalWithoutFix    = 0;
            $iAverageFix                = 0;
            $iAverageNormal             = 0;
            $iAverageFuel               = 0;
            foreach ( $aYear as $aMonth ) {
                $iCount++;
                $iAverageTotal              += floatval($aMonth['fix']) + floatval($aMonth['normal']) + floatval($aMonth['fuel']);
                $iAverageTotalWithoutFix    += floatval($aMonth['normal']) + floatval($aMonth['fuel']);
                $iAverageFix                += floatval($aMonth['fix']);
                $iAverageNormal             += floatval($aMonth['normal']);
                $iAverageFuel               += floatval($aMonth['fuel']);
            }
            $iAverageTotal              = $iAverageTotal / $iCount;
            $iAverageTotalWithoutFix    = $iAverageTotalWithoutFix / $iCount;
            $iAverageFix                = $iAverageFix / $iCount;
            $iAverageNormal             = $iAverageNormal / $iCount;
            $iAverageFuel               = $iAverageFuel / $iCount;
            $aStatistics[$iYear]['info']['average_total']               = number_format( round($iAverageTotal, 2), 2, ".", "");
            $aStatistics[$iYear]['info']['average_total_without_fix']   = number_format( round($iAverageTotalWithoutFix, 2), 2, ".", "");
            $aStatistics[$iYear]['info']['average_fix']                 = number_format( round($iAverageFix, 2), 2, ".", "");
            $aStatistics[$iYear]['info']['average_normal']              = number_format( round($iAverageNormal, 2), 2, ".", "");
            $aStatistics[$iYear]['info']['average_fuel']                = number_format( round($iAverageFuel, 2), 2, ".", "");
        }
        unset( $aYear, $aMonth, $iYear, $iMonth, $iSum, $iCount, $iAverageTotal, $iAverageFix, $iAverageNormal, $iAverageFuel );

        // Rebuild array (DESC)
        krsort( $aStatistics );

        // Set template
        if ( $bAjax ) {
            $sTemplate = "ajax/statistic.html.twig";
        }
        else {
            $sTemplate = "default/statistic.html.twig";
        }

        // Render template with variables
        $oResponse = $this->render( $sTemplate,
            array(
                'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                'sCookieWebApp' => $sCookieWebApp,
                'aNumberFormat' => $aNumberFormat,
                'aStatistics' => $aStatistics,
            )
        );

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
     * PRIVATE function that checks if the fix expends of the current month already were logged and creates them if not
     *
     * @param ObjectManager $oManager
     * @param User $oUser
     * @param string $sFromDateStamp
     * @param string $sToDateStamp
     * @return void
     */
    private function _checkFixExpends( $oManager, $oUser, $sFromDateStamp, $sToDateStamp ) {

        // Get fix income and expend templates of current user
        $oFixIncomeTemplates = $oManager->getRepository('AppBundle:Template')->findByUserAndType( -2, $oUser->getUsername() );
        $oFixExpendTemplates = $oManager->getRepository('AppBundle:Template')->findByUserAndType( -1, $oUser->getUsername() );

        // Add fix logs
        $this->_addFixLog( $oFixIncomeTemplates, $oManager, $oUser, $sFromDateStamp, $sToDateStamp, -2);
        $this->_addFixLog( $oFixExpendTemplates, $oManager, $oUser, $sFromDateStamp, $sToDateStamp, -1);

        // Save new objects
        $oManager->flush();
    }


    /**
     * PRIVATE function that adds fix logs to the current user if they don't exist for the current month
     *
     * @param Template $oFixTemplates
     * @param ObjectManager $oManager
     * @param User $oUser
     * @param string $sFromDateStamp
     * @param string $sToDateStamp
     * @param integer $iType
     * @return void
     */
    private function _addFixLog( $oFixTemplates, $oManager, $oUser, $sFromDateStamp, $sToDateStamp, $iType ) {

        // Create timestamp
        $oDateTime = new \DateTime( $sFromDateStamp );
        $oDateTime->setTimezone( new \DateTimeZone($this->getParameter("date_time_zone")) );

        // Iterate over each expend template and check if it exists for the current month
        /** @var Template $oFixTemplate */
        foreach ( $oFixTemplates as $oFixTemplate ) {

            // Check if current expend template exists for current user and month
            $oFixExpendLog = $oManager->getRepository('AppBundle:Log')->findByUserTypeCodeDescriptionAndDate( $oUser, $iType, $oFixTemplate->getCode(), $oFixTemplate->getDescription(), $sFromDateStamp, $sToDateStamp );

            // Create current fix expend template as fix log for current month
            if ( empty($oFixExpendLog) ) {

                $oFixExpendLog = new Log();
                $oFixExpendLog->setCode( $oFixTemplate->getCode() );
                $oFixExpendLog->setDescription( $oFixTemplate->getDescription() );
                $oFixExpendLog->setSum( $oFixTemplate->getSum() );
                $oFixExpendLog->setType( $oFixTemplate->getType() );
                $oFixExpendLog->setUser( $oUser->getUsername() );
                $oFixExpendLog->setCreatestamp( $oDateTime );
                $oFixExpendLog->setTimestamp( $oDateTime );
                $oFixExpendLog->setLink( "" );
                $oFixExpendLog->setDeleted( 0 );

                // Persist new log object
                $oManager->persist( $oFixExpendLog );
            }
        }

        // Save new objects
        $oManager->flush();
    }


    /**
     * PRIVATE function that generates a JSon string
     *
     * @param array $data
     * @param array $excluded
     * @return string
     */
    private function _generateJSonData( $data, $excluded=array() ) {

        // JSon string start
        $sJSon = "[\n";

        // JSon array
        $aJSon = array();

        // Iterate over given associative data array
        foreach ( $data as $d ) {

            // Start
            $sJSon .= "{ ";

            // Reset JSon array
            $aJSonValue = array();

            // Iterate over each value of current array
            foreach ( $d as $key => $value ) {

                // Continue if current key is not in the excluded array
                if (! in_array($key, $excluded) ) {

                    // If value is an integer, don't put it in quotes
                    if ( is_int($value) ) {
                        $aJSonValue[] = '"'.$key.'": '.$value."";
                    } else {
                        $aJSonValue[] = '"'.$key.'": "'.$value.'"';
                    }
                }
            }

            // End
            $aJSon[] = "{ ". implode(", ", $aJSonValue) ." }";
        }

        // JSon end
        $sJSon = "[\n". implode(",\n", $aJSon) ."\n]";

        return $sJSon;
    }
}