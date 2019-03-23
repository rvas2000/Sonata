<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 21.03.19
 * Time: 14:15
 */

namespace Sonata\Http;


class Response
{
    const TYPE_HTML = 'html';
    const TYPE_TEXT = 'text';
    const TYPE_JSON = 'json';
    const TYPE_XML = 'xml';

    protected static $contentTypeHeaders = [
        self::TYPE_HTML => 'text/html; charset=UTF-8',
        self::TYPE_TEXT => 'text/plain; charset=UTF-8',
        self::TYPE_JSON => 'application/json; charset=UTF-8',
        self::TYPE_XML => 'application/xml; charset=UTF-8',
    ];

    protected $content = '';

    protected $headers = [];

    protected $type = '';

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->content = '';
        $this->setType(self::TYPE_HTML);
    }

    public function setType($type)
    {
        $this->type = $type;
        $this->setHeader('Content-Type', self::$contentTypeHeaders[$this->type]);
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setHeader($header, $content)
    {
        $this->headers[$header] = $content;
    }

    public function flush()
    {
        foreach ($this->headers as $header => $content) {
            header(sprintf("%s: %s", $header, $content), true);
        }

        if ($this->type == self::TYPE_JSON) {
            echo json_encode($this->getContent());
        } else {
            echo $this->getContent();
        }

    }
}