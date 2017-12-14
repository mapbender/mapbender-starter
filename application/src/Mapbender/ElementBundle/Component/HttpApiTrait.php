<?php

namespace Mapbender\ElementBundle\Component;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Response;
use Zumba\Util\JsonSerializer;

/**
 *  Element HTTP API base trait
 *
 *  Features:
 *
 *  * decode requests
 *  * handle request
 *  * execute public methods based on request
 */
trait HttpApiTrait
{
    use ContainerAwareTrait;

    /**
     *
     * Parse raw HTTP request data
     *
     * Pass in $a_data as an array. This is done by reference to avoid copying
     * the data around too much.
     *
     * Any files found in the request will be added by their field name to the
     * $data['files'] array.
     *
     * @see http://www.chlab.ch/blog/archives/webdevelopment/manually-parse-raw-http-data-php
     * @param   string $content Empty array to fill with data
     * @return  array  Associative array of request data
     */
    public static function parseMultiPartRequest($content)
    {
        $result = array();
        // read incoming data

        // grab multipart boundary from content type header
        preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);

        // content type is probably regular form-encoded
        if (!count($matches)) {
            // we expect regular puts to containt a query string containing data
            parse_str(urldecode($content), $result);
            return $result;
        }

        $boundary = $matches[1];

        // split content by boundary and get rid of last -- element
        $a_blocks = preg_split("/-+$boundary/", $content);
        array_pop($a_blocks);

        // loop data blocks
        foreach ($a_blocks as $id => $block) {
            if (empty($block)) {
                continue;
            }

            // you'll have to var_dump $block to understand this and maybe replace \n or \r with a visibile char

            // parse uploaded files
            if (strpos($block, 'application/octet-stream') !== false) {
                // match "name", then everything after "stream" (optional) except for prepending newlines
                preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
                $keyName = preg_replace('/\[\]$/', '', $matches[1]);

                if (!isset($result[ $keyName ])) {
                    $result[ $keyName ] = array();
                }
                $result[ $keyName ][] = $matches[2];

            } // parse all other fields
            else {
                // match "name" and optional value in between newline sequences
                preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
                $result[ $matches[1] ] = $matches[2];
            }
        }

        return $result;
    }

    /**
     * @return array|mixed
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    protected function getRequestData()
    {
        $content    = $this->container->get('request')->getContent();
        $request    = array_merge($_POST, $_GET);
        $hasContent = !empty($content);

        if ($hasContent) {
            $isMultipart = strpos($content, '-') === 0;
            if ($isMultipart) {
                $request = array_merge($request, static::parseMultiPartRequest($content));
            } else {
                $request = array_merge($request, json_decode($content, true));
            }
        }

        return $this->decodeRequest($request);
    }

    /**
     * Decode request array variables
     *
     * @param array $request
     * @return mixed
     */
    public function decodeRequest(array $request)
    {
        foreach ($request as $key => $value) {
            if (is_array($value)) {
                $request[ $key ] = $this->decodeRequest($value);
            } elseif (strpos($key, '[')) {
                preg_match('/(.+?)\[(.+?)\]/', $key, $matches);
                list($match, $name, $subKey) = $matches;

                if (!isset($request[ $name ])) {
                    $request[ $name ] = array();
                }

                $request[ $name ][ $subKey ] = $value;
                unset($request[ $key ]);
            }
        }
        return $request;
    }

    /**
     * Handles requests (API)
     *
     * Get request "action" variable and run defined action method.
     *
     * Example: if $action="feature/get", then convert name
     *          and run $this->getFeatureAction($request);
     *
     * @inheritdoc
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function httpAction($action)
    {
        $request     = $this->getRequestData();
        $names       = array_reverse(explode('/', $action));
        $namesLength = count($names);
        for ($i = 1; $i < $namesLength; $i++) {
            $names[ $i ][0] = strtoupper($names[ $i ][0]);
        }
        $action     = implode($names);
        $methodName = preg_replace('/[^a-z]+/si', null, $action) . 'Action';
        $result     = $this->{$methodName}($request);

        if (is_array($result)) {
            $serializer   = new JsonSerializer();
            $responseBody = $serializer->serialize($result);
            $result       = new Response($responseBody, 200, array('Content-Type' => 'application/json'));
        }

        return $result;
    }
}
