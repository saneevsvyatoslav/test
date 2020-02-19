<?php
/**
 * @todo перенести в комманды
 * Контроллер для записи Категорий
 * @Secure(roles = 'ROLE_ADMIN')
 * @Route('/testxmlcat', name = 'testcategories')
 */

namespace my\Models\Parser;
require __DIR__.'/../../../../www/vendor/autoload.php';
use my\Exceptions\InvalidArgumentException;
use my\Models\ActiveRecordEntity;

class Parser extends ActiveRecordEntity {
    protected $idEruz;
    protected $status;
    protected $eruzType;
    protected $dateStart;
    protected $dateEnd;
    protected $fio;
    protected $inn;
    protected $ogrnip;
    protected $dateRegistration;
    protected $dateStartDuty;
    protected $msp;
    protected $email;
    protected $em;

    public  static $errorCodes = [
        'CURLE_UNSUPPORTED_PROTOCOL',
        'CURLE_FAILED_INIT',
        'CURLE_URL_MALFORMAT',
        'CURLE_URL_MALFORMAT_USER',
        'CURLE_COULDNT_RESOLVE_PROXY',
        'CURLE_COULDNT_RESOLVE_HOST',
        'CURLE_COULDNT_CONNECT',
        'CURLE_FTP_WEIRD_SERVER_REPLY',
        'CURLE_REMOTE_ACCESS_DENIED',
        'CURLE_FTP_WEIRD_PASS_REPLY',
        'CURLE_FTP_WEIRD_PASV_REPLY',
        'CURLE_FTP_WEIRD_227_FORMAT',
        'CURLE_FTP_CANT_GET_HOST',
        'CURLE_FTP_COULDNT_SET_TYPE',
        'CURLE_PARTIAL_FILE',
        'CURLE_FTP_COULDNT_RETR_FILE',
        'CURLE_QUOTE_ERROR',
        'CURLE_HTTP_RETURNED_ERROR',
        'CURLE_WRITE_ERROR',
        'CURLE_UPLOAD_FAILED',
        'CURLE_READ_ERROR',
        'CURLE_OUT_OF_MEMORY',
        'CURLE_OPERATION_TIMEDOUT',
        'CURLE_FTP_PORT_FAILED',
        'CURLE_FTP_COULDNT_USE_REST',
        'CURLE_RANGE_ERROR',
        'CURLE_HTTP_POST_ERROR',
        'CURLE_SSL_CONNECT_ERROR',
        'CURLE_BAD_DOWNLOAD_RESUME',
        'CURLE_FILE_COULDNT_READ_FILE',
        'CURLE_LDAP_CANNOT_BIND',
        'CURLE_LDAP_SEARCH_FAILED',
        'CURLE_FUNCTION_NOT_FOUND',
        'CURLE_ABORTED_BY_CALLBACK',
        'CURLE_BAD_FUNCTION_ARGUMENT',
        'CURLE_INTERFACE_FAILED',
        'CURLE_TOO_MANY_REDIRECTS',
        'CURLE_UNKNOWN_TELNET_OPTION',
        'CURLE_TELNET_OPTION_SYNTAX',
        'CURLE_PEER_FAILED_VERIFICATION',
        'CURLE_GOT_NOTHING',
        'CURLE_SSL_ENGINE_NOTFOUND',
        'CURLE_SSL_ENGINE_SETFAILED',
        'CURLE_SEND_ERROR',
        'CURLE_RECV_ERROR',
        'CURLE_SSL_CERTPROBLEM',
        'CURLE_SSL_CIPHER',
        'CURLE_SSL_CACERT',
        'CURLE_BAD_CONTENT_ENCODING',
        'CURLE_LDAP_INVALID_URL',
        'CURLE_FILESIZE_EXCEEDED',
        'CURLE_USE_SSL_FAILED',
        'CURLE_SEND_FAIL_REWIND',
        'CURLE_SSL_ENGINE_INITFAILED',
        'CURLE_LOGIN_DENIED',
        'CURLE_TFTP_NOTFOUND',
        'CURLE_TFTP_PERM',
        'CURLE_REMOTE_DISK_FULL',
        'CURLE_TFTP_ILLEGAL',
        'CURLE_TFTP_UNKNOWNID',
        'CURLE_REMOTE_FILE_EXISTS',
        'CURLE_TFTP_NOSUCHUSER',
        'CURLE_CONV_FAILED',
        'CURLE_CONV_REQD',
        'CURLE_SSL_CACERT_BADFILE',
        'CURLE_REMOTE_FILE_NOT_FOUND',
        'CURLE_SSH',
        'CURLE_SSL_SHUTDOWN_FAILED',
        'CURLE_AGAIN',
        'CURLE_SSL_CRL_BADFILE',
        'CURLE_SSL_ISSUER_ERROR',
        'CURLE_FTP_PRET_FAILED',
        'CURLE_FTP_PRET_FAILED',
        'CURLE_RTSP_CSEQ_ERROR',
        'CURLE_RTSP_SESSION_ERROR',
        'CURLE_FTP_BAD_FILE_LIST',
        'CURLE_CHUNK_FAILED'
    ];

