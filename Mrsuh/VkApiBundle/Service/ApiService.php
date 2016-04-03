<?php

namespace Mrsuh\VkApiBundle\Service;

use GuzzleHttp\Client;
use Mrsuh\VkApiBundle\Exception\VkApiAuthenticationException;
use Mrsuh\VkApiBundle\Exception\VkApiRequestException;
use Mrsuh\VkApiBundle\Storage\TokenStorageService;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Session;

class ApiService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $vk_params;

    /**
     * @var TokenStorageService
     */
    private $token_storage;

    /**
     * ApiService constructor.
     * @param TokenStorageService $token_storage
     * @param array $guzzle_params
     * @param array $vk_params
     */
    public function __construct(TokenStorageService $token_storage, array $guzzle_params, array $vk_params)
    {
        $this->vk_params = $vk_params;
        $this->token_storage = $token_storage;
        $this->client = new Client([
            'timeout' => $guzzle_params['timeout'],
            'connect_timeout' => $guzzle_params['connect_timeout']
        ]);
    }

    /**
     * @throws VkApiAuthenticationException
     */
    private function auth()
    {
        try {
            $url_data = http_build_query([
                'client_id' => $this->vk_params['app_id'],
                'scope' => implode(',', $this->vk_params['scope']),
                'redirect_uri' => 'http://oauth.vk.com/blank.html',
                'display' => 'page',
                'response_type' => 'token',
            ]);
            $url = 'https://oauth.vk.com/authorize?' . $url_data;

            $driver = new GoutteDriver();
            $session = new Session($driver);

            $session->start();
            $session->visit($url);

            $page = $session->getPage();

            if ($allow = $page->find('css', '#install_allow')) {
                $allow->click();
            }

            $email = $page->find('css', '[name="email"]');
            $pass = $page->find('css', '[name="pass"]');
            $btn = $page->find('css', '[type="submit"]');

            $email->setValue($this->vk_params['username']);
            $pass->setValue($this->vk_params['password']);

            $btn->click();

            if ($btn = $page->find('css', '[type="submit"]')) {
                $btn->click();
            }

            parse_str($session->getCurrentUrl(), $parse);

            if (!isset($parse['https://oauth_vk_com/blank_html#access_token'])) {
                throw new VkApiAuthenticationException('Authorise failed');
            }

            $this->token_storage->setToken($parse['https://oauth_vk_com/blank_html#access_token']);

        } catch (\Exception $e) {
            throw new VkApiAuthenticationException($e->getMessage());
        }
    }

    /**
     * @param $method
     * @param $url
     * @param array $data
     * @param bool $auth
     * @return mixed
     * @throws VkApiAuthenticationException
     * @throws VkApiRequestException
     */
    public function call($url, array $data = [], $method = 'GET', $auth = false)
    {
        $data['access_token'] = $this->token_storage->getToken();
        $data['v'] = $this->vk_params['version'];

        $response = $this->client->request($method, $this->vk_params['url'] . $url . '?' . http_build_query($data));

        $content = json_decode($response->getBody()->getContents(), true);

        if (array_key_exists('error', $content)) {
            if (!$auth) {
                $this->auth();
                $content = $this->call($url, $data, $method, true);
            } else {
                throw new VkApiRequestException($content['error']['error_msg'], $content['error']['error_code']);
            }
        }

        return $content;
    }
}