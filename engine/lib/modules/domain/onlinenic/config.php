<?php

/// system

$sys["title"] = Setting::type('textbox')->lab('Görünen Ad')->val('Onlinenic')->width(200);
$sys['customerID'] = Setting::type('textbox')->lab('Onlinenic ##Username##')->width(200)->depends('status', 'active');
$sys['password'] = Setting::type('textbox')->lab('Onlinenic ##Password##')->width(200)->encrypted(true)->depends(
    'status',
    'active'
);

$sys['ol_host'] = Setting::type('textbox')->lab('Host')->width(400)->depends('status', 'active');
$sys['ol_port'] = Setting::type('textbox')->lab('Port')->width(400)->depends('status', 'active');


