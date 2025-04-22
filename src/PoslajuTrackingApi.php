<?php 

namespace Afzafri;

class PoslajuTrackingApi
{
    public static function crawl($trackingNo, $include_info = false)
    {
		$domain = "ttu-svc.pos.com.my";
        $url = "https://$domain/api/trackandtrace/v1/request";
        $postData = json_encode([
            "connote_ids" => [trim($trackingNo)],
            "culture" => "en"
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // timeout in seconds
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        
        // Set headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'P-Request-ID: ' . self::generateUUID(),
            'Accept: application/json, text/plain, */*',
            'Content-Type: application/json',
            'host: ' . $domain
        ]);
        
        $result = curl_exec($ch);
        $httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errormsg = (curl_error($ch)) ? curl_error($ch) : "No error";
        curl_close($ch);

        // Initialize response array
        $trackres = array();
        $trackres['http_code'] = $httpstatus;
        $trackres['error_msg'] = $errormsg;

        // Parse JSON response
        $jsonData = json_decode($result, true);
        
        // Check if response is valid and has tracking data
        if ($httpstatus == 200 && !empty($jsonData) && isset($jsonData['data']) && !empty($jsonData['data'])) {
            $trackingData = $jsonData['data'][0] ?? null;
            
            if ($trackingData && isset($trackingData['tracking_data']) && !empty($trackingData['tracking_data'])) {
                $trackres['status'] = 1;
                $trackres['message'] = "Record Found";

                // Process tracking events
                $trackres['data'] = [];
                foreach ($trackingData['tracking_data'] as $i => $event) {
                    $trackres['data'][$i] = [
                        'date_time' => isset($event['date']) ? $event['date'] : '',
                        'process' => isset($event['process']) ? $event['process'] : '',
                        'event' => isset($event['process_summary']) ? $event['process_summary'] : '',
                    ];
                }
            } else {
                $trackres['status'] = 0;
                $trackres['message'] = "No Record Found";
            }
        } else {
            $trackres['status'] = 0;
            $trackres['message'] = "No Record Found or API Error";
        }

        if ($include_info) {
            // Project info
            $trackres['info']['creator'] = "Afif Zafri (afzafri)";
            $trackres['info']['project_page'] = "https://github.com/afzafri/Poslaju-Tracking-API";
            $trackres['info']['date_updated'] = "22/04/2025"; // Updated to current date in DD/MM/YYYY format
        }

        return $trackres;
    }

    private static function generateUUID()
    {
        // Format: xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx
        // where y is replaced with (3 & random_value | 8) to ensure variant 1
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // Version 4
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // Variant 1
        
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