    protected static function getTableName():string{
        // TODO: Implement getTableName() method.
        return 'eruz';
    }


    public static function getPage($params = []){
        if($params){
            if(!empty($params['url'])){
                $url = $params['url'];
                $userAgent      = !empty($params['useragent']) ? $params['useragent'] : 'Mozilla/5.0 (Windows NT 6.3; W…) Gecko/20100101 Firefox/57.0';
                $timeout        = !empty($params['timeout']) ? $params['timeout'] : 5;
                $connectTimeOut = !empty($params['connectTimeOut']) ? $params['connectTimeOut'] : 5;
                $head           = !empty($params['head']) ? $params['head'] : false;
                $cookieFile    = !empty($params['cookie']['file']) ? $params['cookie']['file'] : false;
                $cookieSession = !empty($params['cookie']['session']) ? $params['cookie']['session'] : false;
                $proxyIp   = !empty($params['proxy']['ip']) ? $params['proxy']['ip'] : false;
                $proxyPort = !empty($params['proxy']['port']) ? $params['proxy']['port'] : false;
                $proxyType = !empty($params['proxy']['type']) ? $params['proxy']['type'] : false;
                $headers = !empty($params['headers']) ? $params['headers'] : false;
                $post = !empty($params['post']) ? $params['post'] : false;

                if($cookieFile){
                    file_put_contents(__DIR__.'/'.$cookieFile, '');
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
                curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connectTimeOut);

                if($head){
                    curl_setopt($ch, CURLOPT_HEADER, true);
                    curl_setopt($ch, CURLOPT_NOBODY, true);
                }
                if(strpos($url, 'https') !== false){
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                }
                if($cookieFile){
                    curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__.'/'.$cookieFile);
                    curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__.'/'.$cookieFile);
                    if($cookieSession){
                        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
                    }
                }
                if($proxyIp && $proxyPort && $proxyType){

                    curl_setopt($ch, CURLOPT_PROXY, $proxyIp.':'.$proxyPort);
                    curl_setopt($ch, CURLOPT_PROXYTYPE, $proxyType);
                }
                if($headers){

                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                }
                if($post){

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                }
                // Далее продолжаем кодить тут
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);

                $content = curl_exec($ch);
                $info = curl_getinfo($ch);
                $error = false;
                if($content === false){
                    $data = false;
                    $error['message'] = curl_error($ch);
                    $error['code']      = self::$errorCodes [
                    curl_errno($ch)
                    ];
                } else {
                    $data['content'] = $content;
                    $data['info'] = $info;
                }
                curl_close($ch);
                return [
                    'data' => $data,
                    'error' => $error
                ];
                }
        }
        return false;
    }
    private function toStringDate($string):string{
        preg_match("/[0-9]{2}.[0-9]{2}.[0-9]{4}/",$string, $match);
        return $match[0].'::date';
    }
    private function emToArray($string):string{
        $str = str_replace('  ', ',', preg_replace('/\r|\n|\s+/', ' ', $string));
        $splitStr = explode(',', $str);

        $toArray = [];
        foreach ($splitStr as $key => $item) {
            $toInt = [
                1 => 'Национальная электронная площадка',
                2 => 'ЭТП Газпромбанк',
                3 => 'ЭТП ТЭК-Торг',
                4 => 'РТС-тендер',
                5 => 'АГЗ РТ',
                6 => 'ЗАО «Сбербанк-АСТ»',
                7 => 'АО «ЕЭТП»',
                8 => 'АО "РАД"'];
            foreach ($toInt as $Int => $value) {
                if ($item == $value) {
                    $toArray[] = $Int;
                }
            }
        }
        $toArray1 = '{'.implode(',',$toArray).'}'.'::integer[]';
        return $toArray1;
    }
    private function ruToBoolean($str):bool {
        if($str == 'Да'){
            return true;
        }

    else return false ;
    }

    public static function toInsertE(array $erData) {

        $parser = new Parser();
        $parser -> idEruz = $erData['idEruz'];
        $parser -> status = $erData['status'] ;
        $parser -> eruzType = $erData['eruzType'] ;
        $parser -> dateStart =  self::toStringDate($erData['dateStart']) ;

        $parser -> dateEnd =  self::toStringDate($erData['dateEnd']) ;
        $parser -> fio = $erData['fio'] ;
        $parser -> inn = $erData['inn'] ;
        $parser -> ogrnip = $erData['ogrnip'] ;
        $parser -> dateRegistration =  self::toStringDate($erData['dateRegistration']);
        $parser -> dateStartDuty =  self::toStringDate($erData['dateStartDuty']);
        $parser -> msp = self::ruToBoolean($erData['msp']) ;
        $parser -> email = $erData['email'] ;
        $parser -> em = self::emToArray($erData['em']) ;

        $parser -> save();

        return $parser;
    }

}