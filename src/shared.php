<?php

function is_resource_writable($resource): bool {
	if(!is_resource($resource)) {
		return false;
	}
	$mode = stream_get_meta_data($resource)['mode'];
	return str_contains($mode, 'a') ||
		str_contains($mode, 'w');
}

function is_resource_readable($resource): bool {
	if(!is_resource($resource)) {
		return false;
	}
	$mode = stream_get_meta_data($resource)['mode'];
	return str_contains($mode, 'r');
}