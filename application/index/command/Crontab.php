<?php
namespace app\index\command;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Crontab extends Command
{
	protected function configure(){
		$this->setName('Crontab')->setDescription('定时任务 Crontab');
	}

	protected function execute(Input $input, Output $output){
		$output->writeln('Date Crontab job start...');

		// 定时任务
		$this->test();
        
        $output->weiteln('Date Crontab job end...');  
	}

	private function test(){
		echo "这是一个定时任务的测试";
	}
}