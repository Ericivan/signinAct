<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/13
 * Time: 11:46
 */

namespace App\Traits;


use App\Exceptions\BaseException;
use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{

    private $repMessage = 'succeed';

    private $statusCode = Response::HTTP_OK;

    /**
     * @param $statusCode
     * @author :Ericivan
     * @name : setStatusCode
     * @description
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return int
     * @author :Ericivan
     * @name : getStatusCode
     * @description
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $code
     * @param $httpCode
     * @param null $message
     * @throws BaseException
     * @author :Ericivan
     * @name : error
     * @description
     */
    public function error($code, $httpCode, $message = null)
    {
        throw new BaseException($code, $httpCode, $message??$this->getResponseMessage($code));
    }

    public function success($data = null, $code = 0, $meta = null)
    {
        return $this->respond($data, $code, $meta);
    }

    protected function respond($data = null, $code = 0, $meta = null)
    {
        $result = collect(['message' => $this->repMessage, 'code' => $code]);

        if (is_string($data)) {
            $result = $result->merge(['message' => $data]);
        } elseif (is_array($data)) {
            $result = $result->merge(compact('data'));
        } elseif ($data instanceof Arrayable) {
            $result = $result->merge(['data' => $data->toArray()]);
        }

        if (!is_null($meta)) {
            $result = $result->merge(compact('meta'));
        }

        return response()->json($result->toArray(), $this->getStatusCode());
    }

    protected function getResponseMessage($code)
    {
        return config("response.{$code}.msg", $code > 0 ? 'not found' : $this->repMessage);
    }
}