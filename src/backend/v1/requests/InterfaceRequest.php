<?php

namespace v1\requests;


interface InterfaceRequest
{
    public function process($data = []);
}