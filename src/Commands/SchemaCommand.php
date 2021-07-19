<?php

declare(strict_types=1);
/**
 * You know, for fast.
 *
 * @link     https://www.open.ctl.pub
 * @document https://doc.open.ctl.pub
 */
namespace FourLi\Toolkit\Commands;

use FourLi\Toolkit\Utils;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Database\Schema\Schema;
use Hyperf\DbConnection\Db;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * @Command
 */
class SchemaCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    private $desc = '迁移数据操作';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('toolkit:schema');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription($this->desc)
            ->addOption('schema', null, InputOption::VALUE_NONE, '初始化Schema迁移到数据库表')
            ->setHelp($this->genHelpString());
    }

    public function handle()
    {
        Utils::stdLogger('开始初始化命令');

//        if ($this->input->getOption('schema')) {
        if (Schema::hasTable('migrations')) {
            Db::table('migrations')->where('migration', 'like', '%_toolkit_init_%')->delete();
            $this->output->title('删除toolkit_init的迁移记录');
        }

        // 迁移数据库
        $this->execMigration();
        // 生成model文件
        $this->execGenModel();

        $this->output->success('完成');
    }

    private function execGenModel()
    {
        $this->output->title('执行生成模型命令 `./bin/console gen:model`中...');
        $this->call('gen:model');
        $this->output->info('gen:model完成');
    }

    private function execMigration()
    {
        $this->output->title('执行初始化Schema迁移到数据库表命令 `./bin/console migrate`中...');
        $this->call('migrate');
        $this->output->success('migrate完成');
    }

    private function genHelpString()
    {
        return <<<EOF
{$this->desc}
EOF;
    }
}
