<?php

return array(// @hiro サブコマンドレベルで使用するかどうかを選べるように
    // Command main name.
    'MainCommandName'     => 'sjob',
    // Command sub names.
    'SubCommandNames'     => array(
        'Auto'     => 'auto',
        'Change'   => 'change',
        'Do'       => 'do',
        'List'     => 'list',
        'Register' => 'register',
        'Reset'    => 'reset',
        'Show'     => 'show',
        'Sweep'    => 'sweep',
    ),
    // Hashed reset password. ( Reset command will show you hashed string with -? option. )
    'HashedResetPassword' => '',
);