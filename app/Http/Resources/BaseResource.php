<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    private $status = 200;
    private $message = '';

    public function with($request)
    {
        $fields = [
            'status' => $this->status
        ];

        if ($this->status != 200) {
            $fields['message'] = $this->message;
        }

        return $fields;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }
}
