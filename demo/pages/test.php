<?php

if (!empty($params->req)) {
    echo $params->req->uri;
} else {
    echo 'test render';
}
