<?php
class Department extends base
{

    public $depID;
    public $status;
    public $depTitle;
    public $depEmail;
    public $notifyOnTicket;


    function __construct($id = "")
    {
        $this->db_table = "departments";
        $this->ID_field = "depID";
        $this->db_members = array('status', 'depTitle', 'depEmail', 'notifyOnTicket');
        $this->db_checkboxes = array('notifyOnTicket');
        if ($id) {
            $this->load($id);
        }
    }

    function notifyNewTicket(Ticket $Ticket)
    {
        if ($this->notifyOnTicket != '1') {
            return false;
        }

        $data = array(
            'subject'  => 'Yeni Destek Bileti ' . $Ticket->ticketID,
            'to'       => $this->depEmail,
            'body'     => 'Yeni destek bileti oluşturuldu: ' . $Ticket->ticketID,
            'fromName' => $this->depTitle
        );

        Queue::createJob('sendmail')->setParams($data)->update()->start();
    }

    function notifyNewOrder(Order &$Order)
    {
        $data = array(
            'subject'  => 'Yeni Sipariş - ' . $Order->Service->service_name,
            'to'       => $this->depEmail,
            'body'     => 'Yeni sipariş alındı Sipariş No: ' . $Order->orderID,
            'fromName' => $this->depTitle
        );
        Queue::createJob('sendmail')->setParams($data)->update()->start();
    }


} // end of class