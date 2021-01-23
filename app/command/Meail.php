<?php
declare (strict_types=1);

namespace app\command;

use think\cache\driver\Redis;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Meail extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('Meail')
            ->setDescription('the Meail command');
    }

    protected function execute(Input $input, Output $output)
    {
//        $output->writeln('$toMail');
        // 指令输出
        $date = date("Y-m-d");
        $redis = new Redis(config('cache.redis'));
        $bool = $redis->exists('Meail');
        if (!$bool) return;
        while (true) {
            if ($redis->lLen('Meail') > 0) {
                $toMail = $redis->rPop('Meail');
                $cont = '欢迎管理员' . $toMail . '在' . $date . '成功登陆KaXin后台管理系统';
                sendMail($toMail, '登陆成功', $cont);
//                $output->writeln('成功');
                $output->writeln($toMail);
            }
            sleep(30);
        }
    }
}
