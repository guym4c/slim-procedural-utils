const API_HEADERS = [];
const API_BASE_URL = '';

function runPDO($db, $sql, $params = null) {
    /** @var $db PDO */
    if (!$params) return $db->query($sql);

    $q = $db->prepare($sql);
    $q->execute($params);
    return $q;
}

/**
 * @param string $url The URL we are sending the request to
 * @param array $data The GET query or POST request payload to send
 * @param array $headers An array of HTTP headers
 * @param string $method The HTTP method
 * @return false|string False on failure, or the request response on success
 */
function performRequest(string $url, array $data, array $headers, string $method) {
    $options = array(
        'http' => array(
            'header'  => $headers,
            'method'  => $method,
        )
    );
    $query = http_build_query($data);
    if ($method == 'GET') {
        $url = "$url?$query";
    } else {
        $options['http']['content'] = $query;
    }
    $context  = stream_context_create($options);
    return file_get_contents($url, false, $context);
}

/**
 * @param string $uri The Yummly API endpoint
 * @param array $data The GET query or POST request payload to send
 * @return array JSON response as an array
 */
function requestAPI(string $uri, array $data) {
    return json_decode(performRequest(API_BASE_URL . '$uri', $data, [API_HEADERS], 'GET'), true);
}

/**
 * @param string $url The URL to POST the data to
 * @param array $data The data payload
 * @return false|string False on failure, string response on success
 */
function postData(string $url, array $data) {
    return performRequest($url, $data, ['Content-type: application/x-www-form-urlencoded'], 'POST');
}
