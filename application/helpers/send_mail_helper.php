<?php

function sendMail() {
    $config = Array(
        'protocol' => 'smtp',
        'smtp_host' => 'ssl://smtp.gmail.com',
        'smtp_port' => 465,
        'smtp_user' => 'nayanmulla@gmail.com',
        'smtp_pass' => 'rajiya$$karishma',
    );
    $this->load->library('email', $config);
    $this->email->set_newline("\r\n");


    $this->email->from('nayanmulla@gmail.com', 'oasdfapen');
    $this->email->to('mujaffar.sanadi01@gmail.com');
    $this->email->subject('subject');
    $this->email->message(html_entity_decode('yahoo'));

    if ($this->email->send()) {
        echo "Done!";
    } else {
        echo $this->email->print_debugger();
    }
}
