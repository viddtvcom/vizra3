<?php

/// system
$sys["title"] = Setting::type('textbox')->lab('Görünen Ad')->val('DotTK')->width(200);
$sys['username'] = Setting::type('textbox')->lab('DotTK ##Username##')->width(200)->depends('status', 'active');
$sys['password'] = Setting::type('textbox')->lab('DotTK ##Password##')->width(200)->encrypted(true)->depends(
    'status',
    'active'
);
