<?php

namespace StriveJobs\Commands;

use Illuminate\Console\Command;

class BaseCommand extends Command
{
// @hiro cron用に出力の制御
    /**
     * Set commnad name.
     *
     * @param string $name Command main name.
     */
    public function setCommandName( $mainName, $subName )
    {
        $this->setName( $mainName.':'.$subName );
    }
// @hiro remove コマンドも必要、それに合わせパスワードの仕様も変更
}