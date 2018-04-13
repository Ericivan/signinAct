<?php
/**
 * Created by PhpStorm.
 *
 * Date: 2017/9/18
 * Time: 下午1:44
 */

namespace App\Traits;

use App\User;

/**
 * @property \App\User $user
 */
trait Helpers
{
    /**
     * Get the authenticated user.
     *
     * @param bool $verify
     */
    public function user($verify = true)
    {
        try {
            $user = auth('api')->user();

            if ($user instanceof User) {
                return $user;
            }

            return $verify ? $this->errorResponse():false;
        } catch (\Exception $exception) {
            return $verify ?  $this->errorResponse() : false;
        }
    }

    protected function errorResponse()
    {
        return response()->json([
            'code' => 401,
            'msg'=>'unauthorize'
        ]);
    }

    /**
     * Magically handle calls to certain properties.
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function __get($key)
    {
        if (method_exists($this, $key)) {
            return $this->$key();
        }

        throw new \Exception('Undefined property '.get_class($this).'::'.$key, 500);
    }
}