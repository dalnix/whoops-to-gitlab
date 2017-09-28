<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 2017-09-27
 * Time: 08:10
 */

namespace Dalnix\WhoopsToGitlab;


use GuzzleHttp\Client;

class Gitlab
{
    public function addLabel($data)
    {
        $response = self::createRequest('POST', 'labels', $data);

        if (isset($response->error)) {
            dd($response);
        } else {
            return $response;
        }
    }

    public function getLabels($data)
    {
        $response = self::createRequest('GET', 'labels', $data);

        if (isset($response->error)) {

        } else {
            return $response;
        }
    }

    public function addIssue($data)
    {
        $response = self::createRequest('POST', 'issues', $data);

        if (isset($response->error)) {
            dd($response);
        } else {
            return $response;
        }
    }

    public function createRequest($requestType, $type, $postfields = null)
    {
        $baseurl = config('gitlab.connection.url');
        $headers = array(
            'Content-Type' => 'application/json',
            'PRIVATE-TOKEN' => config('gitlab.connection.token')
        );

        $client = new Client(['base_uri' => $baseurl]);
        if ($requestType == 'POST') {
            $url = $type . '?' . http_build_query($postfields);
            try {

                $response = $client->request('POST', $url, ['headers' => $headers]);
                $response = $response->getBody()->getContents();
            } catch (\Exception $e) {

                if ($e->getCode() == 409) {
                    $response = json_encode([
                        'message' => 'Label already exists'
                    ]);
                }
            }

        } elseif ($requestType == 'GET') {
            if (count($postfields)) {
                $postfields = '?' . http_build_query($postfields);
            } else {
                $postfields = '';
            }
            $url =  $type . '' . $postfields;

            $response = $client->request('GET', $url, ['headers' => $headers]);

            $response = $response->getBody()->getContents();
        }


        return json_decode($response);
    }
}