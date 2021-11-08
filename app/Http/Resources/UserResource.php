<?php

namespace App\Http\Resources;


class UserResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->resource;

        return [
            'name' => $resource['name'],
            'lastName' => $resource['last_name'],
            'firstName' => $resource['last_name'],
            'phoneCode' => $resource['phone_code'],
            'phoneNumber' => $resource['phone_number'],
            'email' => $resource['email'],
            'emailVerifiedAt' => empty($resource['email_verified_at'])
                ? null
                : $resource['email_verified_at']->format('c'),
            'createdAt' => $resource['created_at']->format('c'),
        ];
    }
}
