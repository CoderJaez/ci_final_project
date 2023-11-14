<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TicketModel;

class DashboardController extends BaseController
{
    public function index()
    {

        $ticket = new TicketModel();

        $pending = $ticket->where("status", "PENDING")->countAllResults();
        $processing = $ticket->where("status", "PROCESSING")->countAllResults();
        $resolved = $ticket->where("status", "RESOLVED")->countAllResults();
        $tickets = $ticket->countAll();

        $data = array(
            'pending' => $pending,
            'processing' => $processing,
            'resolved' => $resolved,
            'tickets' => $tickets
        );



        return view('dashboard', $data);
    }
}
