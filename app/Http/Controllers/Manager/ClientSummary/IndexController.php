<?php

namespace App\Http\Controllers\Manager\ClientSummary;

use App\Services\Manager\ExpensesObject\ExpensesObjectService;
use App\Services\Manager\ClientSummary\Service;

class IndexController extends BaseController
{
    public object $service;
    public object $expenses_object_service;

    public function __construct(Service $service, ExpensesObjectService $expenses_object_service)
    {
        parent::__construct($service);
        $this->expenses_object_service = $expenses_object_service;
    }

    public function __invoke()
    {
        $clients = $this->service->getClientSummaryData();
        foreach ($clients as &$client) {
            $client->expense_warnings = $this->expenses_object_service->getObjectExpenses($client)['expenses_warnings'];
        }
        return view('manager.client_summary.index', compact('clients'));
    }
}
