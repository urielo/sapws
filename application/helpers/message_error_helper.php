<?php

function messageErro()
{
    return $this->response(array(
                    'status' => 'Error',
                    'message' => array('segurado' => 'Deve-se passar o objeto segurado.'),
                    ), REST_Controller::HTTP_BAD_REQUEST);
}
