<?php

namespace App\Http\Sections\Life;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminFormElement;
use App\Http\Controllers\Life\SystemsController;
use App\Models\Chocolife\LifeC24h;
use App\Models\Chocolife\LifeCloud;
use App\Models\Chocolife\LifeHome;
use App\Models\Chocolife\LifeKaspi;
use App\Models\Chocolife\LifePost;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Facades\FormElement;
use SleepingOwl\Admin\Form\FormElements;
use SleepingOwl\Admin\Section;

/**
 * Class LifeEpay
 *
 * @property \App\Models\Holding\Epay $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class LifeSystems extends Section implements Initializable {
    /**
     * @see http://sleepingowladmin.ru/docs/model_configuration#ограничение-прав-доступа
     *
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $alias;

    /**
     * Initialize class.
     */
    public function initialize()
    {

    }

    public function getTitle()
    {
        return 'Транзакции life';
    }

    /**
     * @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay($payload = [])
    {
        $tabs = AdminDisplay::tabbed();
        $tables = $this->getTables();
        foreach ($tables as $name => $table) {
            $tabs->appendTab($table, $name);
        }
        $tabs->appendTab(AdminFormElement::view('forms.exportLife', $data=[]), 'Экспорт');
        return $tabs;
    }

    public function getColumns($projectName)
    {
        return [
            AdminColumn::text('sysRef', $projectName . ' Референс'),
            AdminColumn::text('lifeRef', 'Life Референс'),
            AdminColumn::text('sysSum', $projectName . ' Сумма'),
            AdminColumn::text('lifeSum', 'Life Сумма'),
            AdminColumn::text('sysDate', $projectName . ' Дата'),
            AdminColumn::text('lifeDate', 'Life Дата')
        ];

    }

    public function getTables()
    {
        $filters = [AdminColumnFilter::text()->setPlaceholder('Референс'),
            AdminColumnFilter::text()->setPlaceholder('Референс'),
            null,
            null,
            AdminColumnFilter::range()->setFrom(
                AdminColumnFilter::date()->setPlaceholder('Дата от')->setFormat('d-m-Y H:i')
            )->setTo(
                AdminColumnFilter::date()->setPlaceholder('Дата до')->setFormat('d-m-Y H:i')
            ),
            AdminColumnFilter::range()->setFrom(
                AdminColumnFilter::date()->setPlaceholder('Дата от')->setFormat('d-m-Y H:i')
            )->setTo(
                AdminColumnFilter::date()->setPlaceholder('Дата до')->setFormat('d-m-Y H:i')
            ),
        ];

        $psController = new PaymentSystemsController();
        $paySystems = $psController->getPSLife();
        $tables = [];
        foreach ($paySystems as $paySystem) {
            $tables[$paySystem] = AdminDisplay::datatablesAsync()
                ->setName($paySystem)
                ->setModelClass($psController->getModelClass($paySystem))
                ->setColumns($this->getColumns($paySystem))
                ->setHtmlAttribute('class', $paySystem)
                ->paginate(20);
            $tables[$paySystem]->setColumnFilters($filters)->setPlacement('table.header');
        }

        return $tables;
    }

    /**
     * @param int|null $id*
     * @return FormInterface
     */
    public function isEditable(Model $model)
    {
        return false;
    }

    /**
     * @return FormInterface
     */
    public function isCreatable()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isDeletable(Model $model)
    {
        return false;
    }
}
