<?php

namespace Develpr\AlexaApp\Response\Directives\AudioPlayer;

use Develpr\AlexaApp\Response\Directives\Directive;

class Play extends Directive
{
    const DEFAULT_PLAY_BEHAVIOR = 'REPLACE_ALL';

    const TYPE = 'AudioPlayer.Play';

    private $validPlayBehaviors = ['REPLACE_ALL', 'ENQUEUE', 'REPLACE_ENQUEUED'];

    protected $url = '';

    protected $token = '';

    protected $playBehavior = '';

    protected $offsetInMilliseconds = '';

    protected $expectedPreviousToken = '';

    protected $metadata = [];

    protected $backgroundImage = [];


    /**
     * Play constructor.
     *
     * @param string $url
     * @param string $token
     * @param string $playBehavior
     * @param string $expectedPreviousToken
     */
    public function __construct($url, $token = '', $offsetInMilliseconds = 0, $playBehavior = null, $expectedPreviousToken = '')
    {
        $this->url = $url;
        $this->token = $token ?: str_random(64);
        $this->playBehavior = $playBehavior ?: self::DEFAULT_PLAY_BEHAVIOR;
        $this->offsetInMilliseconds = $offsetInMilliseconds;
        $this->expectedPreviousToken = $expectedPreviousToken;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $playAsArray['type'] = self::TYPE;
        $playAsArray['audioItem']['stream'] = [];

        $this->addAttributeToArray('playBehavior', $playAsArray);
        $this->addAttributeToArray('url', $playAsArray['audioItem']['stream']);
        $this->addAttributeToArray('token', $playAsArray['audioItem']['stream']);
        $this->addAttributeToArray('offsetInMilliseconds', $playAsArray['audioItem']['stream']);

        if($this->playBehavior == 'ENQUEUE')
        {
            $this->addAttributeToArray('expectedPreviousToken', $playAsArray['audioItem']['stream']);
        }

        if(is_array($this->metadata) && count($this->metadata))
        {
            $playAsArray['metadata'] = $this->metadata;
        }

        if(is_array($this->backgroundImage) && count($this->backgroundImage))
        {
            $playAsArray['backgroundImage'] = $this->backgroundImage;
        }

        return $playAsArray;
    }



    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @param string $offsetInMilliseconds
     *
     * @return $this
     */
    public function setOffsetInMilliseconds($offsetInMilliseconds)
    {
        $this->offsetInMilliseconds = $offsetInMilliseconds;

        return $this;
    }

    /**
     * @param $playBehavior
     * @return $this
     * @throws \Exception
     */
    public function setPlayBehavior($playBehavior)
    {
        if (!in_array($playBehavior, $this->validPlayBehaviors)) {
            throw new \Exception('Invalid play behavior supplied');
        }

        $this->playBehavior = $playBehavior;

        return $this;
    }

    /**
     * @param $expectedPreviousToken
     * @return $this
     * @throws \Exception
     */
    public function setExpectedPreviousToken($expectedPreviousToken)
    {
        if ($this->playBehavior != 'ENQUEUE') {
            throw new \Exception('ExpectedPreviousToken is only allowed for play behavior ENQUEUE');
        }

        $this->expectedPreviousToken = $expectedPreviousToken;

        return $this;
    }


    /**
     * @param $title
     * @return $this
     */
    public function setMetadataTitle($title)
    {
        $this->metadata['title'] = $title;

        return $this;
    }

    /**
     * @param $subtitle
     * @return $this
     */
    public function setMetadataSubtitle($subtitle)
    {
        $this->metadata['subtitle'] = $subtitle;

        return $this;
    }


    /**
     * @param $url
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function setMetadataArtSourcesImage($url,$width = 0,$height = 0)
    {
        $image['url'] = $url;

        if ($width && $height)
        {
            $image['width'] = $width;
            $image['height'] = $height;
        }

        $this->metadata['art']['sources'][] = $image;

        return $this;
    }


    public function setBackgroundImageSourceUrl($url)
    {
        $this->backgroundImage['sources']['url'] = $url;

        return $this;
    }




    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getPlayBehavior()
    {
        return $this->playBehavior;
    }

    /**
     * @return integer
     */
    public function getOffsetInMilliseconds()
    {
        return $this->offsetInMilliseconds;
    }

    /**
     * @return integer
     */
    public function getExpectedPreviousToken()
    {
        return $this->expectedPreviousToken;
    }


    /**
     * @return mixed|null
     */
    public function getMetadataTitle()
    {
        return isset($this->metadata['title']) ? $this->metadata['title']: null;
    }

    /**
     * @return string|null
     */
    public function getMetadataSubtitle()
    {
        return isset($this->metadata['subtitle']) ? $this->metadata['subtitle']: null;
    }

    /**
     * @return string|null
     */
    public function getMetadataArtSourcesUrl()
    {
        return isset($this->metadata['art']['sources']['url'] ) ? $this->metadata['art']['sources']['url'] : null;
    }

    /**
     * @return string|null
     */
    public function getBackgroundImageSourceUrl()
    {
        return isset($this->backgroundImage['sources']['url'] ) ? $this->backgroundImage['sources']['url'] : null;
    }

}
